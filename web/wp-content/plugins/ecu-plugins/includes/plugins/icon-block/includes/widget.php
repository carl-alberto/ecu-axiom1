<?php
/**
 * Icon Block widget.
 *
 * @package Icon-block
 */

// Register the widget
add_action( 'widgets_init', function(){
	register_widget( 'WP_Icon_Block_Widget' );
});

	/**
	 * Widget class.
	 *
	 * @since 1.0.0
	 *
	 * @author  atwebdev
	 */
	class WP_Icon_Block_Widget extends WP_Widget {

		/**
		 * Constructor. Sets up and creates the widget with appropriate settings.
		 *
		 * @since 1.0.0
		 * @access public
		 */
		public function __construct() {

			add_action('admin_enqueue_scripts', array($this, 'scripts'),1);
			add_action('admin_enqueue_scripts', array($this, 'styles'),2);
			$widget_ops = array(
				'classname'   => 'wp-icon_block',
				'description' => __( 'A block of content with an icon centered above the title, text, and button', 'wp-icon_block' )
			);

			parent::__construct( 'wp-icon_block', 'ECU Icon Block Widget', $widget_ops);
		}

		/**
		 * The scripts for this widget
		 *
		 * @since 1.0.0
		 * @access public
		 *
		 */
		public function scripts($hook) {

			if( $hook != 'widgets.php' ) 
				return;
			wp_enqueue_script('ecu_icon_block_admin', plugin_dir_url( __FILE__)  . '../js/icon-select.js',array('jquery'));
			wp_enqueue_script('ecu-icon-select-fonticonpicker', plugin_dir_url( __FILE__)  . '../../../shortcake_fields/icon-select/js/jquery.fonticonpicker.js',array('jquery'));		
		}

		/**
		 * The styles for this widget
		 *
		 * @since 1.0.0
		 * @access public
		 *
		 */
		public function styles($hook) {

			if( $hook != 'widgets.php' ) 
				return;
			wp_enqueue_style( 'ecu-icon-select-fonticonpicker', plugin_dir_url( __FILE__ ) . '../../../shortcake_fields/icon-select/css/jquery.fonticonpicker.min.css' );
			wp_enqueue_style( 'ecu-icon-select-fontello', plugin_dir_url( __FILE__ ) . '../../../shortcake_fields/icon-select/fontello/css/fontello.css' );
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
			$block_title = esc_html( $instance['block_title'] );
			$icon = esc_html( $instance['icon'] );
			$icon_size = esc_html( $instance['icon_size'] );
			$icon_color = esc_html( $instance['icon_color'] );
			$page = $instance['page'];
			$text = esc_html( $instance['text'] );
			if ($instance['button_text'] != '') {
				$button_text = esc_html( $instance['button_text'] );
			} else {
				$button_text= 'Learn More';
			}
			$button_url = esc_html( $instance['button_url'] );

			$str = '';

			// Output Widget
			echo $args['before_widget'];

			if ( ! empty( $title ) ) {
				echo $args['before_title'] . $title. $args['after_title'];
			}

			echo '<div class="ecu-icon-container ecu-icon" style="text-align: center;">';

			if ( ! empty( $icon ) ) {
				echo '<p class="' . $icon . '" aria-hidden="true" style="font-size: ' . $icon_size . ';color: ' . $icon_color . '"/>';
			}

			if ( ! empty($block_title)) {
				echo '<p class="ecu-icon-block-widget-title">' . $block_title . '</p>';
			}

			if ( ! empty($text)) {
				echo '<p>' . wp_kses_post($text) . '</p>';
			}

			if(!empty($url) || !empty($page)){
				if (!empty($button_url)) {
					echo '<p><a href="' . esc_url($button_url) . '" class="featuredblock-button btn-ribbon">' . esc_html($button_text) . '</a></p>';
				} else {

					if (!empty($page)) {

						$page_url = esc_url(get_page_link($page));
						echo '<p><a href="' . $page_url . '" class="featuredblock-button btn-ribbon">' .  esc_html($button_text) . '</a></p>';
					}
				}
			}

			echo '</div>';
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
			$instance['title'] = sanitize_text_field( $new_instance['title'] );
			$instance['block_title'] = sanitize_text_field( $new_instance['block_title'] );
			$instance['icon'] = sanitize_text_field( $new_instance['icon'] );
			$instance['text'] = sanitize_text_field( $new_instance['text'] );
			$instance['button_text'] = sanitize_text_field( $new_instance['button_text'] );
			$instance['button_url'] = sanitize_text_field( $new_instance['button_url'] );
			$instance['page'] = sanitize_text_field( $new_instance['page'] );
			$instance['icon_color'] = sanitize_text_field( $new_instance['icon_color'] );
			$instance['icon_size'] = sanitize_text_field( $new_instance['icon_size'] );

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
		 *      @type string  $title			The title of the widget
		 *      @type string  $block_title		The title of the icon block
		 *      @type string  $icon				The icon to display
		 *      @type string  $text				The text to display
		 *      @type string  $button_text		The button text
		 *      @type string  $button_url		The url that the button text should link to
		 *      @type string  $icon_color		The color of the icon
		 *      @type string  $icon		The size of the icon
		 * }
		 */
		public function form( $instance ) {

			// Set form values
			if( isset( $instance['title'] ) ) {
				$title = $instance['title'];
			} else {
				$title = '';
			}
			if( isset( $instance['block_title'] ) ) {
				$block_title= $instance['block_title'];
			} else {
				$block_title= '';
			}
			if( isset( $instance['icon'] ) ) {
				$icon= $instance['icon'];
			} else {
				$icon= '';
			}
			if( isset( $instance['text'] ) ) {
				$text= $instance['text'];
			} else {
				$text= '';
			}
			if( isset( $instance['button_text'] ) ) {
				$button_text= $instance['button_text'];
			} else {
				$button_text= '';
			}
			if( isset( $instance['button_url'] ) ) {
				$button_url= $instance['button_url'];
			} else {
				$button_url= '';
			}
			if( isset( $instance['page'] ) ) {
				$page = $instance['page'];
			} else {
				$page = '';
			}
			if( isset( $instance['icon_color'] ) ) {
				$icon_color= $instance['icon_color'];
			} else {
				$icon_color= '#000';
			}
			if( isset( $instance['icon_size'] ) ) {
				$icon_size= $instance['icon_size'];
			} else {
				$icon_size= '';
			}

			// Get the list of pages on this site
			$pages = get_pages();

			//Make sure to fire only when the DOM is ready
			echo "
			<script type='text/javascript'>
			jQuery(document).ready(function($) {

				$('#" . $this->get_field_id( 'icon' ) . "').fontIconPicker({
		            theme: 'fip-bootstrap',
		            hasSearch: true
		        }); // Load with default options

				$('#" . $this->get_field_id( 'icon_color' ) . "').wpColorPicker();
			})
			</script>";
	
			// Start Form

			// Basic Options
			echo '<h4>Basic Options</h4>';

			// widget title
			echo '<p>';
			echo '<label for="' . $this->get_field_name( 'title' ) . '">Widget Title:</label>';
			echo '<input id="' . $this->get_field_id( 'title' ) . '" class="widefat" type="text" name="' . $this->get_field_name( 'title' ) . '" value="' . esc_attr($title) . '">';
			echo '</p>';

			// title
			echo '<p>';
			echo '<label for="' . $this->get_field_name( 'block_title' ) . '">Title:</label>';
			echo '<input id="' . $this->get_field_id( 'block_title' ) . '" class="widefat" type="text" name="' . $this->get_field_name( 'block_title' ) . '" value="' . esc_attr($block_title) . '">';
			echo '<span class="input_description">The focus of the block.</span>';
			echo '</p>';

			// icon 
			echo '<p>';

			echo '<label for="' . $this->get_field_name( 'icon' ) . '">Icon:</label>';
			echo '<select id="' . $this->get_field_id( 'icon' ) . '" class="widefat" name="' . $this->get_field_name( 'icon' ) . '">';
			echo '<optgroup label="Font Awesome">';
			echo '<option value="icon-glass"';
			if (esc_attr($icon) == 'icon-glass') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-music"';
			if (esc_attr($icon) == 'icon-music') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-search"';
			if (esc_attr($icon) == 'icon-search') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-mail"';
			if (esc_attr($icon) == 'icon-mail') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-mail-alt"';
			if (esc_attr($icon) == 'icon-mail-alt') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-mail-squared"';
			if (esc_attr($icon) == 'icon-mail-squared') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-heart"';
			if (esc_attr($icon) == 'icon-heart') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-heart-empty"';
			if (esc_attr($icon) == 'icon-heart-empty') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-star"';
			if (esc_attr($icon) == 'icon-star') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-star-empty"';
			if (esc_attr($icon) == 'icon-star-empty') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-star-half"';
			if (esc_attr($icon) == 'icon-star-half') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-star-half-alt"';
			if (esc_attr($icon) == 'icon-star-half-alt') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-user"';
			if (esc_attr($icon) == 'icon-user') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-user-plus"';
			if (esc_attr($icon) == 'icon-user-plus') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-user-times"';
			if (esc_attr($icon) == 'icon-user-times') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-video"';
			if (esc_attr($icon) == 'icon-video') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-videocam"';
			if (esc_attr($icon) == 'icon-videocam') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-picture"';
			if (esc_attr($icon) == 'icon-picture') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-camera"';
			if (esc_attr($icon) == 'icon-camera') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-camera-alt"';
			if (esc_attr($icon) == 'icon-camera-alt') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-th-large"';
			if (esc_attr($icon) == 'icon-th-large') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-th"';
			if (esc_attr($icon) == 'icon-th') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-th-list"';
			if (esc_attr($icon) == 'icon-th-list') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-ok"';
			if (esc_attr($icon) == 'icon-ok') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-ok-circled"';
			if (esc_attr($icon) == 'icon-ok-circled') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-ok-circled2"';
			if (esc_attr($icon) == 'icon-ok-circled2') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-ok-squared"';
			if (esc_attr($icon) == 'icon-ok-squared') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-cancel"';
			if (esc_attr($icon) == 'icon-cancel') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-cancel-circled"';
			if (esc_attr($icon) == 'icon-cancel-circled') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-cancel-circled2"';
			if (esc_attr($icon) == 'icon-cancel-circled2') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-minus-circled"';
			if (esc_attr($icon) == 'icon-minus-circled') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-minus-squared"';
			if (esc_attr($icon) == 'icon-minus-squared') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-minus-squared-alt"';
			if (esc_attr($icon) == 'icon-minus-squared-alt') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-help"';
			if (esc_attr($icon) == 'icon-help') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-help-circled"';
			if (esc_attr($icon) == 'icon-help-circled') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-info-circled"';
			if (esc_attr($icon) == 'icon-info-circled') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-info"';
			if (esc_attr($icon) == 'icon-info') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-home"';
			if (esc_attr($icon) == 'icon-home') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-link"';
			if (esc_attr($icon) == 'icon-link') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-unlink"';
			if (esc_attr($icon) == 'icon-unlink') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-link-ext"';
			if (esc_attr($icon) == 'icon-link-ext') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-link-ext-alt"';
			if (esc_attr($icon) == 'icon-link-ext-alt') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-attach"';
			if (esc_attr($icon) == 'icon-attach') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-lock"';
			if (esc_attr($icon) == 'icon-lock') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-lock-open"';
			if (esc_attr($icon) == 'icon-lock-open') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-tags"';
			if (esc_attr($icon) == 'icon-tags') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-bookmark"';
			if (esc_attr($icon) == 'icon-bookmark') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-bookmark-empty"';
			if (esc_attr($icon) == 'icon-bookmark-empty') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-flag"';
			if (esc_attr($icon) == 'icon-flag') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-flag-empty"';
			if (esc_attr($icon) == 'icon-flag-empty') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-flag-checkered"';
			if (esc_attr($icon) == 'icon-flag-checkered') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-thumbs-up"';
			if (esc_attr($icon) == 'icon-thumbs-up') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-thumbs-down"';
			if (esc_attr($icon) == 'icon-thumbs-down') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-thumbs-up-alt"';
			if (esc_attr($icon) == 'icon-thumbs-up-alt') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-thumbs-down-alt"';
			if (esc_attr($icon) == 'icon-thumbs-down-alt') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-download"';
			if (esc_attr($icon) == 'icon-download') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-upload"';
			if (esc_attr($icon) == 'icon-upload') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-download-cloud"';
			if (esc_attr($icon) == 'icon-download-cloud') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-upload-cloud"';
			if (esc_attr($icon) == 'icon-upload-cloud') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-reply"';
			if (esc_attr($icon) == 'icon-reply') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-export"';
			if (esc_attr($icon) == 'icon-export') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-export-alt"';
			if (esc_attr($icon) == 'icon-export-alt') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-share"';
			if (esc_attr($icon) == 'icon-share') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-share-squared"';
			if (esc_attr($icon) == 'icon-share-squared') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-pencil"';
			if (esc_attr($icon) == 'icon-pencil') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-pencil-squared"';
			if (esc_attr($icon) == 'icon-pencil-squared') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-edit"';
			if (esc_attr($icon) == 'icon-edit') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-print"';
			if (esc_attr($icon) == 'icon-print') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-retweet"';
			if (esc_attr($icon) == 'icon-retweet') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-keyboard"';
			if (esc_attr($icon) == 'icon-keyboard') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-gamepad"';
			if (esc_attr($icon) == 'icon-gamepad') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-comment"';
			if (esc_attr($icon) == 'icon-comment') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-chat"';
			if (esc_attr($icon) == 'icon-chat') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-comment-empty"';
			if (esc_attr($icon) == 'icon-comment-empty') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-chat-empty"';
			if (esc_attr($icon) == 'icon-chat-empty') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-attention"';
			if (esc_attr($icon) == 'icon-attention') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-attention-circled"';
			if (esc_attr($icon) == 'icon-attention-circled') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-location"';
			if (esc_attr($icon) == 'icon-location') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-direction"';
			if (esc_attr($icon) == 'icon-direction') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-compass"';
			if (esc_attr($icon) == 'icon-compass') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-trash"';
			if (esc_attr($icon) == 'icon-trash') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-trash-empty"';
			if (esc_attr($icon) == 'icon-trash-empty') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-doc"';
			if (esc_attr($icon) == 'icon-doc') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-docs"';
			if (esc_attr($icon) == 'icon-docs') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-doc-text"';
			if (esc_attr($icon) == 'icon-doc-text') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-doc-inv"';
			if (esc_attr($icon) == 'icon-doc-inv') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-doc-text-inv"';
			if (esc_attr($icon) == 'icon-doc-text-inv') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-file-pdf"';
			if (esc_attr($icon) == 'icon-file-pdf') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-file-word"';
			if (esc_attr($icon) == 'icon-file-word') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-file-excel"';
			if (esc_attr($icon) == 'icon-file-excel') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-file-code"';
			if (esc_attr($icon) == 'icon-file-code') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-folder"';
			if (esc_attr($icon) == 'icon-folder') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-folder-open"';
			if (esc_attr($icon) == 'icon-folder-open') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-folder-empty"';
			if (esc_attr($icon) == 'icon-folder-empty') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-folder-open-empty"';
			if (esc_attr($icon) == 'icon-folder-open-empty') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-box"';
			if (esc_attr($icon) == 'icon-box') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-rss"';
			if (esc_attr($icon) == 'icon-rss') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-rss-squared"';
			if (esc_attr($icon) == 'icon-rss-squared') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-phone"';
			if (esc_attr($icon) == 'icon-phone') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-phone-squared"';
			if (esc_attr($icon) == 'icon-phone-squared') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-fax"';
			if (esc_attr($icon) == 'icon-fax') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-menu"';
			if (esc_attr($icon) == 'icon-menu') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-cog"';
			if (esc_attr($icon) == 'icon-cog') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-cog-alt"';
			if (esc_attr($icon) == 'icon-cog-alt') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-wrench"';
			if (esc_attr($icon) == 'icon-wrench') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-calendar-empty"';
			if (esc_attr($icon) == 'icon-calendar-empty') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-login"';
			if (esc_attr($icon) == 'icon-login') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-logout"';
			if (esc_attr($icon) == 'icon-logout') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-mic"';
			if (esc_attr($icon) == 'icon-mic') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-mute"';
			if (esc_attr($icon) == 'icon-mute') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-volume-off"';
			if (esc_attr($icon) == 'icon-volume-off') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-volume-down"';
			if (esc_attr($icon) == 'icon-volume-down') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-volume-up"';
			if (esc_attr($icon) == 'icon-volume-up') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-headphones"';
			if (esc_attr($icon) == 'icon-headphones') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-clock"';
			if (esc_attr($icon) == 'icon-clock') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-lightbulb"';
			if (esc_attr($icon) == 'icon-lightbulb') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-block"';
			if (esc_attr($icon) == 'icon-block') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-resize-full"';
			if (esc_attr($icon) == 'icon-resize-full') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-resize-full-alt"';
			if (esc_attr($icon) == 'icon-resize-full-alt') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-resize-small"';
			if (esc_attr($icon) == 'icon-resize-small') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-down-circled2"';
			if (esc_attr($icon) == 'icon-down-circled2') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-up-circled2"';
			if (esc_attr($icon) == 'icon-up-circled2') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-left-circled2"';
			if (esc_attr($icon) == 'icon-left-circled2') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-right-circled2"';
			if (esc_attr($icon) == 'icon-right-circled2') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-down-dir"';
			if (esc_attr($icon) == 'icon-down-dir') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-up-dir"';
			if (esc_attr($icon) == 'icon-up-dir') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-left-dir"';
			if (esc_attr($icon) == 'icon-left-dir') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-right-dir"';
			if (esc_attr($icon) == 'icon-right-dir') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-down-open"';
			if (esc_attr($icon) == 'icon-down-open') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-left-open"';
			if (esc_attr($icon) == 'icon-left-open') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-right-open"';
			if (esc_attr($icon) == 'icon-right-open') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-up-open"';
			if (esc_attr($icon) == 'icon-up-open') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-angle-left"';
			if (esc_attr($icon) == 'icon-angle-left') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-angle-right"';
			if (esc_attr($icon) == 'icon-angle-right') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-angle-up"';
			if (esc_attr($icon) == 'icon-angle-up') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-angle-double-left"';
			if (esc_attr($icon) == 'icon-angle-double-left') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-angle-double-right"';
			if (esc_attr($icon) == 'icon-angle-double-right') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-angle-double-up"';
			if (esc_attr($icon) == 'icon-angle-double-up') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-angle-double-down"';
			if (esc_attr($icon) == 'icon-angle-double-down') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-down"';
			if (esc_attr($icon) == 'icon-down') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-left"';
			if (esc_attr($icon) == 'icon-left') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-right"';
			if (esc_attr($icon) == 'icon-right') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-up"';
			if (esc_attr($icon) == 'icon-up') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-down-big"';
			if (esc_attr($icon) == 'icon-down-big') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-left-big"';
			if (esc_attr($icon) == 'icon-left-big') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-right-big"';
			if (esc_attr($icon) == 'icon-right-big') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-up-big"';
			if (esc_attr($icon) == 'icon-up-big') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-right-hand"';
			if (esc_attr($icon) == 'icon-right-hand') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-left-hand"';
			if (esc_attr($icon) == 'icon-left-hand') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-up-hand"';
			if (esc_attr($icon) == 'icon-up-hand') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-cw"';
			if (esc_attr($icon) == 'icon-cw') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-ccw"';
			if (esc_attr($icon) == 'icon-ccw') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-arrows-cw"';
			if (esc_attr($icon) == 'icon-arrows-cw') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-level-up"';
			if (esc_attr($icon) == 'icon-level-up') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-level-down"';
			if (esc_attr($icon) == 'icon-level-down') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-shuffle"';
			if (esc_attr($icon) == 'icon-shuffle') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-exchange"';
			if (esc_attr($icon) == 'icon-exchange') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-history"';
			if (esc_attr($icon) == 'icon-history') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-expand"';
			if (esc_attr($icon) == 'icon-expand') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-collapse"';
			if (esc_attr($icon) == 'icon-collapse') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-expand-right"';
			if (esc_attr($icon) == 'icon-expand-right') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-collapse-left"';
			if (esc_attr($icon) == 'icon-collapse-left') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-play"';
			if (esc_attr($icon) == 'icon-play') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-play-circled"';
			if (esc_attr($icon) == 'icon-play-circled') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-play-circled2"';
			if (esc_attr($icon) == 'icon-play-circled2') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-to-start-alt"';
			if (esc_attr($icon) == 'icon-to-start-alt') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-fast-fw"';
			if (esc_attr($icon) == 'icon-fast-fw') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-fast-bw"';
			if (esc_attr($icon) == 'icon-fast-bw') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-eject"';
			if (esc_attr($icon) == 'icon-eject') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-target"';
			if (esc_attr($icon) == 'icon-target') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-signal"';
			if (esc_attr($icon) == 'icon-signal') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-wifi"';
			if (esc_attr($icon) == 'icon-wifi') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-award"';
			if (esc_attr($icon) == 'icon-award') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-desktop"';
			if (esc_attr($icon) == 'icon-desktop') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-laptop"';
			if (esc_attr($icon) == 'icon-laptop') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-tablet"';
			if (esc_attr($icon) == 'icon-tablet') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-mobile"';
			if (esc_attr($icon) == 'icon-mobile') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-inbox"';
			if (esc_attr($icon) == 'icon-inbox') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-globe"';
			if (esc_attr($icon) == 'icon-globe') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-sun"';
			if (esc_attr($icon) == 'icon-sun') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-fighter-jet"';
			if (esc_attr($icon) == 'icon-fighter-jet') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-paper-plane"';
			if (esc_attr($icon) == 'icon-paper-plane') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-paper-plane-empty"';
			if (esc_attr($icon) == 'icon-paper-plane-empty') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-space-shuttle"';
			if (esc_attr($icon) == 'icon-space-shuttle') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-leaf"';
			if (esc_attr($icon) == 'icon-leaf') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-font"';
			if (esc_attr($icon) == 'icon-font') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-bold"';
			if (esc_attr($icon) == 'icon-bold') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-medium"';
			if (esc_attr($icon) == 'icon-medium') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-italic"';
			if (esc_attr($icon) == 'icon-italic') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-header"';
			if (esc_attr($icon) == 'icon-header') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-paragraph"';
			if (esc_attr($icon) == 'icon-paragraph') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-text-height"';
			if (esc_attr($icon) == 'icon-text-height') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-text-width"';
			if (esc_attr($icon) == 'icon-text-width') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-align-left"';
			if (esc_attr($icon) == 'icon-align-left') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-align-center"';
			if (esc_attr($icon) == 'icon-align-center') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-list-bullet"';
			if (esc_attr($icon) == 'icon-list-bullet') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-list-numbered"';
			if (esc_attr($icon) == 'icon-list-numbered') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-strike"';
			if (esc_attr($icon) == 'icon-strike') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-underline"';
			if (esc_attr($icon) == 'icon-underline') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-superscript"';
			if (esc_attr($icon) == 'icon-superscript') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-subscript"';
			if (esc_attr($icon) == 'icon-subscript') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-table"';
			if (esc_attr($icon) == 'icon-table') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-columns"';
			if (esc_attr($icon) == 'icon-columns') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-crop"';
			if (esc_attr($icon) == 'icon-crop') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-scissors"';
			if (esc_attr($icon) == 'icon-scissors') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-paste"';
			if (esc_attr($icon) == 'icon-paste') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-briefcase"';
			if (esc_attr($icon) == 'icon-briefcase') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-suitcase"';
			if (esc_attr($icon) == 'icon-suitcase') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-ellipsis"';
			if (esc_attr($icon) == 'icon-ellipsis') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-ellipsis-vert"';
			if (esc_attr($icon) == 'icon-ellipsis-vert') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-book"';
			if (esc_attr($icon) == 'icon-book') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-adjust"';
			if (esc_attr($icon) == 'icon-adjust') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-tint"';
			if (esc_attr($icon) == 'icon-tint') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-toggle-off"';
			if (esc_attr($icon) == 'icon-toggle-off') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-toggle-on"';
			if (esc_attr($icon) == 'icon-toggle-on') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-check"';
			if (esc_attr($icon) == 'icon-check') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-check-empty"';
			if (esc_attr($icon) == 'icon-check-empty') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-circle"';
			if (esc_attr($icon) == 'icon-circle') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-circle-empty"';
			if (esc_attr($icon) == 'icon-circle-empty') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-circle-thin"';
			if (esc_attr($icon) == 'icon-circle-thin') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-circle-notch"';
			if (esc_attr($icon) == 'icon-circle-notch') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-dot-circled"';
			if (esc_attr($icon) == 'icon-dot-circled') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-asterisk"';
			if (esc_attr($icon) == 'icon-asterisk') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-gift"';
			if (esc_attr($icon) == 'icon-gift') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-fire"';
			if (esc_attr($icon) == 'icon-fire') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-ticket"';
			if (esc_attr($icon) == 'icon-ticket') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-credit-card"';
			if (esc_attr($icon) == 'icon-credit-card') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-floppy"';
			if (esc_attr($icon) == 'icon-floppy') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-megaphone"';
			if (esc_attr($icon) == 'icon-megaphone') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-hdd"';
			if (esc_attr($icon) == 'icon-hdd') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-key"';
			if (esc_attr($icon) == 'icon-key') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-fork"';
			if (esc_attr($icon) == 'icon-fork') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-rocket"';
			if (esc_attr($icon) == 'icon-rocket') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-bug"';
			if (esc_attr($icon) == 'icon-bug') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-certificate"';
			if (esc_attr($icon) == 'icon-certificate') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-tasks"';
			if (esc_attr($icon) == 'icon-tasks') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-filter"';
			if (esc_attr($icon) == 'icon-filter') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-beaker"';
			if (esc_attr($icon) == 'icon-beaker') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-magic"';
			if (esc_attr($icon) == 'icon-magic') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-cab"';
			if (esc_attr($icon) == 'icon-cab') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-train"';
			if (esc_attr($icon) == 'icon-train') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-subway"';
			if (esc_attr($icon) == 'icon-subway') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-ship"';
			if (esc_attr($icon) == 'icon-ship') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-money"';
			if (esc_attr($icon) == 'icon-money') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-euro"';
			if (esc_attr($icon) == 'icon-euro') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-pound"';
			if (esc_attr($icon) == 'icon-pound') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-dollar"';
			if (esc_attr($icon) == 'icon-dollar') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-rupee"';
			if (esc_attr($icon) == 'icon-rupee') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-yen"';
			if (esc_attr($icon) == 'icon-yen') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-rouble"';
			if (esc_attr($icon) == 'icon-rouble') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-shekel"';
			if (esc_attr($icon) == 'icon-shekel') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-try"';
			if (esc_attr($icon) == 'icon-try') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-won"';
			if (esc_attr($icon) == 'icon-won') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-bitcoin"';
			if (esc_attr($icon) == 'icon-bitcoin') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-viacoin"';
			if (esc_attr($icon) == 'icon-viacoin') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-sort-name-up"';
			if (esc_attr($icon) == 'icon-sort-name-up') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-sort-name-down"';
			if (esc_attr($icon) == 'icon-sort-name-down') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-sort-number-up"';
			if (esc_attr($icon) == 'icon-sort-number-up') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-sort-number-down"';
			if (esc_attr($icon) == 'icon-sort-number-down') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-hammer"';
			if (esc_attr($icon) == 'icon-hammer') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-gauge"';
			if (esc_attr($icon) == 'icon-gauge') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-sitemap"';
			if (esc_attr($icon) == 'icon-sitemap') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-spinner"';
			if (esc_attr($icon) == 'icon-spinner') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-coffee"';
			if (esc_attr($icon) == 'icon-coffee') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-food"';
			if (esc_attr($icon) == 'icon-food') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-beer"';
			if (esc_attr($icon) == 'icon-beer') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-user-md"';
			if (esc_attr($icon) == 'icon-user-md') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-stethoscope"';
			if (esc_attr($icon) == 'icon-stethoscope') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-heartbeat"';
			if (esc_attr($icon) == 'icon-heartbeat') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-ambulance"';
			if (esc_attr($icon) == 'icon-ambulance') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-building-filled"';
			if (esc_attr($icon) == 'icon-building-filled') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-bank"';
			if (esc_attr($icon) == 'icon-bank') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-smile"';
			if (esc_attr($icon) == 'icon-smile') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-frown"';
			if (esc_attr($icon) == 'icon-frown') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-meh"';
			if (esc_attr($icon) == 'icon-meh') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-anchor"';
			if (esc_attr($icon) == 'icon-anchor') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-terminal"';
			if (esc_attr($icon) == 'icon-terminal') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-eraser"';
			if (esc_attr($icon) == 'icon-eraser') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-puzzle"';
			if (esc_attr($icon) == 'icon-puzzle') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-shield"';
			if (esc_attr($icon) == 'icon-shield') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-extinguisher"';
			if (esc_attr($icon) == 'icon-extinguisher') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-bullseye"';
			if (esc_attr($icon) == 'icon-bullseye') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-wheelchair"';
			if (esc_attr($icon) == 'icon-wheelchair') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-language"';
			if (esc_attr($icon) == 'icon-language') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-graduation-cap"';
			if (esc_attr($icon) == 'icon-graduation-cap') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-tree"';
			if (esc_attr($icon) == 'icon-tree') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-database"';
			if (esc_attr($icon) == 'icon-database') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-server"';
			if (esc_attr($icon) == 'icon-server') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-lifebuoy"';
			if (esc_attr($icon) == 'icon-lifebuoy') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-rebel"';
			if (esc_attr($icon) == 'icon-rebel') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-empire"';
			if (esc_attr($icon) == 'icon-empire') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-bomb"';
			if (esc_attr($icon) == 'icon-bomb') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-soccer-ball"';
			if (esc_attr($icon) == 'icon-soccer-ball') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-tty"';
			if (esc_attr($icon) == 'icon-tty') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-binoculars"';
			if (esc_attr($icon) == 'icon-binoculars') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-plug"';
			if (esc_attr($icon) == 'icon-plug') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-newspaper"';
			if (esc_attr($icon) == 'icon-newspaper') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-calc"';
			if (esc_attr($icon) == 'icon-calc') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-copyright"';
			if (esc_attr($icon) == 'icon-copyright') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-at"';
			if (esc_attr($icon) == 'icon-at') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-venus"';
			if (esc_attr($icon) == 'icon-venus') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-mars"';
			if (esc_attr($icon) == 'icon-mars') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-mercury"';
			if (esc_attr($icon) == 'icon-mercury') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-transgender"';
			if (esc_attr($icon) == 'icon-transgender') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-transgender-alt"';
			if (esc_attr($icon) == 'icon-transgender-alt') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-venus-double"';
			if (esc_attr($icon) == 'icon-venus-double') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-mars-double"';
			if (esc_attr($icon) == 'icon-mars-double') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-venus-mars"';
			if (esc_attr($icon) == 'icon-venus-mars') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-mars-stroke"';
			if (esc_attr($icon) == 'icon-mars-stroke') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-mars-stroke-v"';
			if (esc_attr($icon) == 'icon-mars-stroke-v') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-mars-stroke-h"';
			if (esc_attr($icon) == 'icon-mars-stroke-h') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-neuter"';
			if (esc_attr($icon) == 'icon-neuter') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-cc-visa"';
			if (esc_attr($icon) == 'icon-cc-visa') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-cc-mastercard"';
			if (esc_attr($icon) == 'icon-cc-mastercard') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-cc-discover"';
			if (esc_attr($icon) == 'icon-cc-discover') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-angellist"';
			if (esc_attr($icon) == 'icon-angellist') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-apple"';
			if (esc_attr($icon) == 'icon-apple') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-b"';
			if (esc_attr($icon) == 'icon-behance') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-behance-squared"';
			if (esc_attr($icon) == 'icon-behance-squared') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-bitbucket"';
			if (esc_attr($icon) == 'icon-bitbucket') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-bitbucket-squared"';
			if (esc_attr($icon) == 'icon-bitbucket-squared') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-buysellads"';
			if (esc_attr($icon) == 'icon-buysellads') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-cc"';
			if (esc_attr($icon) == 'icon-cc') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-codeopen"';
			if (esc_attr($icon) == 'icon-codeopen') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-connectdevelop"';
			if (esc_attr($icon) == 'icon-connectdevelop') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-css3"';
			if (esc_attr($icon) == 'icon-css3') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-dashcube"';
			if (esc_attr($icon) == 'icon-dashcube') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-delicious"';
			if (esc_attr($icon) == 'icon-delicious') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-deviantart"';
			if (esc_attr($icon) == 'icon-deviantart') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-digg"';
			if (esc_attr($icon) == 'icon-digg') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-facebook-official"';
			if (esc_attr($icon) == 'icon-facebook-official') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-f"';
			if (esc_attr($icon) == 'icon-flickr') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-forumbee"';
			if (esc_attr($icon) == 'icon-forumbee') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-foursquare"';
			if (esc_attr($icon) == 'icon-foursquare') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-git-squared"';
			if (esc_attr($icon) == 'icon-git-squared') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-git"';
			if (esc_attr($icon) == 'icon-git') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-github"';
			if (esc_attr($icon) == 'icon-github') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-github-squared"';
			if (esc_attr($icon) == 'icon-github-squared') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-github-circled"';
			if (esc_attr($icon) == 'icon-github-circled') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-gittip"';
			if (esc_attr($icon) == 'icon-gittip') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-google"';
			if (esc_attr($icon) == 'icon-google') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-gplus"';
			if (esc_attr($icon) == 'icon-gplus') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-gplus-squared"';
			if (esc_attr($icon) == 'icon-gplus-squared') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-gwallet"';
			if (esc_attr($icon) == 'icon-gwallet') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-hacker-news"';
			if (esc_attr($icon) == 'icon-hacker-news') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-lastfm"';
			if (esc_attr($icon) == 'icon-lastfm') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-lastfm-squared"';
			if (esc_attr($icon) == 'icon-lastfm-squared') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-leanpub"';
			if (esc_attr($icon) == 'icon-leanpub') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-linkedin-squared"';
			if (esc_attr($icon) == 'icon-linkedin-squared') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-linux"';
			if (esc_attr($icon) == 'icon-linux') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-linkedin"';
			if (esc_attr($icon) == 'icon-linkedin') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-maxcdn"';
			if (esc_attr($icon) == 'icon-maxcdn') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-meanpath"';
			if (esc_attr($icon) == 'icon-meanpath') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-openid"';
			if (esc_attr($icon) == 'icon-openid') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-pagelines"';
			if (esc_attr($icon) == 'icon-pagelines') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-paypal"';
			if (esc_attr($icon) == 'icon-paypal') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-pied-piper-squared"';
			if (esc_attr($icon) == 'icon-pied-piper-squared') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-pied-piper-alt"';
			if (esc_attr($icon) == 'icon-pied-piper-alt') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-pinterest"';
			if (esc_attr($icon) == 'icon-pinterest') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-pinterest-circled"';
			if (esc_attr($icon) == 'icon-pinterest-circled') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-sellsy"';
			if (esc_attr($icon) == 'icon-sellsy') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-shirtsinbulk"';
			if (esc_attr($icon) == 'icon-shirtsinbulk') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-simplybuilt"';
			if (esc_attr($icon) == 'icon-simplybuilt') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-skyatlas"';
			if (esc_attr($icon) == 'icon-skyatlas') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-skype"';
			if (esc_attr($icon) == 'icon-skype') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-slack"';
			if (esc_attr($icon) == 'icon-slack') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-slideshare"';
			if (esc_attr($icon) == 'icon-slideshare') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-soundcloud"';
			if (esc_attr($icon) == 'icon-soundcloud') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-spotify"';
			if (esc_attr($icon) == 'icon-spotify') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-stackexchange"';
			if (esc_attr($icon) == 'icon-stackexchange') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-stackoverflow"';
			if (esc_attr($icon) == 'icon-stackoverflow') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-steam"';
			if (esc_attr($icon) == 'icon-steam') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-steam-squared"';
			if (esc_attr($icon) == 'icon-steam-squared') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-stumbleupon"';
			if (esc_attr($icon) == 'icon-stumbleupon') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-stumbleupon-circled"';
			if (esc_attr($icon) == 'icon-stumbleupon-circled') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-twitter-squared"';
			if (esc_attr($icon) == 'icon-twitter-squared') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-twitter"';
			if (esc_attr($icon) == 'icon-twitter') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-vimeo-squared"';
			if (esc_attr($icon) == 'icon-vimeo-squared') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-vine"';
			if (esc_attr($icon) == 'icon-vine') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-vkontakte"';
			if (esc_attr($icon) == 'icon-vkontakte') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-whatsapp"';
			if (esc_attr($icon) == 'icon-whatsapp') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-wechat"';
			if (esc_attr($icon) == 'icon-wechat') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-weibo"';
			if (esc_attr($icon) == 'icon-weibo') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-windows"';
			if (esc_attr($icon) == 'icon-windows') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-wordpress"';
			if (esc_attr($icon) == 'icon-wordpress') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-xing"';
			if (esc_attr($icon) == 'icon-xing') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-xing-squared"';
			if (esc_attr($icon) == 'icon-xing-squared') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-yelp"';
			if (esc_attr($icon) == 'icon-yelp') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-youtube"';
			if (esc_attr($icon) == 'icon-youtube') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-yahoo"';
			if (esc_attr($icon) == 'icon-yahoo') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-y-combinator"';
			if (esc_attr($icon) == 'icon-y-combinator') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-optin-monster"';
			if (esc_attr($icon) == 'icon-optin-monster') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-opencart"';
			if (esc_attr($icon) == 'icon-opencart') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-expeditedssl"';
			if (esc_attr($icon) == 'icon-expeditedssl') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-battery-4"';
			if (esc_attr($icon) == 'icon-battery-4') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-battery-3"';
			if (esc_attr($icon) == 'icon-battery-3') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-battery-2"';
			if (esc_attr($icon) == 'icon-battery-2') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-battery-1"';
			if (esc_attr($icon) == 'icon-battery-1') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-battery-0"';
			if (esc_attr($icon) == 'icon-battery-0') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-mouse-pointer"';
			if (esc_attr($icon) == 'icon-mouse-pointer') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-i-cursor"';
			if (esc_attr($icon) == 'icon-i-cursor') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-object-group"';
			if (esc_attr($icon) == 'icon-object-group') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-object-ungroup"';
			if (esc_attr($icon) == 'icon-object-ungroup') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-sticky-note"';
			if (esc_attr($icon) == 'icon-sticky-note') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-sticky-note-o"';
			if (esc_attr($icon) == 'icon-sticky-note-o') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-hourglass-1"';
			if (esc_attr($icon) == 'icon-hourglass-1') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-hourglass-2"';
			if (esc_attr($icon) == 'icon-hourglass-2') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-hourglass-3"';
			if (esc_attr($icon) == 'icon-hourglass-3') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-hourglass"';
			if (esc_attr($icon) == 'icon-hourglass') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-hand-peace-o"';
			if (esc_attr($icon) == 'icon-hand-grab-o') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-hand-paper-o"';
			if (esc_attr($icon) == 'icon-hand-paper-o') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-hand-scissors-o"';
			if (esc_attr($icon) == 'icon-hand-scissors-o') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-hand-lizard-o"';
			if (esc_attr($icon) == 'icon-hand-lizard-o') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-hand-spock-o"';
			if (esc_attr($icon) == 'icon-hand-spock-o') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-hand-pointer-o"';
			if (esc_attr($icon) == 'icon-hand-pointer-o') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-hand-peace-o"';
			if (esc_attr($icon) == 'icon-hand-peace-o') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-trademark"';
			if (esc_attr($icon) == 'icon-trademark') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-registered"';
			if (esc_attr($icon) == 'icon-registered') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-creative-commons"';
			if (esc_attr($icon) == 'icon-creative-commons') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-gg"';
			if (esc_attr($icon) == 'icon-gg') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-wikipedia-w"';
			if (esc_attr($icon) == 'icon-wikipedia-w') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-safari"';
			if (esc_attr($icon) == 'icon-safari') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-chrome"';
			if (esc_attr($icon) == 'icon-chrome') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-firefox"';
			if (esc_attr($icon) == 'icon-firefox') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-opera"';
			if (esc_attr($icon) == 'icon-opera') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-internet-explorer"';
			if (esc_attr($icon) == 'icon-internet-explorer') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-television"';
			if (esc_attr($icon) == 'icon-television') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-contao"';
			if (esc_attr($icon) == 'icon-contao') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-500px"';
			if (esc_attr($icon) == 'icon-500px') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-amazon"';
			if (esc_attr($icon) == 'icon-amazon') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-calendar-plus-o"';
			if (esc_attr($icon) == 'icon-calendar-plus-o') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-calendar-minus-o"';
			if (esc_attr($icon) == 'icon-calendar-minus-o') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-calendar-times-o"';
			if (esc_attr($icon) == 'icon-calendar-times-o') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-calendar-check-o"';
			if (esc_attr($icon) == 'icon-calendar-check-o') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-industry"';
			if (esc_attr($icon) == 'icon-industry') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-commenting-o"';
			if (esc_attr($icon) == 'icon-commenting-o') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-houzz"';
			if (esc_attr($icon) == 'icon-houzz') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-vimeo"';
			if (esc_attr($icon) == 'icon-vimeo') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-black-tie"';
			if (esc_attr($icon) == 'icon-black-tie') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-fonticons"';
			if (esc_attr($icon) == 'icon-fonticons') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-reddit-alien"';
			if (esc_attr($icon) == 'icon-reddit-alien') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-edge"';
			if (esc_attr($icon) == 'icon-edge') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-credit-card-alt"';
			if (esc_attr($icon) == 'icon-credit-card-alt') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-codiepie"';
			if (esc_attr($icon) == 'icon-codiepie') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-modx"';
			if (esc_attr($icon) == 'icon-modx') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-font-awesome"';
			if (esc_attr($icon) == 'icon-fort-awesome') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-usb"';
			if (esc_attr($icon) == 'icon-usb') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-product-hunt"';
			if (esc_attr($icon) == 'icon-product-hunt') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-mixcloud"';
			if (esc_attr($icon) == 'icon-mixcloud') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-scribd"';
			if (esc_attr($icon) == 'icon-scribd') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-shopping-basket"';
			if (esc_attr($icon) == 'icon-shopping-basket') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-hashtag"';
			if (esc_attr($icon) == 'icon-hashtag') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-bluetooth"';
			if (esc_attr($icon) == 'icon-bluetooth') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-bluetooth-b"';
			if (esc_attr($icon) == 'icon-bluetooth-b') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-percent"';
			if (esc_attr($icon) == 'icon-percent') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-gitlab"';
			if (esc_attr($icon) == 'icon-gitlab') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-wpbeginner"';
			if (esc_attr($icon) == 'icon-wpbeginner') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-wpforms"';
			if (esc_attr($icon) == 'icon-wpforms') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-envira"';
			if (esc_attr($icon) == 'icon-envira') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-universal-access"';
			if (esc_attr($icon) == 'icon-universal-access') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-wheelchair-alt"';
			if (esc_attr($icon) == 'icon-wheelchair-alt') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-question-circle-o"';
			if (esc_attr($icon) == 'icon-question-circle-o') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-blind"';
			if (esc_attr($icon) == 'icon-blind') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-audio-description"';
			if (esc_attr($icon) == 'icon-audio-description') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-volume-control-phone"';
			if (esc_attr($icon) == 'icon-volume-control-phone') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-glide-g"';
			if (esc_attr($icon) == 'icon-glide-g') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-sign-language"';
			if (esc_attr($icon) == 'icon-sign-language') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-low-vision"';
			if (esc_attr($icon) == 'icon-low-vision') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-viadeo"';
			if (esc_attr($icon) == 'icon-viadeo') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-viadeo-square"';
			if (esc_attr($icon) == 'icon-viadeo-square') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-snapchat"';
			if (esc_attr($icon) == 'icon-snapchat') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-snapchat-ghost"';
			if (esc_attr($icon) == 'icon-snapchat-ghost') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-snapchat-square"';
			if (esc_attr($icon) == 'icon-snapchat-square') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-pied-piper"';
			if (esc_attr($icon) == 'icon-pied-piper') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-first-order"';
			if (esc_attr($icon) == 'icon-first-order') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-yoast"';
			if (esc_attr($icon) == 'icon-yoast') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-themeisle"';
			if (esc_attr($icon) == 'icon-themeisle') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-google-plus-circle"';
			if (esc_attr($icon) == 'icon-google-plus-circle') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-font-awesome"';
			if (esc_attr($icon) == 'icon-font-awesome') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-handshake-o"';
			if (esc_attr($icon) == 'icon-handshake-o') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-address-card"';
			if (esc_attr($icon) == 'icon-address-card') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-address-card-o"';
			if (esc_attr($icon) == 'icon-address-card-o') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-circle"';
			if (esc_attr($icon) == 'icon-user-circle') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-user-circle-o"';
			if (esc_attr($icon) == 'icon-user-circle-o') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-user-o"';
			if (esc_attr($icon) == 'icon-user-o') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-id-badge"';
			if (esc_attr($icon) == 'icon-id-badge') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-id-card"';
			if (esc_attr($icon) == 'icon-id-card') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-id-card-o"';
			if (esc_attr($icon) == 'icon-id-card-o') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-quora"';
			if (esc_attr($icon) == 'icon-quora') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-free-code-camp"';
			if (esc_attr($icon) == 'icon-free-code-camp') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-telegram"';
			if (esc_attr($icon) == 'icon-telegram') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-thermometer"';
			if (esc_attr($icon) == 'icon-thermometer') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-thermometer-3"';
			if (esc_attr($icon) == 'icon-thermometer-3') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-thermometer-2"';
			if (esc_attr($icon) == 'icon-thermometer-2') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-thermometer-quarter"';
			if (esc_attr($icon) == 'icon-thermometer-quarter') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-window-minimize"';
			if (esc_attr($icon) == 'icon-window-minimize') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-window-restore"';
			if (esc_attr($icon) == 'icon-window-restore') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-window-close"';
			if (esc_attr($icon) == 'icon-window-close') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-window-close-o"';
			if (esc_attr($icon) == 'icon-window-close-o') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-bandcamp"';
			if (esc_attr($icon) == 'icon-bandcamp') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-grav"';
			if (esc_attr($icon) == 'icon-grav') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-etsy"';
			if (esc_attr($icon) == 'icon-etsy') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-imdb"';
			if (esc_attr($icon) == 'icon-imdb') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-ravelry"';
			if (esc_attr($icon) == 'icon-ravelry') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-eercast"';
			if (esc_attr($icon) == 'icon-eercast') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-microchip"';
			if (esc_attr($icon) == 'icon-microchip') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-snowflake-o"';
			if (esc_attr($icon) == 'icon-snowflake-o') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-superpowers"';
			if (esc_attr($icon) == 'icon-superpowers') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-wpexplorer"';
			if (esc_attr($icon) == 'icon-wpexplorer') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-meetup"';
			if (esc_attr($icon) == 'icon-meetup') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-users"';
			if (esc_attr($icon) == 'icon-users') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-male"';
			if (esc_attr($icon) == 'icon-male') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-female"';
			if (esc_attr($icon) == 'icon-female') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-child"';
			if (esc_attr($icon) == 'icon-child') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-user-secret"';
			if (esc_attr($icon) == 'icon-user-secret') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-plus"';
			if (esc_attr($icon) == 'icon-plus') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-plus-circled"';
			if (esc_attr($icon) == 'icon-plus-circled') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-plus-squared"';
			if (esc_attr($icon) == 'icon-plus-squared') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-plus-squared-alt"';
			if (esc_attr($icon) == 'icon-plus-squared-alt') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-minus"';
			if (esc_attr($icon) == 'icon-minus') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-lock-open-alt"';
			if (esc_attr($icon) == 'icon-lock-open-alt') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-pin"';
			if (esc_attr($icon) == 'icon-pin') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-eye"';
			if (esc_attr($icon) == 'icon-eye') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-eye-off"';
			if (esc_attr($icon) == 'icon-eye-off') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-tag"';
			if (esc_attr($icon) == 'icon-tag') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-reply-all"';
			if (esc_attr($icon) == 'icon-reply-all') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-forward"';
			if (esc_attr($icon) == 'icon-forward') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-quote-left"';
			if (esc_attr($icon) == 'icon-quote-left') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-quote-right"';
			if (esc_attr($icon) == 'icon-quote-right') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-code"';
			if (esc_attr($icon) == 'icon-code') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-bell"';
			if (esc_attr($icon) == 'icon-bell') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-bell-alt"';
			if (esc_attr($icon) == 'icon-bell-alt') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-bell-off"';
			if (esc_attr($icon) == 'icon-bell-off') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-bell-off-empty"';
			if (esc_attr($icon) == 'icon-bell-off-empty') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-attention-alt"';
			if (esc_attr($icon) == 'icon-attention-alt') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-file-powerpoint"';
			if (esc_attr($icon) == 'icon-file-powerpoint') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-file-image"';
			if (esc_attr($icon) == 'icon-file-image') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-file-archive"';
			if (esc_attr($icon) == 'icon-file-archive') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-file-audio"';
			if (esc_attr($icon) == 'icon-file-audio') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-file-video"';
			if (esc_attr($icon) == 'icon-file-video') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-sliders"';
			if (esc_attr($icon) == 'icon-sliders') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-basket"';
			if (esc_attr($icon) == 'icon-basket') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-cart-plus"';
			if (esc_attr($icon) == 'icon-cart-plus') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-cart-arrow-down"';
			if (esc_attr($icon) == 'icon-cart-arrow-down') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-calendar"';
			if (esc_attr($icon) == 'icon-calendar') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-resize-vertical"';
			if (esc_attr($icon) == 'icon-resize-vertical') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-resize-horizontal"';
			if (esc_attr($icon) == 'icon-resize-horizontal') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-move"';
			if (esc_attr($icon) == 'icon-move') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-zoom-in"';
			if (esc_attr($icon) == 'icon-zoom-in') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-zoom-out"';
			if (esc_attr($icon) == 'icon-zoom-out') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-angle-down"';
			if (esc_attr($icon) == 'icon-angle-down') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-angle-circled-left"';
			if (esc_attr($icon) == 'icon-angle-circled-left') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-angle-circled-right"';
			if (esc_attr($icon) == 'icon-angle-circled-right') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-angle-circled-up"';
			if (esc_attr($icon) == 'icon-angle-circled-up') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-angle-circled-down"';
			if (esc_attr($icon) == 'icon-angle-circled-down') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-down-hand"';
			if (esc_attr($icon) == 'icon-down-hand') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-left-circled"';
			if (esc_attr($icon) == 'icon-left-circled') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-right-circled"';
			if (esc_attr($icon) == 'icon-right-circled') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-up-circled"';
			if (esc_attr($icon) == 'icon-up-circled') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-down-circled"';
			if (esc_attr($icon) == 'icon-down-circled') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-stop"';
			if (esc_attr($icon) == 'icon-stop') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-pause"';
			if (esc_attr($icon) == 'icon-pause') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-to-end"';
			if (esc_attr($icon) == 'icon-to-end') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-to-end-alt"';
			if (esc_attr($icon) == 'icon-to-end-alt') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-to-start"';
			if (esc_attr($icon) == 'icon-to-start') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-cloud"';
			if (esc_attr($icon) == 'icon-cloud') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-flash"';
			if (esc_attr($icon) == 'icon-flash') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-moon"';
			if (esc_attr($icon) == 'icon-moon') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-umbrella"';
			if (esc_attr($icon) == 'icon-umbrella') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-flight"';
			if (esc_attr($icon) == 'icon-flight') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-align-right"';
			if (esc_attr($icon) == 'icon-align-right') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-align-justify"';
			if (esc_attr($icon) == 'icon-align-justify') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-list"';
			if (esc_attr($icon) == 'icon-list') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-indent-left"';
			if (esc_attr($icon) == 'icon-indent-left') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-indent-right"';
			if (esc_attr($icon) == 'icon-indent-right') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-off"';
			if (esc_attr($icon) == 'icon-off') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-road"';
			if (esc_attr($icon) == 'icon-road') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-list-alt"';
			if (esc_attr($icon) == 'icon-list-alt') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-qrcode"';
			if (esc_attr($icon) == 'icon-qrcode') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-barcode"';
			if (esc_attr($icon) == 'icon-barcode') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-magnet"';
			if (esc_attr($icon) == 'icon-magnet') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-chart-bar"';
			if (esc_attr($icon) == 'icon-chart-bar') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-chart-area"';
			if (esc_attr($icon) == 'icon-chart-area') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-chart-pie"';
			if (esc_attr($icon) == 'icon-chart-pie') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-chart-line"';
			if (esc_attr($icon) == 'icon-chart-line') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-taxi"';
			if (esc_attr($icon) == 'icon-taxi') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-truck"';
			if (esc_attr($icon) == 'icon-truck') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-bus"';
			if (esc_attr($icon) == 'icon-bus') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-bicycle"';
			if (esc_attr($icon) == 'icon-bicycle') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-motorcycle"';
			if (esc_attr($icon) == 'icon-motorcycle') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-sort"';
			if (esc_attr($icon) == 'icon-sort') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-sort-down"';
			if (esc_attr($icon) == 'icon-sort-down') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-sort-up"';
			if (esc_attr($icon) == 'icon-sort-up') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-sort-alt-up"';
			if (esc_attr($icon) == 'icon-sort-alt-up') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-sort-alt-down"';
			if (esc_attr($icon) == 'icon-sort-alt-down') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-medkit"';
			if (esc_attr($icon) == 'icon-medkit') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-h-sigh"';
			if (esc_attr($icon) == 'icon-h-sigh') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-bed"';
			if (esc_attr($icon) == 'icon-bed') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-hospital"';
			if (esc_attr($icon) == 'icon-hospital') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-building"';
			if (esc_attr($icon) == 'icon-building') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-paw"';
			if (esc_attr($icon) == 'icon-paw') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-spoon"';
			if (esc_attr($icon) == 'icon-spoon') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-cube"';
			if (esc_attr($icon) == 'icon-cube') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-cubes"';
			if (esc_attr($icon) == 'icon-cubes') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-recycle"';
			if (esc_attr($icon) == 'icon-recycle') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-eyedropper"';
			if (esc_attr($icon) == 'icon-eyedropper') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-brush"';
			if (esc_attr($icon) == 'icon-brush') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-birthday"';
			if (esc_attr($icon) == 'icon-birthday') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-diamond"';
			if (esc_attr($icon) == 'icon-diamond') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-street-view"';
			if (esc_attr($icon) == 'icon-street-view') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-cc-amex"';
			if (esc_attr($icon) == 'icon-cc-amex') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-cc-paypal"';
			if (esc_attr($icon) == 'icon-cc-paypal') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-cc-stripe"';
			if (esc_attr($icon) == 'icon-cc-stripe') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-adn"';
			if (esc_attr($icon) == 'icon-adn') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-android"';
			if (esc_attr($icon) == 'icon-android') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-dribbble"';
			if (esc_attr($icon) == 'icon-dribbble') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-dropbox"';
			if (esc_attr($icon) == 'icon-dropbox') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-drupal"';
			if (esc_attr($icon) == 'icon-drupal') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-facebook"';
			if (esc_attr($icon) == 'icon-facebook') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-facebook-squared"';
			if (esc_attr($icon) == 'icon-facebook-squared') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-html5"';
			if (esc_attr($icon) == 'icon-html5') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-instagram"';
			if (esc_attr($icon) == 'icon-instagram') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-ioxhost"';
			if (esc_attr($icon) == 'icon-ioxhost') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-joomla"';
			if (esc_attr($icon) == 'icon-joomla') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-jsfiddle"';
			if (esc_attr($icon) == 'icon-jsfiddle') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-pinterest-squared"';
			if (esc_attr($icon) == 'icon-pinterest-squared') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-qq"';
			if (esc_attr($icon) == 'icon-qq') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-reddit"';
			if (esc_attr($icon) == 'icon-reddit') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-reddit-squared"';
			if (esc_attr($icon) == 'icon-reddit-squared') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-renren"';
			if (esc_attr($icon) == 'icon-renren') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-tencent-weibo"';
			if (esc_attr($icon) == 'icon-tencent-weibo') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-trello"';
			if (esc_attr($icon) == 'icon-trello') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-tumblr"';
			if (esc_attr($icon) == 'icon-tumblr') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-tumblr-squared"';
			if (esc_attr($icon) == 'icon-tumblr-squared') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-twitch"';
			if (esc_attr($icon) == 'icon-twitch') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-youtube-squared"';
			if (esc_attr($icon) == 'icon-youtube-squared') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-youtube-play"';
			if (esc_attr($icon) == 'icon-youtube-play') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-blank"';
			if (esc_attr($icon) == 'icon-blank') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-lemon"';
			if (esc_attr($icon) == 'icon-lemon') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-genderless"';
			if (esc_attr($icon) == 'icon-genderless') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-cc-jcb"';
			if (esc_attr($icon) == 'icon-cc-jcb') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-cc-diners-club"';
			if (esc_attr($icon) == 'icon-cc-diners-club') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-clone"';
			if (esc_attr($icon) == 'icon-clone') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-balance-scale"';
			if (esc_attr($icon) == 'icon-balance-scale') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-hourglass-o"';
			if (esc_attr($icon) == 'icon-hourglass-o') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-gg-circle"';
			if (esc_attr($icon) == 'icon-gg-circle') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-tripadvisor"';
			if (esc_attr($icon) == 'icon-tripadvisor') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-odnoklassniki"';
			if (esc_attr($icon) == 'icon-odnoklassniki') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-odnoklassniki-square"';
			if (esc_attr($icon) == 'icon-odnoklassniki-square') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-get-pocket"';
			if (esc_attr($icon) == 'icon-get-pocket') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-map-pin"';
			if (esc_attr($icon) == 'icon-map-pin') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-map-signs"';
			if (esc_attr($icon) == 'icon-map-signs') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-map-o"';
			if (esc_attr($icon) == 'icon-map-o') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-map"';
			if (esc_attr($icon) == 'icon-map') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-commenting"';
			if (esc_attr($icon) == 'icon-commenting') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-pause-circle"';
			if (esc_attr($icon) == 'icon-pause-circle') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-pause-circle-o"';
			if (esc_attr($icon) == 'icon-pause-circle-o') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-stop-circle"';
			if (esc_attr($icon) == 'icon-stop-circle') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-stop-circle-o"';
			if (esc_attr($icon) == 'icon-stop-circle-o') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-shopping-bag"';
			if (esc_attr($icon) == 'icon-shopping-bag') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-braille"';
			if (esc_attr($icon) == 'icon-braille') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-assistive-listening-systems"';
			if (esc_attr($icon) == 'icon-assistive-listening-systems') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-american-sign-language-interpreting"';
			if (esc_attr($icon) == 'icon-american-sign-language-interpreting') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-asl-interpreting"';
			if (esc_attr($icon) == 'icon-asl-interpreting') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-glide"';
			if (esc_attr($icon) == 'icon-glide') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-envelope-open"';
			if (esc_attr($icon) == 'icon-envelope-open') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-envelope-open-o"';
			if (esc_attr($icon) == 'icon-envelope-open-o') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-linode"';
			if (esc_attr($icon) == 'icon-linode') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-address-book"';
			if (esc_attr($icon) == 'icon-address-book') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-address-book-o"';
			if (esc_attr($icon) == 'icon-address-book-o') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-thermometer-0"';
			if (esc_attr($icon) == 'icon-thermometer-0') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-shower"';
			if (esc_attr($icon) == 'icon-shower') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-bath"';
			if (esc_attr($icon) == 'icon-bath') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-podcast"';
			if (esc_attr($icon) == 'icon-podcast') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-window-maximize"';
			if (esc_attr($icon) == 'icon-window-maximize') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '</optgroup>';
			echo '<optgroup label="Fontelico">';
			echo '<option value="icon-emo-happy"';
			if (esc_attr($icon) == 'icon-emo-happy') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-emo-wink"';
			if (esc_attr($icon) == 'icon-emo-wink') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-emo-wink2"';
			if (esc_attr($icon) == 'icon-emo-wink2') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-emo-unhappy"';
			if (esc_attr($icon) == 'icon-emo-unhappy') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-emo-sleep"';
			if (esc_attr($icon) == 'icon-emo-sleep') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-emo-thumbsup"';
			if (esc_attr($icon) == 'icon-emo-thumbsup') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-emo-displeased"';
			if (esc_attr($icon) == 'icon-emo-displeased') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-emo-grin"';
			if (esc_attr($icon) == 'icon-emo-grin') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-emo-angry"';
			if (esc_attr($icon) == 'icon-emo-angry') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-emo-saint"';
			if (esc_attr($icon) == 'icon-emo-saint') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-emo-cry"';
			if (esc_attr($icon) == 'icon-emo-cry') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-emo-squint"';
			if (esc_attr($icon) == 'icon-emo-squint') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-emo-laugh"';
			if (esc_attr($icon) == 'icon-emo-laugh') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-firefox-1"';
			if (esc_attr($icon) == 'icon-firefox-1') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-chrome-1"';
			if (esc_attr($icon) == 'icon-chrome-1') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-opera-1"';
			if (esc_attr($icon) == 'icon-opera-1') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-ie"';
			if (esc_attr($icon) == 'icon-ie') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-crown"';
			if (esc_attr($icon) == 'icon-crown') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-crown-plus"';
			if (esc_attr($icon) == 'icon-crown-plus') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-crown-minus"';
			if (esc_attr($icon) == 'icon-crown-minus') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-marquee"';
			if (esc_attr($icon) == 'icon-marquee') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-emo-devil"';
			if (esc_attr($icon) == 'icon-emo-devil') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-emo-surprised"';
			if (esc_attr($icon) == 'icon-emo-surprised') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-emo-tongue"';
			if (esc_attr($icon) == 'icon-emo-tongue') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-emo-coffee"';
			if (esc_attr($icon) == 'icon-emo-coffee') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-emo-sunglasses"';
			if (esc_attr($icon) == 'icon-emo-sunglasses') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '</optgroup>';
			echo '<optgroup label="Maki">';
			echo '<option value="icon-aboveground-rail"';
			if (esc_attr($icon) == 'icon-aboveground-rail') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-airfield"';
			if (esc_attr($icon) == 'icon-airfield') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-airport"';
			if (esc_attr($icon) == 'icon-airport') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-art-gallery"';
			if (esc_attr($icon) == 'icon-art-gallery') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-bar"';
			if (esc_attr($icon) == 'icon-bar') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-baseball"';
			if (esc_attr($icon) == 'icon-baseball') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-basketball"';
			if (esc_attr($icon) == 'icon-basketball') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-beer-2"';
			if (esc_attr($icon) == 'icon-beer-2') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-belowground-rail"';
			if (esc_attr($icon) == 'icon-belowground-rail') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-bicycle-1"';
			if (esc_attr($icon) == 'icon-bicycle-1') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-bus-1"';
			if (esc_attr($icon) == 'icon-bus-1') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-cafe"';
			if (esc_attr($icon) == 'icon-cafe') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-campsite"';
			if (esc_attr($icon) == 'icon-campsite') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-cemetery"';
			if (esc_attr($icon) == 'icon-cemetery') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-cinema"';
			if (esc_attr($icon) == 'icon-cinema') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-college"';
			if (esc_attr($icon) == 'icon-college') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-commerical-building"';
			if (esc_attr($icon) == 'icon-commerical-building') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-credit-card-2"';
			if (esc_attr($icon) == 'icon-credit-card-2') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-cricket"';
			if (esc_attr($icon) == 'icon-cricket') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-embassy"';
			if (esc_attr($icon) == 'icon-embassy') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-fast-food"';
			if (esc_attr($icon) == 'icon-fast-food') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-ferry"';
			if (esc_attr($icon) == 'icon-ferry') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-fire-station"';
			if (esc_attr($icon) == 'icon-fire-station') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-football"';
			if (esc_attr($icon) == 'icon-football') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-fuel"';
			if (esc_attr($icon) == 'icon-fuel') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-garden"';
			if (esc_attr($icon) == 'icon-garden') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-giraffe"';
			if (esc_attr($icon) == 'icon-giraffe') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-golf"';
			if (esc_attr($icon) == 'icon-golf') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-grocery-store"';
			if (esc_attr($icon) == 'icon-grocery-store') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-harbor"';
			if (esc_attr($icon) == 'icon-harbor') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-heliport"';
			if (esc_attr($icon) == 'icon-heliport') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-hospital-"';
			if (esc_attr($icon) == 'icon-hospital-1') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-industrial-building"';
			if (esc_attr($icon) == 'icon-industrial-building') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-library"';
			if (esc_attr($icon) == 'icon-library') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-lodging"';
			if (esc_attr($icon) == 'icon-lodging') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-london-underground"';
			if (esc_attr($icon) == 'icon-london-underground') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-minefield"';
			if (esc_attr($icon) == 'icon-minefield') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-monument"';
			if (esc_attr($icon) == 'icon-monument') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-museum"';
			if (esc_attr($icon) == 'icon-museum') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-pharmacy"';
			if (esc_attr($icon) == 'icon-pharmacy') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-pitch"';
			if (esc_attr($icon) == 'icon-pitch') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-police"';
			if (esc_attr($icon) == 'icon-police') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-post"';
			if (esc_attr($icon) == 'icon-post') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-prison"';
			if (esc_attr($icon) == 'icon-prison') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-rail"';
			if (esc_attr($icon) == 'icon-rail') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-religious-christian"';
			if (esc_attr($icon) == 'icon-religious-christian') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-religious-islam"';
			if (esc_attr($icon) == 'icon-religious-islam') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-religious-jewis"';
			if (esc_attr($icon) == 'icon-religious-jewish') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-restaurant"';
			if (esc_attr($icon) == 'icon-restaurant') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-roadblock"';
			if (esc_attr($icon) == 'icon-roadblock') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-school"';
			if (esc_attr($icon) == 'icon-school') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-shop"';
			if (esc_attr($icon) == 'icon-shop') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-skiing"';
			if (esc_attr($icon) == 'icon-skiing') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-soccer"';
			if (esc_attr($icon) == 'icon-soccer') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-swimming"';
			if (esc_attr($icon) == 'icon-swimming') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-tennis"';
			if (esc_attr($icon) == 'icon-tennis') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-theatre"';
			if (esc_attr($icon) == 'icon-theatre') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-toilet"';
			if (esc_attr($icon) == 'icon-toilet') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-town-hall"';
			if (esc_attr($icon) == 'icon-town-hall') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-trash-2"';
			if (esc_attr($icon) == 'icon-trash-2') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-tree-2"';
			if (esc_attr($icon) == 'icon-tree-2') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-tree-3"';
			if (esc_attr($icon) == 'icon-tree-3') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-warehouse"';
			if (esc_attr($icon) == 'icon-warehouse') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '</optgroup>';
			echo '<optgroup label="Meteocons">';	
			echo '<option value="icon-windy-rain-inv"';
			if (esc_attr($icon) == 'icon-windy-rain-inv') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-snow-inv"';
			if (esc_attr($icon) == 'icon-snow-inv') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-snow-heavy-inv"';
			if (esc_attr($icon) == 'icon-snow-heavy-inv') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-hail-inv"';
			if (esc_attr($icon) == 'icon-hail-inv') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-clouds-inv"';
			if (esc_attr($icon) == 'icon-clouds-inv') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-clouds-flash-inv"';
			if (esc_attr($icon) == 'icon-clouds-flash-inv') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-temperature"';
			if (esc_attr($icon) == 'icon-temperature') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-windy-inv"';
			if (esc_attr($icon) == 'icon-windy-inv') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-sunrise"';
			if (esc_attr($icon) == 'icon-sunrise') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-sun-1"';
			if (esc_attr($icon) == 'icon-sun-1') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-moon-1"';
			if (esc_attr($icon) == 'icon-moon-1') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-eclipse"';
			if (esc_attr($icon) == 'icon-eclipse') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-mist"';
			if (esc_attr($icon) == 'icon-mist') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-wind"';
			if (esc_attr($icon) == 'icon-wind') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-windy-rain"';
			if (esc_attr($icon) == 'icon-windy-rain') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-snow"';
			if (esc_attr($icon) == 'icon-snow') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-snow-alt"';
			if (esc_attr($icon) == 'icon-snow-alt') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-snow-heavy"';
			if (esc_attr($icon) == 'icon-snow-heavy') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-hail"';
			if (esc_attr($icon) == 'icon-hail') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-clouds"';
			if (esc_attr($icon) == 'icon-clouds') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-clouds-flash"';
			if (esc_attr($icon) == 'icon-clouds-flash') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-compass-1"';
			if (esc_attr($icon) == 'icon-compass-1') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-na"';
			if (esc_attr($icon) == 'icon-na') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-celcius"';
			if (esc_attr($icon) == 'icon-celcius') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-fahrenheit"';
			if (esc_attr($icon) == 'icon-fahrenheit') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-clouds-flash-alt"';
			if (esc_attr($icon) == 'icon-clouds-flash-alt') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-sun-inv"';
			if (esc_attr($icon) == 'icon-sun-inv') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-moon-inv"';
			if (esc_attr($icon) == 'icon-moon-inv') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-cloud-sun-inv"';
			if (esc_attr($icon) == 'icon-cloud-sun-inv') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-cloud-moon-inv"';
			if (esc_attr($icon) == 'icon-cloud-moon-inv') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-cloud-inv"';
			if (esc_attr($icon) == 'icon-cloud-inv') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-cloud-flash-inv"';
			if (esc_attr($icon) == 'icon-cloud-flash-inv') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-drizzle-inv"';
			if (esc_attr($icon) == 'icon-drizzle-inv') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-rain-inv"';
			if (esc_attr($icon) == 'icon-rain-inv') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-snowflake"';
			if (esc_attr($icon) == 'icon-snowflake') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-cloud-sun"';
			if (esc_attr($icon) == 'icon-cloud-sun') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-cloud-moon"';
			if (esc_attr($icon) == 'icon-cloud-moon') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-fog-sun"';
			if (esc_attr($icon) == 'icon-fog-sun') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-fog-moon"';
			if (esc_attr($icon) == 'icon-fog-moon') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-fog-cloud"';
			if (esc_attr($icon) == 'icon-fog-cloud') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-fog"';
			if (esc_attr($icon) == 'icon-fog') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-cloud-1"';
			if (esc_attr($icon) == 'icon-cloud-1') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-cloud-flash"';
			if (esc_attr($icon) == 'icon-cloud-flash') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-cloud-flash-alt"';
			if (esc_attr($icon) == 'icon-cloud-flash-alt') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-drizzle"';
			if (esc_attr($icon) == 'icon-drizzle') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-rain"';
			if (esc_attr($icon) == 'icon-rain') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '<option value="icon-windy"';
			if (esc_attr($icon) == 'icon-windy') {
				echo ' selected ';
			}	
			echo  '></option>';
			echo '</optgroup>';
			echo '</select>';
			echo '</p>';

			// text
			echo '<p>';
			echo '<label for="' . $this->get_field_name( 'text' ) . '">Text:</label>';
			echo '<textarea id="' . $this->get_field_id( 'text' ) . '" class="widefat" name="' . $this->get_field_name( 'text' ) . '">' . esc_html($text) . '</textarea>';
			echo '<span class="input_description">Concise statement about the focus of the block. Some HTML, such as links, is allowed.</span>';
			echo '</p>';

			// button text
			echo '<p>';
			echo '<label for="' . $this->get_field_name( 'button_text' ) . '">Button Text: </label>';
			echo '<input id="' . $this->get_field_id( 'button_text' ) . '" class="widefat" type="text" name="' . $this->get_field_name( 'button_text' ) . '" value="' . esc_attr($button_text) . '"/>';
			echo '<span class="input_description">If you leave this blank, the button text will default to "Learn More".</span>';
			echo '</p>';

			// display the page link
			echo '<p>';
			echo '<label for="' . $this->get_field_name( 'page' ) . '">Select page for this item to link to</label>';
			echo '<div class="widefat ecu-profile-page-select-div" ><select id="' . $this->get_field_id( 'page' ) . '" class="widefat ecu-profile-page-select" name="' . $this->get_field_name( 'page' ) . '">';
			// Prepare the options for the select box
			echo '<option value="">Do not link to a page</option>';
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
			echo "<script type='text/javascript'>\n";
			echo "jQuery(document).ready(function($) {\n";
			echo "$('#" . $this->get_field_id( 'page' ) . "').select2({width: 'element'});\n";
			echo "})</script>\n";

			// button url
			echo '<p>';
			echo '<label for="' . $this->get_field_name( 'button_url' ) . '">Button URL (Overrides the "Select a page" option above):</label>';
			echo '<input id="' . $this->get_field_id( 'button_url' ) . '" class="widefat" type="text" name="' . $this->get_field_name( 'button_url' ) . '" value="' . esc_attr($button_url) . '"/>';
			echo '<span class="input_description">If you leave this blank, the button will be hidden.</span>';
			echo '</p>';

			// icon color
			echo '<p>';
			echo '<label for="' . $this->get_field_name( 'icon_color' ) . '">Icon Color:</label>';
			echo '<input id="' . $this->get_field_id( 'icon_color' ) . '" class="widefat" type="text" name="' . $this->get_field_name( 'icon_color' ) . '" value="' . esc_attr($icon_color) . '"/>';
			echo '</p>';

			// icon size
			echo '<p>';
			echo '<label for="' . $this->get_field_name( 'icon_size' ) . '">Icon Size:</label>';
			echo '<select id="' . $this->get_field_id( 'icon_size' ) . '" class="widefat" name="' . $this->get_field_name( 'icon_size' ) . '">';
			echo '<option value="4em"';
			if (esc_attr($icon_size) == '4em') {
				echo ' selected ';
			}
			echo '>X-Large</option>';
			echo '<option value="3em"';
			if (esc_attr($icon_size) == '3em') {
				echo ' selected ';
			}
			echo '>Large</option>';
			echo '<option value="2em"';
			if (esc_attr($icon_size) == '2em') {
				echo ' selected ';
			}
			echo '>Medium</option>';
			echo '<option value="1em"';
			if (esc_attr($icon_size) == '1em') {
				echo ' selected ';
			}
			echo '>Small</option>';
			echo '</select>';
			echo '</p>';
		}
	}
