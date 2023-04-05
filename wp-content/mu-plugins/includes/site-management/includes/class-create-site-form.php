<?php
namespace Site_Management;
use \Mu_Plugins\Form as Form;
use \Ldap\Ad_User as Ad_User;

defined( 'ABSPATH' ) OR exit;

class Create_Site_Form extends Form
{
  protected $pirate_id;

  protected $site_domain;

  protected $site_path;

  protected $site_title;

  protected $notify_admin;

  public function set_pirate_id($value) {
    $this->pirate_id = sanitize_text_field($value);
  }

  public function get_pirate_id() {
    return $this->pirate_id;
  }

  public function set_site_domain($value) {
    $this->site_domain = sanitize_text_field($value);
  }

  public function get_site_domain() {
    return $this->site_domain;
  }

  public function set_site_path($value) {
    $this->site_path = sanitize_text_field($value);
  }

  public function get_site_path() {
    return $this->site_path;
  }

  public function set_site_title($value) {
    $this->site_title = sanitize_text_field($value);
  }

  public function get_site_title() {
    return $this->site_title;
  }

  public function set_notify_admin($value) {
    $this->notify_admin = sanitize_text_field($value);
  }

  public function get_notify_admin() {
    return $this->notify_admin;
  }

  public function process()  {
  
    // ensures form was not submitted without pirateid
    if (empty($this->pirate_id)) {
      $this->errors[] = 'Valid Pirate ID is required.';
      return;
    } 

    // ensures form was not submitted without site url
    if (empty($this->site_domain)) {
      $this->errors[] = 'Valid Site URL is required.';
      return;
    }

   // ensures form was not submitted without site title
    if (empty($this->site_title)) {
      $this->errors[] = 'Site Title is required.';
      return;
    } 
      
    // searches for given pirate ID
    $user = new Ad_User($this->pirate_id);
    if(!$user->is_valid()){
      $this->errors[] = 'Error: Invalid PirateID'; // invalid pirate id
      return;
    }

    // Create User if needed
    if(!$user->get_wp_id()) {
      $user->create_wp_user();
    }

    // Default options for site
    // Other options are set in the init script.  Those are options
    // we do not want changed and to be reverted if they were.   These
    // options are changeable.
    $email =  sanitize_email(strtolower($user->get_user()->getEmail()));
    $options = [ 
      'default_comment_status' => 'closed',
      'default_ping_status' => 'closed',
      'close_comments_days_old' => '1',
      'close_comments_for_old_posts' => '1',
      'thread_comments' => '',
      'timezone_string' => 'America/New_York',    
      'default_role','blog_owner',
      'gmt_offset' => '-4',
      'admin_email' => $email,
      'blog_public' => 0,
      'blogdescription' => '',
      'require_name_email' => '1', 
      'comment_registration' => '1',
      'moderation_notify' => '1',
      'comments_notify' => '1',
      'comment_moderation' => '1',
      'comment_whitelist' => '1'
    ];

    // creates new blog
    $blog_id = wpmu_create_blog(
      $this->site_domain, // domain
      $this->site_path, // path
      $this->site_title, // title
      $user->get_wp_id(), 
      $options, // default options...set during init so none passed in here
      get_current_network_id() // the network it belongs to.
    );

    if(is_wp_error($blog_id)){
      $this->errors[] = 'Error: ' . $blog_id->get_error_message(); // new blog could not be created; internal wp error
      return;
    }

    switch_to_blog($blog_id);
    
    $this->results[] = $this->init_site();

    $this->results[] = Init_Site::activate_plugins();
    $this->results[] = Init_Site::init_roles();
    $this->results[] = Init_Site::init_widgets();
    $this->results[] = Init_Site::init_itcs_users();
    $this->results[] = Init_Site::init_cs_users();
    $this->results[] = Init_Site::init_options();

    // Switch back to blog
    restore_current_blog();
    
    if($this->notify_admin){
      $sent = wp_mail(
        $email,
        'Your WordPress Site is Ready',
        sprintf(__('Your site %1$s is ready. You can access it by logging in at: %2$s'), $this->site_title, get_admin_url($blog_id))
      );
      if($sent){
        $this->results[] = "Site successfully created, initialized and admin was notified.<br />}"; // site success, notification success
      } else {
        $this->results[] = "Site successfully created, initialized but there was an error in notifying admin.<br />"; // site success, notification failure
      }
    } else {
      $this->results[] = "Site was successfully created and initialized.<br />"; // site success, no notification
    }
  }

  private function init_site() {

    $result = '<h3>Site Initializatoin</h3><ul>';

    //create featured category
    if(wp_create_category( 'featured' )) {
      $result .= '<li>Featured category created</li>';
    } else {
      $result .= '<li>Error: Unable to create the featured category</li>';
    }

    //remove default comment
    wp_delete_comment(1, true);
    $result .= '<li>Removed Default Comment</li>';

    //// Check if the menu exists
    $menu_name = 'Primary';
    $menu_exists = wp_get_nav_menu_object( $menu_name );
    //create menu and add contact page
    if( !$menu_exists){
      $result .= '<li>Created New Menu with Contact Us Page</li>';
        $menu_id = wp_create_nav_menu($menu_name);
    }   else    {
      $menu = wp_get_nav_menus(array('slug'=>'primary'));
      foreach ($menu as $m) {
        if ($m->slug == 'primary') {
          $menu_id = $m->term_id;
        }
      }
    }
    $current_nav = get_theme_mod( 'nav_menu_locations' );
    //assign menu to primary template spot
    if (!$current_nav['primary']) {
      $locations['primary'] = $menu_id;
      set_theme_mod('nav_menu_locations', $locations);
      $result .= '<li>Set the primary menu</li>';
    }
    if ( is_plugin_active( 'ninja-forms/ninja-forms.php' ) ) {
      //get id of contact form
      if($forms = Ninja_Forms()->form()->get_forms()){
        //find the contact me form
        foreach( $forms as $form ){
          $model = Ninja_Forms()->form( $form->get_id() )->get();
          $title = $model->get_setting( 'title' );
          if ($title == 'Contact Me') {
            //set id to add content to page
            $form_id = $form->get_id();
            //change title to contact US
            $model->update_setting( 'title', 'Contact Us' )->save();
          }
        }
        $ninja_forms_shortcode = '[ninja_form id='.(string)$form_id.']';
        //check if contact page exists
        $contact_page = get_page_by_title('Contact Us');
      }
      //if not create it
      if (!$contact_page) {
        //create a ninja forms page
        $new_post = array(
            'post_title' => 'Contact Us',
            'post_content' => $ninja_forms_shortcode,
            'post_status' => 'publish',
            'post_type' => 'page',
            'post_category' => array(0)
        );
        $post_id = wp_insert_post($new_post);
      }
      $result .= '<li>Created Contact Us Page</li>';
      wp_update_nav_menu_item($menu_id, 0, array(
            'menu-item-title' =>  __('Contact Us'),
            'menu-item-url' => home_url( '/contact-us/' ),
            'menu-item-status' => 'publish'));
    }   
    unset($current_nav);
    unset($contact_page);
    unset($primary);
    unset($menu_exists);

    $result .= '</ul>';
    return $result;
  }
}