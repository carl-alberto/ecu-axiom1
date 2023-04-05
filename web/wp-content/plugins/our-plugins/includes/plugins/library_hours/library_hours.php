<?php
	namespace OUR_PLUGINS;

	/**
	 * Fancy Quote is a shortcode that adds a styled quote and citation
	 */
	class Library_Hours extends Abstract_Ecu_Shortcode {
		public function initialize(){
			parent::initialize();
		}
        public function enqueue_scripts() {
			// wp_register_style( 'ecu-shortcode-dashboard', plugins_url('/our-plugins/includes/plugins/dashboard/css/style.css') );
		}
	    /**
	     * Returns the shortcode
	     *
	     * @return string $shortcode The shortcode.
	     */
		public function get_shortcode() {
			return "library_hours";
		}

		/**
		 * Shortcode Function.
		 *
		 * @link https://codex.wordpress.org/Shortcode_API Shortcode UI
		 *
		 * @param array $atts  {
		 *      Optional. The settings for the shortcode instance.
		 *
		 *      @type string  $fancyquote-body      The qoute.
		 *      @type string  $fancyquote-citation  The source of the qoute.
		 * }
		 * @param string $content 		The enclosed content (if the shortcode is used in its enclosing form)
		 * @param string $shortcode_tag The shortcode tag, useful for shared callback functions.
		 * @return string The output for the shortcode.
		 */
		public function callback($attrs, $content = '', $shortcode_tag){
            $attrs = shortcode_atts(array(
				'id' => '',
			),array_map('rawurldecode',$attrs), $shortcode_tag);
            $output;
			if($hours = get_field('ui_library_hours', $attrs['id'])) {
				$base = 'http://lib.ecu.edu/api/hours';

				$title = $hours['title'];
				$type = $hours['type'];
				$day = $hours['day'];
				$endpoint = FALSE;

				$libs = array('joyner', 'laupus', 'music');
				$output = '';
				if(in_array($type, $libs)){
					if($type != 'music'){
						$dept = $type == 'joyner' ? $hours['joynerdpt'] : $hours['laupusdpt'];
						if($dept != 'lib'){
							if($day == 'all'){
								$url = implode("/", array($base, $type, $dept));
							} else {
								$url = implode("/", array($base, $type, $dept, $day));
								$endpoint = TRUE;
							}
						} else {
							if($day == 'all'){
								$url = implode("/", array($base, $type));
							} else {
								$url = implode("/", array($base, $type, $day));
								$endpoint = TRUE;
							}
						}
					} else {
						if($day == 'all'){
							$url = implode("/", array($base, $type));
						} else {
							$url = implode("/", array($base, $type, $day));
							$endpoint = TRUE;
						}
					}
				} else {
					$url = implode("/", array($base, $type));
				}

				if(WP_HTTP_BLOCK_EXTERNAL && (strpos(WP_ACCESSIBLE_HOSTS, 'lib.ecu.edu') !== false)) {
					return "<p>API functionality has been temporarily disabled for library hours.</p>";
				}

				$hash = 'libhours_' . hash("crc32b", $url);
				$api = get_transient($hash);

        		if (false === $api ){
					$api = json_decode(file_get_contents($url));
        	    	set_transient($hash, $api, HOUR_IN_SECONDS);
        		}

				if($api != null){
					$output = "<div class='ui_library_hours'>";
					if($title){
						$output .= "<h2>{$title}</h2>";
					}
					if($endpoint){
						$output .= "<p>{$api->Hours}</p>";
					} else {
						if(count($api) == 1 ){
							$output .= "<h3>Regular Hours</h3>";
							$output .= "<table class='table-striped table-sm'>";
							if($api->Sunday){
								$output .= "<tr><td>Sunday</td><td>{$api->Sunday}</td></tr>";
							}
							if($api->MondayThursday){
								$output .= "<tr><td>Monday - Thursday</td><td>{$api->MondayThursday}</td></tr>";
							}
							if($api->Friday){
								$output .= "<tr><td>Friday</td><td>{$api->Friday}</td></tr>";
							}
							if($api->Saturday){
								$output .= "<tr><td>Saturday</td><td>{$api->Saturday}</td></tr>";
							}
							$output .= "</table>";
							if(is_array($api->Exceptions)){
								$output .= "<h3>Upcoming Exceptions</h3>
								<table class='table-striped table-sm'>";
								foreach($api->Exceptions as $ex){
									$desc = $ex->Description ? "(".$ex->Description.")" : "";
									if($ex->StartDate == $ex->EndDate){
										$output .= "<tr><td>".date('F j', strtotime($ex->StartDate))." {$desc}</td><td>{$ex->Hours}</td></tr>";
									} else {
										$output .= "<tr><td>".date('F j', strtotime($ex->StartDate)). " - " .
										date('F j', strtotime($ex->EndDate)) . " {$desc}</td><td>{$ex->Hours}</td></tr>";
									}
								}
								$output .= "</table>";
							}
							$output .= "</table>";
						} else {
							if($type == 'all'){
								foreach($api as $lib){
									$output .= "<h3>{$lib->Name} Regular Hours</h3>";
									$output .= "<table class='table-striped table-sm'>";
									if($lib->Sunday){
										$output .= "<tr><td>Sunday</td><td>{$lib->Sunday}</td></tr>";
									}
									if($lib->MondayThursday){
										$output .= "<tr><td>Monday - Thursday</td><td>{$lib->MondayThursday}</td></tr>";
									}
									if($lib->Friday){
										$output .= "<tr><td>Friday</td><td>{$lib->Friday}</td></tr>";
									}
									if($lib->Saturday){
										$output .= "<tr><td>Saturday</td><td>{$lib->Saturday}</td></tr>";
									}
									$output .= "</table>";
									if(is_array($lib->Exceptions)){
										$output .= "<h3>Upcoming Exceptions</h3>
										<table class='table-striped table-sm'>";
										foreach($lib->Exceptions as $ex){
											$desc = $ex->Description ? "(".$ex->Description.")" : "";
											if($ex->StartDate == $ex->EndDate){
												$output .= "<tr><td>".date('F j', strtotime($ex->StartDate))." {$desc}</td><td>{$ex->Hours}</td></tr>";
											} else {
												$output .= "<tr><td>".date('F j', strtotime($ex->StartDate)). " - " .
												date('F j', strtotime($ex->EndDate)) . " {$desc}</td><td>{$ex->Hours}</td></tr>";
											}
										}
										$output .= "</table>";
										}
									$output .= "</table>";
								}
							} else {
								foreach($api as $lib){
									$output .= "<h3>{$lib->Name}</h3>
									<p>{$lib->Hours}</p>";
								}
							}
						}
					}
					$output .= "</div>";

				} else {
					if(is_admin()){
						$output = 'API returned no data';
					} else {
						$output = 'No hours available for this day.';
					}

				}
            } else {
                $output = 'Invalid library hours ID.';
            };
            return $output;
		}
		/**
		 * Registers the UI of the shortcode with shortcake
		 *
		 * @link https://github.com/wp-shortcake/shortcake/wiki/Registering-Shortcode-UI Registering Shortcode UI
	     */
		function register_with_shortcake(){
			$url = get_home_url() . '/wp-admin/post-new.php?post_type=ui-elements';
            shortcode_ui_register_for_shortcode(
                $this->get_shortcode(),
                array(
                    'label'         => 'Library Hours',
                    'listItemImage' => $this->get_font_awesome_html('fa-hourglass'),
                    'attrs'         => array(
                        array(
                            'label'  => esc_html__( 'Library Hours', $this->get_shortcode() ),
                            'attr'   => 'header',
                            'type'   => 'ecu-shortcode-information',
                            'description' => "Displays hours for a particular library using the hours API<br /><br /><a href='{$url}&ui-type=library_hours ' class='btn btn-success' style='color:#FFFFFF;' target='_blank'>New Hours</a>"
                        ),
                        array(
                            'label'    => esc_html__( 'Select Hours', 'shortcode-ui-example', 'shortcode-ui' ),
                            'attr'     => 'id',
                            'type'     => 'post_select',
                            'query'    => array(
                                'post_type' => 'ui-elements',
                                'meta_query' => array(
                                    array(
                                        'key' => 'element_type',
                                        'value' => 'library_hours',
                                    )
                                )
                            ),
                            'multiple' => false,
                        )
                    )
                )
            );
		}
	}

	new Library_Hours;
