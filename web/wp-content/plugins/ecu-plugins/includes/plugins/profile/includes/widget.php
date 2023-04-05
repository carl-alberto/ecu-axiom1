<?php
/**
 * Profile widget.
 *
 * @package Profile
 */

// Register the widget
add_action( 'widgets_init', function(){
	register_widget( 'WP_Profile_Widget' );
});

	/**
	 * Widget class.
	 *
	 * @since 1.0.0
	 *
	 * @author  atwebdev
	 */
	class WP_Profile_Widget extends WP_Widget {

		/**
		 * The DB handler for the homepage_tools database.  Use the get function
		 * to get a singleton instance.
		 *
		 * @link https://codex.wordpress.org/Class_Reference/wpdb WPDB API
		 *
		 * @var Object wpdb object connected to the tools db.
		 */
		private static $tools_db;

		/**
		 * Constructor. Sets up and creates the widget with appropriate settings.
		 *
		 * @since 1.0.0
		 * @access public
		 */
		public function __construct() {

			add_action('admin_enqueue_scripts', array($this, 'scripts'));

			$widget_ops = array(
					'classname'   => 'wp-profile',
					'description' => __( 'Place profile data into a widgetized area.', 'wp-profile' )
			);

			parent::__construct( 'wp-profile', 'ECU Profile Widget');
		}

		/**
		 * The scripts for this widget
		 *
		 * @since 1.0.0
		 * @access public
		 *
		 */
		public function scripts() {
			wp_enqueue_script('media-upload');
			wp_enqueue_media();
			wp_enqueue_script('ecu_profile_admin', plugin_dir_url( __FILE__)  . '../js/admin.js');
		}

		/**
		 * Outputs the widget within the widgetized area.
		 *
		 * @since 1.0.0
		 * @access public
		 *
		 * @param array $args     The default widget arguments.
		 * @param array $instance The settings for the current widget instance.  See the form function for details.
		 */
		public function widget( $args, $instance ) {
			// Get Data
			$title = apply_filters( 'widget_title', esc_html( $instance['title'] ) );
			$alt_title = apply_filters( 'widget_title', esc_html( $instance['alt_title'] ) );
			$image = apply_filters( 'widget_title', esc_html( $instance['image'] ) );
			$page_url = esc_url(get_page_link($instance['page']));
			$page_link_text = esc_html($instance['page_link_text']);

			$hide_email = esc_html($instance['hide_email']) === 'on' ? true : false;
			$hide_name = esc_html($instance['hide_name']) === 'on' ? true : false;
			$hide_title = esc_html($instance['hide_title']) === 'on' ? true : false;
			$hide_phone = esc_html($instance['hide_phone']) === 'on' ? true : false;
			$hide_mailstop = esc_html($instance['hide_mailstop']) === 'on' ? true : false;
			$hide_bldg = esc_html($instance['hide_bldg']) === 'on' ? true : false;
			$hide_dept = esc_html($instance['hide_dept']) === 'on' ? true : false;

			$str = '';

			// Output Widget
			echo $args['before_widget'];

			$str .= '<div class="ecu-profile">';

			if ( ! empty( $title ) ) {
				echo $args['before_title'] . $title . $args['after_title'];
			}

			// Get the user data from AD
			if( $instance['pirate_id'] !== '' ) {
				$profile = Ecu_Plugins\Profile_Data::get_profile($instance['pirate_id']);
				$user = $profile['ad_account'];
			}	else {
				return;
			}

			// profile pic
			if ($image) {
				$picture_url = wp_get_attachment_image_src($image, 'thumbnail');
				$str .= '<div class="ecu-profile-pic ecu-widget-pic"><img class="ecu-profile-image" src="' . esc_url($picture_url[0]) . '" alt="Picture of ' . esc_html($user->get_user()->getFirstName()) . ' ' . esc_html($user->get_user()->getLastName()) . '"/></div>';
			}
			$str .= '<div class="ecu-profile-main">';
			if(!$hide_name){
				// Always display the first and last name
				$str .= '<div class="ecu-profile-name ecu-widget-profile-name">' . esc_html($user->get_user()->getFirstName()) . ' ' . esc_html($user->get_user()->getLastName()) . '</div>';
			}
			// Display the each field unless the field is turned off, or the value is blank
			$title = '';
			if (!$hide_title) {
				$title = $user->get_user()->getTitle();

				if ($attrs['alt_title'] != '') {
					$title = $attrs['alt_title'];
				}
				if ($title != ''){
					$str .= '<div class="ecu-profile-title  ecu-shortcode-profile-title ">' . esc_html($title) . '</div>';
				}
			}
			// department
			if (!$hide_dept && $profile['dept'] != '') {
				$str .= '<div class="ecu-profile-dept ecu-widget-profile-dept">' . esc_html($profile['dept']) . '</div>';
			}
			$str .= '</div><div class="ecu-profile-info">';
			// email
			if (!$hide_email && $user->get_user()->getEmail() != '') {
				$str .= '<div class="ecu-profile-email ecu-widget-profile-email"><a href="mailto:' . esc_html($user->get_user()->getEmail()) . '">' . esc_html($user->get_user()->getEmail()) . '</a></div>';
			}
			// phone
			if (!$hide_phone && $user->get_user()->getTelephoneNumber() != '') {
				$str .= '<div class="ecu-profile-phone ecu-widget-profile-phone"><a href="tel:' . esc_html(Ecu_Plugins\Profile_Data::strip_phone($user->get_user()->getTelephoneNumber())) . '"  aria-label="' . esc_html(Ecu_Plugins\Profile_Data::aria_phone($user->get_user()->getTelephoneNumber())). '">' . esc_html(Ecu_Plugins\Profile_Data::format_phone($user->get_user()->getTelephoneNumber())) . '</a></div>';
			}
			// mailstop
			if (!$hide_mailstop && $profile['mailstop'] != '') {
				$str .= '<div class="ecu-profile-mailstop ecu-widget-profile-mailstop">Mail Stop: ' . esc_html($profile['mailstop']) . '</div>';
			}
			// building
			if (!$hide_bldg && $user->get_user()->getPhysicalDeliveryOfficeName() != '' && $user->get_user()->getPhysicalDeliveryOfficeName() != 'Student') {
				$str .= '<div class="ecu-profile-office ecu-widget-profile-office">' . esc_html($user->get_user()->getPhysicalDeliveryOfficeName()) . '</div>';
				// If we got the URL for the building, display the map link
				if (isset($profile['mailstop_url'])) {
					$str .= '<div itemprop="workLocation" itemtype="http://schema.org/Place" itemscope=""><a href="' .
							$profile['mailstop_url'] .
							'"><span class="fa fa-map-marker" aria-hidden="true"></span> Map</a></div>';
				}
			}
			$str .= '</div><div class="ecu-profile-extra">';
			// notes
			if ($instance['notes'] != '') {
				$str .= '<div class="ecu-profile-notes ecu-widget-profile-notes">' . esc_html($instance['notes']) . '</div>';
			}
			$str .= "</div>";
			// page link
			if ($page_link_text != '' && $page_url != '') {
				$str .= '<div class="ecu-profile-page-link ecu-shortcode-profile-page-link"><a href="' . $page_url . '">' . $page_link_text . '</a></div>';
			}
			$str .= "</div>";

			echo $str;
			echo $args['after_widget'];
		}

		/**
		 * Processing widget options on save
		 *
		 * @since 1.0.0
		 * @access public
		 *
		 * @param array $new_instance The new options
		 * @param array $old_instance The previous options
		 */
		public function update( $new_instance, $old_instance ) {

			// Set $instance to the old instance in case no new settings have been updated for a particular field.
			$instance = $old_instance;


			// Sanitize user inputs.
			$instance['image'] = sanitize_text_field( $new_instance['image'] );
			$instance['title'] = sanitize_text_field( $new_instance['title'] );
			$instance['pirate_id'] = sanitize_text_field( $new_instance['pirate_id'] );
			$instance['alt_title'] = sanitize_text_field( $new_instance['alt_title'] );
			$instance['hide_email'] = sanitize_text_field( $new_instance['hide_email'] );
			$instance['hide_name'] = sanitize_text_field( $new_instance['hide_name'] );
			$instance['hide_title'] =  sanitize_text_field( $new_instance['hide_title']);
			$instance['hide_phone'] = sanitize_text_field( $new_instance['hide_phone'] );
			$instance['hide_mailstop'] = sanitize_text_field( $new_instance['hide_mailstop'] );
			$instance['hide_bldg'] = sanitize_text_field( $new_instance['hide_bldg'] );
			$instance['hide_dept'] = sanitize_text_field( $new_instance['hide_dept'] );
			$instance['notes'] = sanitize_text_field( $new_instance['notes'] );
			if (sanitize_text_field( $new_instance['notes'] ) != '-1') {
				$instance['page'] = sanitize_text_field( $new_instance['page'] );
			}
			$instance['page_link_text'] = sanitize_text_field( $new_instance['page_link_text'] );

			return $instance;
		}

		/**
		 * Outputs the widget form where the user can specify settings.
		 *
		 * @since 1.0.0
		 * @access public
		 *
		 * @param array $instance  {
		 *      Optional. The settings for the widget instance.
		 *
		 *      @type string  $image       	  The profile image ID
		 *      @type string  $title          The title of the widget
		 *      @type string  $pirate_id      The pirate ID to look up.
		 *      @type string  $hide_title     Should the job title be hidden
		 *      @type string  $hide_email     Should the email address be hidden
		 *      @type string  $hide_phone     Should the phone number be hidden
		 *      @type string  $hide_mailstop  Should the mailstop be hidden
		 *      @type string  $hide_bldg      Should the building be hidden
		 *      @type string  $hide_dept      Should the department be hidden
		 *      @type string  $alt_title      An alternate job title to be used
		 *      @type string  $notes	      Additional notes to be output
		 * }
		 */
		public function form( $instance ) {

			// Set form values
			if( isset( $instance['image'] ) ) {
				$image= $instance['image'];
				$image_tn = wp_get_attachment_thumb_url($image);
			} else {
				$image= '';
				$image_tn = '';
			}
			if( isset( $instance['title'] ) ) {
				$title = $instance['title'];
			} else {
				$title = '';
			}
			if( isset( $instance['pirate_id'] ) ) {
				$pirate_id= $instance['pirate_id'];
			} else {
				$pirate_id= '';
			}
			if( isset( $instance['alt_title'] ) ) {
				$alt_title= $instance['alt_title'];
			} else {
				$alt_title= '';
			}
			if( isset( $instance['hide_email'] ) ) {
				$hide_email= $instance['hide_email'];
			} else {
				$hide_email= '';
			}
			if( isset( $instance['hide_name'] ) ) {
				$hide_name= $instance['hide_name'];
			} else {
				$hide_name = false;
			}
			if( isset( $instance['hide_phone'] ) ) {
				$hide_phone= $instance['hide_phone'];
			} else {
				$hide_phone= false;
			}
			if( isset( $instance['hide_mailstop'] ) ) {
				$hide_mailstop= $instance['hide_mailstop'];
			} else {
				$hide_mailstop= false;
			}
			if( isset( $instance['hide_bldg'] ) ) {
				$hide_bldg= $instance['hide_bldg'];
			} else {
				$hide_bldg= false;
			}
			if( isset( $instance['hide_dept'] ) ) {
				$hide_dept= $instance['hide_dept'];
			} else {
				$hide_dept= false;
			}
			if( isset( $instance['hide_title'] ) ) {
				$hide_title= $instance['hide_title'];
			} else {
				$hide_title= false;
			}
			if( isset( $instance['notes'] ) ) {
				$notes= $instance['notes'];
			} else {
				$notes= false;
			}

			// Start Form
			if( isset( $instance['page'] ) ) {
				$page= $instance['page'];
			} else {
				$page= false;
			}
			if( isset( $instance['page_link_text'] ) ) {
				$page_link_text= $instance['page_link_text'];
			} else {
				$page_link_text= false;
			}


			// Get the list of pages on this site
			$pages = get_pages();

			// Start Form
			echo '<div class="ecu-profile"><p class="description">Shows directory information about a user</p>';
			// Basic Options
			echo '<h4>Basic Options</h4>';

			// title
			echo '<p>';
			echo '<label for="' . $this->get_field_name( 'title' ) . '">Widget Title:</label>';
			echo '<input id="' . $this->get_field_id( 'title' ) . '" class="widefat" type="text" name="' . $this->get_field_name( 'title' ) . '" value="' . esc_attr($title) . '">';
			echo '</p>';

			// image
			echo '<p>';
			echo '<label for="' . $this->get_field_name( 'image' ) . '">Profile Picture:</label><br/>';
			echo '<input id="' . $this->get_field_id( 'image' ) . '" class="widefat ecu_profile_image" type="hidden" name="' . $this->get_field_name( 'image' ) . '" value="' . esc_attr($image) . '">';
			echo '<button class="ecu_profile_upload_button button button_primary">Select Image</button>';
			echo '<div class="ecu_profile_pic_tn">';
			if ($image != '') {
				echo '<image src="' . esc_attr($image_tn) . '">';
				echo '<div class="ecu_profile_image_remove"><a href="" id="remove_image_button">remove</a></div>';
			}
			echo '</div>';
			echo '</p>';

			// pirate id
			echo '<p>';
			echo '<label for="' . $this->get_field_name( 'pirate_id' ) . '">Pirate ID:</label>';
			echo '<input id="' . $this->get_field_id( 'pirate_id' ) . '" class="widefat" type="text" name="' . $this->get_field_name( 'pirate_id' ) . '" value="' . esc_attr($pirate_id) . '">';
			echo '</p>';

			// alternate job title
			echo '<p>';
			echo '<label for="' . $this->get_field_name( 'alt_title' ) . '">Alternate Job Title (overrides default one):</label>';
			echo '<input id="' . $this->get_field_id( 'alt_title' ) . '" class="widefat" type="text" name="' . $this->get_field_name( 'alt_title' ) . '" value="' . esc_attr($alt_title) . '">';
			echo '</p>';

			// hide the name
			echo '<p>';
			echo '<input id="' . $this->get_field_id( 'hide_name' ) . '" class="widefat" type="checkbox" name="' . $this->get_field_name( 'hide_name' ) . '"';
			if ($hide_name) {
				echo ' checked ';
			}
			echo '>';
			echo '<label for="' . $this->get_field_name( 'hide_name' ) . '">Do not show name</label>';
			echo '</p>';

			// hide the phone number
			echo '<p>';
			echo '<input id="' . $this->get_field_id( 'hide_phone' ) . '" class="widefat" type="checkbox" name="' . $this->get_field_name( 'hide_phone' ) . '"';
			if ($hide_phone) {
				echo ' checked ';
			}
			echo '>';
			echo '<label for="' . $this->get_field_name( 'hide_phone' ) . '">Do not show phone number</label>';
			echo '</p>';

			// hide the job title
			echo '<p>';
			echo '<input id="' . $this->get_field_id( 'hide_title' ) . '" class="widefat" type="checkbox" name="' . $this->get_field_name( 'hide_title' ) . '"';
			if ($hide_title) {
				echo ' checked ';
			}
			echo '>';
			echo '<label for="' . $this->get_field_name( 'hide_title' ) . '">Do not show job title</label>';
			echo '</p>';

			// hide the email address
			echo '<p>';
			echo '<input id="' . $this->get_field_id( 'hide_email' ) . '" class="widefat" type="checkbox" name="' . $this->get_field_name( 'hide_email' ) . '"';
			if ($hide_email) {
				echo ' checked ';
			}
			echo '>';
			echo '<label for="' . $this->get_field_name( 'hide_email' ) . '">Do not show email address</label>';
			echo '</p>';

			// hide the mailstop
			echo '<p>';
			echo '<input id="' . $this->get_field_id( 'hide_mailstop' ) . '" class="widefat" type="checkbox" name="' . $this->get_field_name( 'hide_mailstop' ) . '"';
			if ($hide_mailstop) {
				echo ' checked ';
			}
			echo '>';
			echo '<label for="' . $this->get_field_name( 'hide_mailstop' ) . '">Do not show mail stop</label>';
			echo '</p>';

			// hide the building
			echo '<p>';
			echo '<input id="' . $this->get_field_id( 'hide_bldg' ) . '" class="widefat" type="checkbox" name="' . $this->get_field_name( 'hide_bldg' ) . '"';
			if ($hide_bldg) {
				echo ' checked ';
			}
			echo '>';
			echo '<label for="' . $this->get_field_name( 'hide_bldg' ) . '">Do not show building information</label>';
			echo '</p>';

			// hide the department
			echo '<p>';
			echo '<input id="' . $this->get_field_id( 'hide_dept' ) . '" class="widefat" type="checkbox" name="' . $this->get_field_name( 'hide_dept' ) . '"';
			if ($hide_dept) {
				echo ' checked ';
			}
			echo '>';
			echo '<label for="' . $this->get_field_name( 'hide_dept' ) . '">Do not show department</label>';
			echo '</p>';

			// hide the notes
			echo '<p>';
			echo '<label for="' . $this->get_field_name( 'notes' ) . '">Notes</label>';
			echo '<textarea id="' . $this->get_field_id( 'notes' ) . '" class="widefat" name="' . $this->get_field_name( 'notes' ) . '">' . esc_html($notes) . '</textarea>';
			echo '</p>';

			// display the page link
			echo '<p>';
			echo '<label for="' . $this->get_field_name( 'page' ) . '">Select page for this item to link to</label>';
			echo '<div class="widefat ecu-profile-page-select-div" ><select id="' . $this->get_field_id( 'page' ) . '" class="widefat ecu-profile-page-select" name="' . $this->get_field_name( 'page' ) . '">';
			// Prepare the options for the select box
			echo '<option value="-1">Do not link to a page</option>';
			foreach ($pages as $pageindex) {
				echo '<option value="' . $pageindex->ID . '" ';
				if ($page == $pageindex->ID) {
					echo ' selected';
				}
				echo '>' . $pageindex->post_title . '</option>';
			}
			echo '</select>';
			echo '</div>';
			echo '</p>';
			echo '<p>';
			echo '<label for="' . $this->get_field_name( 'page_link_text' ) . '">Page link text</label>';
			echo '<input id="' . $this->get_field_id( 'page_link_text' ) . '" class="widefat" type="text" name="' . $this->get_field_name( 'page_link_text' ) . '" value="' . esc_attr($page_link_text) . '">';
			echo '</p></div>';
		}
	}