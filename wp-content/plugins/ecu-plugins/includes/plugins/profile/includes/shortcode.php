<?php
	namespace Ecu_Plugins;

	/**
	 * Shortcode class for the link farm.
	 */
	class Directory_Information extends Abstract_Ecu_Shortcode {


		/**
		 * Returns the shortcode
		 *
		 * @return string $shortcode The shortcode.
		 */
		public function get_shortcode() {
			return "ecu_profile";
		}
		/**
		 * Initialize
		 */
		public function initialize(){

			add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));

			if ( is_admin() ) {
				add_editor_style( plugins_url('/ecu-plugins/includes/plugins/profile/css/style.css') );
			}

			parent::initialize();
		}

		/**
		 * Enqueueues the necessary CSS and JS
		 */
		public function enqueue_scripts() {
			wp_register_style( 'ecu-shortcode-profile', plugins_url('/ecu-plugins/includes/plugins/profile/css/style.css') );
		}

		/**
		 * Shortcode Function.
		 *
		 * @link https://codex.wordpress.org/Shortcode_API Shortcode UI
		 *
		 * @param array $atts  {
		 *      Optional. The settings for the shortcode instance.
		 *
		 *      @type string  $pirate_id      The pirate ID to look up.
		 *      @type string  $image       	  The profile image ID
		 *      @type string  $hide_title     Should the job title be hidden
		 *      @type string  $hide_email     Should the email address be hidden
		 *      @type string  $hide_phone     Should the phone number be hidden
		 *      @type string  $hide_mailstop  Should the mailstop be hidden
		 *      @type string  $hide_bldg      Should the building be hidden
		 *      @type string  $hide_dept      Should the department be hidden
		 *      @type string  $alt_title      An alternate job title to be used
		 *      @type string  $notes	      Additional notes to be output
		 * }
		 * @param string $content 		The enclosed content (if the shortcode is used in its enclosing form)
		 * @param string $shortcode_tag The shortcode tag, useful for shared callback functions.
		 * @return string The output for the shortcode.
		 */
		public function callback($attrs, $content = '', $shortcode_tag){
			wp_enqueue_style('ecu-shortcode-profile');
			//Get Values and Set any unset values.
			$str = '';
			$attrs = shortcode_atts(array( //creates varaibales from your attrs
					'pirate_id' => '',
					'alt_title'=>'',
					'pic' => '',
					'hide_title' => '',
					'hide_email' => '',
					'hide_phone' => '',
					'hide_mailstop' => '',
					'hide_bldg' => '',
					'hide_dept' => '',
					'notes' => '',
					'page' => '',
					'page_link_text' => 'Profile',

			), $attrs, $shortcode_tag);

			if( $attrs['pirate_id'] !== '' ) {
					$profile = Profile_Data::get_profile($attrs['pirate_id']);
					$user = $profile['ad_account'];
					if ( !$user || !$user->is_valid()) {
						$str = "User could not be found";
					}
					else {
						$str .= '<div class="ecu-profile">';
						if ($attrs['pic'] != '') {
							// $picture_url = wp_get_attachment_url($attrs['pic']);
							$picture_url = wp_get_attachment_image_src($attrs['pic'], 'medium');
							$str .= '<div class="ecu-profile-pic ecu-shortcode-profile-pic"><img class="ecu-profile-image" src="' . $picture_url[0] . '" alt="Picture of ' . esc_html($user->get_user()->getFirstName()) . ' ' . esc_html($user->get_user()->getLastName()) . '"/></div>';
						}
						// Always display the first and last name
						//
						//
						$str .= '<div class="ecu-profile-main">';
						$str .= '<div class="ecu-profile-name ecu-shortcode-profile-name">' . esc_html($user->get_user()->getFirstName()) . ' ' . esc_html($user->get_user()->getLastName()) . '</div>';
						// Display the each field unless the field is turned off, or the value is blank
						$title = '';
						if ($attrs['hide_title'] != 'true') {
							$title = $user->get_user()->getTitle();

							if ($attrs['alt_title'] != '') {
								$title = $attrs['alt_title'];
							}
							if ($title != ''){
								$str .= '<div class="ecu-profile-title  ecu-shortcode-profile-title ">' . esc_html($title) . '</div>';
							}
						}
						// department
						if ($attrs['hide_dept'] != 'true' && $profile['dept'] != '') {
							$str .= '<div class="ecu-profile-dept ecu-shortcode-profile-dept">' . esc_html($profile['dept']) . '</div>';
						}
						$str .= '</div>';

						$info = '';

						// email
						if ($attrs['hide_email'] != 'true' && $user->get_user()->getEmail() != '') {
							$info .= '<div class="ecu-profile-email ecu-shortcode-profile-email"><a href="mailto:' . esc_html($user->get_user()->getEmail()) . '">' . esc_html($user->get_user()->getEmail()) . '</a></div>';
						}
						// phone

						if ($attrs['hide_phone'] != 'true' && $user->get_user()->getTelephoneNumber() != '') {
							$info .= '<div class="ecu-profile-phone ecu-shortcode-profile-phone"><a href="tel:' . esc_html(Profile_Data::strip_phone($user->get_user()->getTelephoneNumber())) . '" aria-label="' . esc_html(Profile_Data::aria_phone($user->get_user()->getTelephoneNumber())) . '">' . esc_html(Profile_Data::format_phone($user->get_user()->getTelephoneNumber())) . '</a></div>';
						}
						// mailstop
						if ($attrs['hide_mailstop'] != 'true' && $profile['mailstop'] != '') {
							$info .= '<div class="ecu-profile-mailstop ecu-shortcode-profile-mailstop">Mail Stop: ' . esc_html($profile['mailstop']) . '</div>';
						}
						// building
						if ($attrs['hide_bldg'] != 'true' && $user->get_user()->getPhysicalDeliveryOfficeName() != '' && $user->get_user()->getPhysicalDeliveryOfficeName() != 'Student') {
							$info .= '<div class="ecu-profile-office ecu-shortcode-profile-office">' . esc_html($user->get_user()->getPhysicalDeliveryOfficeName()) . '</div>';
							// If we got the URL for the building, display the map link
							if (isset($profile['mailstop_url'])) {

								$info .= '<div itemprop="workLocation" itemtype="http://schema.org/Place" itemscope=""><a href="' .
									$profile['mailstop_url'] .
									'"> <span class="fa fa-map-marker" aria-hidden="true"></span> Map</a></div>';
							}
						}
						if(!empty($info)) {
							$str .= '<div class="ecu-profile-info">' . $info . '</div>';
						}

						// notes

						if ($attrs['notes'] != '') {
							$str .= '<div class="ecu-profile-extra">';
								$str .= '<div class="ecu-profile-notes ecu-shortcode-profile-notes">' . esc_html(esc_html($attrs['notes'])) . '</div>';
							$str .= "</div>";
						}

						$str .= "</div>";
					}
			}
			return do_shortcode($str);

		}

		/**
		 * Registers the UI of the shortcode with shortcake
		 *
		 * @link https://github.com/wp-shortcake/shortcake/wiki/Registering-Shortcode-UI Registering Shortcode UI
		 */
		function register_with_shortcake(){
			if (function_exists("shortcode_ui_register_for_shortcode")){

				// Get the list of pages on this site
				$pages = get_pages();
				$page_array[] = array('value'=>'-1', 'label'=>'Do not link to a page');
				foreach ($pages as $pageindex) {
					if ($pageindex->post_title != '') {
						$page_array[] = array('value' => $pageindex->ID, 'label' => $pageindex->post_title);
					}
				}

				shortcode_ui_register_for_shortcode(
						$this->get_shortcode(),
						array(
								'label'         => 'Profile',
								'description' => 'Shows directory information about a user',
								'listItemImage' => $this->get_font_awesome_html('fa-user'),
								'attrs'         => array(
										array(
												'label'  => esc_html__( 'Profile', $this->get_shortcode() ),
												'attr'   => 'header',
												'type'   => 'ecu-shortcode-information',
												'description' => 'Shows directory information about a user'
										),
										array(
												'label'  => esc_html__( 'Pirate ID', $this->get_shortcode() ),
												'attr'   => 'pirate_id',
												'type'   => 'text',
												'encode' => true,
										),
										array(
												'label'  => esc_html__( 'Alternate Job Title (overrides default one)', $this->get_shortcode() ),
												'attr'   => 'alt_title',
												'type'   => 'text',
												'encode' => true,
										),
										array(
												'label'       => esc_html__( 'Profile Picture', $this->get_shortcode() ),
												'attr'        => 'pic',
												'type'        => 'attachment',
												'libraryType' => array('image'),
												'addButton'   => esc_html__( 'Select Image', $this->get_shortcode() ),
												'frameTitle'  => esc_html__( 'Select Image', $this->get_shortcode() ),
										),
										array(
												'label'  => esc_html__( 'Do not show job title', $this->get_shortcode() ),
												'attr'   => 'hide_title',
												'type'   => 'checkbox',
												'encode' => false,
										),
										array(
												'label'  => esc_html__( 'Do not show email address', $this->get_shortcode() ),
												'attr'   => 'hide_email',
												'type'   => 'checkbox',
												'encode' => false,
										),
										array(
												'label'  => esc_html__( 'Do not show phone number', $this->get_shortcode() ),
												'attr'   => 'hide_phone',
												'type'   => 'checkbox',
												'encode' => false,
										),
										array(
												'label'  => esc_html__( 'Do not show mail stop', $this->get_shortcode() ),
												'attr'   => 'hide_mailstop',
												'type'   => 'checkbox',
												'encode' => false,
										),
										array(
												'label'  => esc_html__( 'Do not Show Building Information', $this->get_shortcode() ),
												'attr'   => 'hide_bldg',
												'type'   => 'checkbox',
												'encode' => false,
										),
										array(
												'label'  => esc_html__( 'Do not Show Department', $this->get_shortcode() ),
												'attr'   => 'hide_dept',
												'type'   => 'checkbox',
												'encode' => false,
										),
										array(
												'label'  => esc_html__( 'Notes', $this->get_shortcode() ),
												'attr'   => 'notes',
												'type'   => 'textarea',
												'encode' => false,
										),
										array(

												'label'    => esc_html__( 'Select page for link', $this->get_shortcode() ),
												'attr'     => 'page',
												'description' => esc_html__( 'You can select one page.  If you cannot find the page you created be sure that it is not a post.' ),
												'type'     => 'post_select',
												'query'    => array('post_type' => array('page')),

												'multiple' => false,
										),
										array(
												'label'  => esc_html__( 'Page link text', $this->get_shortcode() ),
												'attr'   => 'page_link_text',

												'description' => esc_html__( 'Defaults to Profile if a page is selected but no text provided.' ),

												'type'   => 'text',
												'encode' => true,
										),
								)
						)
				);
			}
		}
	}
	new Directory_Information;
