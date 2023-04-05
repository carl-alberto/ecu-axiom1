<?php
	namespace OUR_PLUGINS;


	/**
	 * Shortcode class for the link farm.
	 */
	class Ecu_Video extends Abstract_Ecu_Shortcode {

		/**
		 * Returns the shortcode
		 *
		 * @return string $shortcode The shortcode.
		 */
		public function get_shortcode() {
			return "ecu_video";
		}

		/**
		 * Shortcode Function.
		 *
		 * @link https://codex.wordpress.org/Shortcode_API Shortcode UI
		 *
		 * @param array $atts  {
		 *      Optional. The settings for the shortcode instance.
		 *
		 *      @type string  $pirateid      The pirate ID to look up.
		 * }
		 * @param string $content 		The enclosed content (if the shortcode is used in its enclosing form)
		 * @param string $shortcode_tag The shortcode tag, useful for shared callback functions.
		 * @return string The output for the shortcode.
		 */
		public function callback($attrs, $content = '', $shortcode_tag){
			//Get Values and Set any unset values.
			$str = '';
			$attrs = shortcode_atts(array( //creates varaibales from your attrs
					'title' => '',
					'title_x' => '',
					'title_y' => '',
					'title_background_color' => '',
					'src' => '',
					'url' => '',
					'poster' => '',
					'loop' => '',
					'autoplay' => '',
					'preload' => '',
					'height' => '',
					'width' => '',
			),array_map('rawurldecode',$attrs), $shortcode_tag);
			if( isset($attrs['src']) || isset($attrs['url'])) {

				if ($attrs['title']) {
					$str .= '<div class="ecu-video-wrap">';

						if ($attrs['title_y'] == 'top') {
							$str .= '<div class="title-wrap '.$attrs['title_background_color'].' '.$attrs['title_y'].' ">';
								$str .= '<div class="ecu-video-title '.$attrs['title_x'].' '. $attrs['title_y'] .' '.$attrs['title_background_color'].'">';
								$str .= $attrs['title'];
								$str .= '</div>';
							$str .= '</div>';
						}

						$shortcode = $this->ecu_create_shortcode($attrs);
						$str .= do_shortcode($shortcode);

						if ($attrs['title_y'] == 'bottom') {
							$str .= '<div class="title-wrap '.$attrs['title_background_color'].' '.$attrs['title_y'].' ">';
								$str .= '<div class="ecu-video-title '.$attrs['title_x'].' '.$attrs['title_y'].' '.$attrs['title_background_color'].'">';
								$str .= $attrs['title'];
								$str .= '</div>';
							$str .= '</div>';
						}
					$str .='</div>';
				} else {
					$shortcode = $this->ecu_create_shortcode($attrs);
					$str .= do_shortcode($shortcode);
				}
			}

			return $str;
		}

		public function ecu_create_shortcode($attrs) {
				// Get the URL for the video
				//if its not pulled from the media library, then pull the URL
				$url = !empty($attrs['src']) ? wp_get_attachment_url($attrs['src']) : $attrs['url'];

				$str = '[video src="' . $url . '"';

				if ($attrs['poster'] != '') {
					$poster = wp_get_attachment_image_src($attrs['poster'],"full");
					$str .= ' poster="' . $poster[0] . '"';
				}
				if ($attrs['loop'] != '') {
					$str .= ' loop="' . $attrs['loop'] . '"';
				}
				if ($attrs['autoplay'] != '') {
					$str .= ' autoplay="' . $attrs['autoplay'] . '"';
				}
				if ($attrs['preload'] != '') {
					$str .= ' preload="' . $attrs['preload'] . '"';
				}

				// If height/width was manually entered, use that. Otherwise, use the values from the video itself
				if ($attrs['height'] != '') {
					$str .= ' height="' . $attrs['height'] . '"';
				}
				if ($attrs['height'] != '') {
					$str .= ' width="' . $attrs['width'] . '"';
				}

				$str  .= ']';

				return $str;
		}


		function register_with_shortcake(){
			if (function_exists("shortcode_ui_register_for_shortcode")){

				$args = array(
						'post_type' => 'ui-elements',
						'meta_query' => array(
								array(
										'key' => 'element_type',
										'value' => 'video',
								)
						)
				);
				$query = query_posts($args);
				wp_reset_query();

				if($query){
					$options = array();
					foreach($query as $post){
						$options[] = array(
								'value' => $post->ID,
								'label' => $post->post_title
						);
					};
				} else {
					$options = array(
							array (
									'value' => '',
									'label' => 'Please Create Video UI Element'
							)
					);
				};

				if (function_exists("shortcode_ui_register_for_shortcode")){

						shortcode_ui_register_for_shortcode(
						$this->get_shortcode(),
								 array(
									'label'         => 'Video',
									'listItemImage' => $this->get_font_awesome_html('fa-video-camera'),
									'attrs'         => array(
											array(
													'label'  => esc_html__( 'Video', $this->get_shortcode()),
													'attr'   => 'header',
													'type'   => 'ecu-shortcode-information',
													'description' => 'Embed a video in your page or post'
											),
											array(
													'label'  => esc_html__( 'Title', $this->get_shortcode() ),
													'attr'   => 'title',
													'description' => 'If title is specified it will show above or below the video.',
													'type'   => 'text',
													'encode' => true,
											),
											array(
													'label'  => esc_html__( 'Title X', $this->get_shortcode() ),
													'attr'   => 'title_x',
													'description' => 'Display title left or right.',
													'type'      => 'select',
													'value'		=> 'label-left',
													'options'   => array(
															array(
																	'value' => 'label-left',
																	'label' => 'Left',
															),
															array(
																	'value' => 'label-right',
																	'label' => 'Right',
															),
													),
											),
											array(
												'label'  => esc_html__( 'Title Y', $this->get_shortcode() ),
												'attr'   => 'title_y',
												'description' => 'Display title top or bottom.',
												'type'      => 'select',
												'value'		=> 'top',
												'options'   => array(
													array(
															'value' => 'top',
															'label' => 'Top',
													),
													array(
															'value' => 'bottom',
															'label' => 'Bottom',
														),
												),
											),
											array(
												'label'  => esc_html__( 'Title Background Color', $this->get_shortcode() ),
												'attr'   => 'title_background_color',
												'description' => 'Background color for title.',
												'type'      => 'select',
												'value'		=> 'ecu-purple',
												'options'   => array(
													array(
															'value' => 'ecu-purple',
															'label' => 'ECU Purple',
													),
													array(
															'value' => 'ecu-dark-purple',
															'label' => 'ECU Dark Purple',
													),
													array(
															'value' => 'ecu-gold',
															'label' => 'ECU Gold',
													),
													array(
															'value' => 'ecu-burnt-gold',
															'label' => 'ECU Burnt Gold',
													),
													array(
															'value' => 'ecu-manatee',
															'label' => 'ECU Manatee',
													),
													array(
															'value' => 'ecu-dark-teal',
															'label' => 'ECU Dark Teal',
													),
												),
											),
											array(
													'label'       => esc_html__( 'Insert URL to Video', $this->get_shortcode() ),
													'attr'        => 'url',
													'type'        => 'url',
													'description' => 'You can insert a URL here or fill out the next field to use a video in your media library.',
											),
											array(
													'label'       => esc_html__( 'Select Video', $this->get_shortcode() ),
													'attr'        => 'src',
													'type'        => 'attachment',
													'libraryType' => array('video'),
													'addButton'   => esc_html__( 'Select Video', $this->get_shortcode() ),
													'frameTitle'  => esc_html__( 'Select Video', $this->get_shortcode() ),
											),
											array(
													'label'       => esc_html__( 'Select Poster (default image to show as placeholder before the media plays)', $this->get_shortcode() ),
													'attr'        => 'poster',
													'type'        => 'attachment',
													'description' => 'Image to show as placeholder before the video plays',
													'libraryType' => array('image'),
													'addButton'   => esc_html__( 'Select Picture', $this->get_shortcode()),
													'frameTitle'  => esc_html__( 'Select Picture', $this->get_shortcode()),
											),
											array(
													'label'  => esc_html__( 'Loop', $this->get_shortcode() ),
													'attr'      => 'loop',
													'description' => 'If set to “on” the video will start over again every time it is finished.',
													'type'      => 'select',
													'value'		=> '',
													'options'   => array(
															array(
																	'value' => '',
																	'label' => 'Off',
															),
															array(
																	'value' => 'on',
																	'label' => 'On',
															),
													),
											),
											array(
													'label'  => esc_html__( 'Autoplay', $this->get_shortcode() ),
													'attr'      => 'autoplay',
													'type'      => 'select',
													'description' => 'Causes the video to automatically play as soon as it is ready',

													'options'   => array(
															array(
																	'value' => '',
																	'label' => 'Off',
															),
															array(
																	'value' => 'on',
																	'label' => 'On',
															),
													),
											),
											array(
													'label'  => esc_html__( 'Preload', $this->get_shortcode() ),
													'attr'      => 'preload',
													'type'      => 'select',
													'description'		=> 'Defines if and how the video should be loaded when the page loads. By default it’s set to “metadata”.',
													'options'   => array(
															array(
																	'value' => '',
																	'label' => 'Metadata (only metadata should be loaded when the page loads)',
															),
															array(
																	'value' => 'none',
																	'label' => 'None (the video should not be loaded when the page loads)',
															),
															array(
																	'value' => 'auto',
																	'label' => 'Auto (the video should be loaded entirely when the page loads)',
															),
													),
											),
											array(
													'label'  => esc_html__( 'Height', $this->get_shortcode() ),
													'attr'   => 'height',
													'description' => 'It’s better not to specify a height. WordPress will fit the video in the content area with the best possible width and height (keeping the aspect ratio). This will also keep the video size responsive.',
													'type'   => 'text',
													'encode' => true,
											),
											array(
													'label'  => esc_html__( 'Width', $this->get_shortcode() ),
													'attr'   => 'width',
													'description' => 'It’s better not to specify a width. WordPress will fit the video in the content area with the best possible width and height (keeping the aspect ratio). This will also keep the video size responsive.',
													'type'   => 'text',
													'encode' => true,
											),
									)
								)
						);
				}
			}
		}

	}

	new Ecu_Video;
