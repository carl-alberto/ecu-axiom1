<?php

namespace Intranet;

defined( 'ABSPATH' ) OR exit;

class Intranet
{
    use Settings;

    /**
     * User role with the same capabilities as a Subscriber
     */
    const USER_ROLE = 'wp_intranet_user';

    /**
     * Admin role with capability to edit the intranet settings.
     */
    const ADMIN_ROLE = 'wp_intranet_admin';

    /**
     * Constructor.   Sets the intranet settings from the options if any
     * have been set yet.  Otherwise uses the property defaults.
     */
    public function __construct() {

        $this->init_settings();

    }

    /**
     * Adds roles.  Meant to be used with the activation hook.
     */
    public function plugin_activation() {

        $capabilities = array(
            'read' => true,
            'read_envira_gallery' => true
        );
       add_role( self::USER_ROLE, 'ECU Intranet User', $capabilities);


        $capabilities = array(
            'wp_manage_intranet' => true,
        );
        add_role( self::ADMIN_ROLE, 'ECU Intranet Admin', $capabilities);

    }

    /**
     * Removes role and options.  Meant to be used with the deactivation hook.
     */
    public function plugin_deactivation() {
        delete_option($this->option_name);
        remove_role(self::USER_ROLE);
        remove_role(self::ADMIN_ROLE);
    }

    /**
     * Change the robots.txt discourage crawlers.
     *
     * @see https://developer.wordpress.org/reference/hooks/robots_txt/
     * @see https://kinsta.com/blog/wordpress-robots-txt/
     */
    public function robots( $output, $public ) {
        if ( $this->get_enabled() ) {
             return "User-agent: *\nDisallow: /\n";
        }

        return $output;
    }

    /**
     * Redirects the user to the login page if the user is not logged in.
     */
    public function force_login() {
        global $wp;

        // Only redirect if not an ajax request, not a cron job, or being executed from cli.
        // Do not need to check if on login as template_redirect action isn't executed on the login page.
        if( wp_doing_ajax() || wp_doing_cron() || ( defined( 'WP_CLI' ) && WP_CLI ) || ("robots.txt" === $wp->request) ) {
            return;
        }

        auth_redirect(); // Already takes care of returning the user to the url referrer after forced login.
    }

    /**
     * Adds a message on the login page for intranet sites.
     *
     * @return string   The message to always display on intranet login.
     */
    public function login_message() {
        return "
        <div class='message'>
            Login Required
        </div>
        ";
    }

    /**
     * Adds the user to the blog with the ecu intranet user role.
     *
     * @param WP_User $user A valid WP User.
     */
    public function add_user($user) {

        $blog_id = get_current_blog_id();
        // Makes sure that the user is on the blog with the intranet user role.
        if(!is_user_member_of_blog($user->id, $blog_id)) {
            add_user_to_blog( $blog_id, $user->id, self::USER_ROLE );
        }

        if(!in_array(self::USER_ROLE, $user->roles)) {
            // add the ecu intranet user role
           $user->add_role( self::USER_ROLE );
        }
    }

    /**
     * Removes the user from the intranet.   If the user's only role is the ecu intranet user
     * role then they will be removed from the site as well.
     *
     * @param WP_User $user A valid WP User.
     */
    public function remove_user($user) {

         if(in_array(self::USER_ROLE, $user->roles)) {
            // User has other roles then just intranet remove role but leave user on the blog
            $user->remove_role( self::USER_ROLE );
        }

        //  remove user where the number of roles is 0. This will only ever be a intranet user.
        if ( count($user->roles) == 0 ) {
            $blog_id = get_current_blog_id();
            remove_user_from_blog($user->id, $blog_id);
        }
    }

    /**
     * Will remove all intranet users.
     *
     * @see https://codex.wordpress.org/Function_Reference/get_users
     */
    public function remove_users() {
        $users = get_users();
        foreach($users as $user) {
            $this->remove_user($user);
        }
    }

    /**
     * Checks if the AD user is authorized to access the intranet based on the type of intranet.
     *
     * @param object $ad_user A valid ecu ad user object.
     * @return bool true if the user is authorized for this intranet, false otherwise
     */
    public function authorize($ad_user) {

        if (current_user_can('wp_manage_intranet')) {
            return true;
        }

        switch ($this->type) {

            // employees only
            case 5:
                return $ad_user->is_employee();
                break;

            // students only
            case 4:
                return $ad_user->is_student();
                break;

            // group membership
            case 3:
                $accounts = array_map('trim',explode(',',$this->ad_accounts));
                $groups = array_map('trim',explode(',',$this->ad_groups));
                if($ad_user->in_group($groups) || in_array($ad_user->get_user()->getAccountName(), $accounts)) {
                    return true;
                } else {
                    return false;
                }
                break;

            // Blog Members Only
            case 2:
                $wp_user = get_user_by('login', $ad_user->get_user()->getAccountName());
                if($wp_user) {
                    return is_user_member_of_blog($wp_user->id);
                } else {
                    return false;
                }
                break;

            // any valid pirate_id
            case 1:
                return true;
                break;

            // deny login by default
            default:
                return false;
                break;

        }
    }

   /**
    * Checks if the intranet is turned on.
    *
    * @return bool true if intranet is enabled, false otherwise.
    */
   public function is_enabled() {
        return $this->enabled;
    }

    /**
     * Validates a local users ( currently on atwebdev ) and users in AD and makes sure they are assigned to the given blog.
     *
     * @param  object $user     	A WP User object or NULL normally, but ignored by this function.
     * @param  string $username 	The username that was in the login field
     * @param  string $password 	The password that was in the passwords field
     * @return WP User | WP Error	Returns either a WP User object if the user validates or a WP Error object if there was an error validating
     */
    public function authenticate( $user, $username = NULL, $password = NULL ) {

        // 5.2 stopped check if the username was null in the wp_signon function in the user.php.  So have to do it here now.
        if(empty($username)) {
            return;
        }

        // Need to do this to insure the default role / public blog settings are correct for intranets.  This could be changed
        // via init scripts or other action.
        update_option('default_role', Intranet::USER_ROLE);
        update_option('blog_public', '0');   // adds no index and blocks crawlers for intranet enabled sites

        $ad_user = new \Ldap\Ad_User($username);

        if($ad_user->is_valid() && $ad_user->authenticate($password) && $this->authorize($ad_user)) {
            if(!$ad_user->get_wp_id()) {
                // Have to create the user if they don't exist for intranets they are authorized for.
                // WordPress will add them to the blog with the default role upon creation.
                $user_id = $ad_user->create_wp_user();
                $user = $ad_user->get_wp_user();
            } else {
                $ad_user->sync_wp_user();
                $user = $ad_user->get_wp_user(); // returns false on failure
                // Makes sure that the user is on the blog with the intranet user role.
                $this->add_user($user);
            }
        }


        return $user;
    }
}