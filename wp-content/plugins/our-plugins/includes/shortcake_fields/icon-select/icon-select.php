<?php
	namespace Ecu_Plugins;

	/**
	 * Icon Font Picker.   Allows for categories and searching.
	 */
	class Ecu_Icon_Select extends Abstract_Ecu_Field  {

	    /**
	     * Returns the instance
	     *
	     * @var string $instance The single instance for the field.
	     */
		protected static $instance;

		/**
		 * Setup the field.
		 */
		public function initialize(){

			add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));

			if ( is_admin() ) {

				add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_shortcode_ui' ) );
				add_editor_style( plugin_dir_url( __FILE__ ) . 'fontello/css/fontello.css' );
			}

			parent::initialize();
        }

		/**
	     * Enqueueues the necessary css
		 */
        public function enqueue_styles(){
             wp_register_style( 'ecu-fontello-fonts', plugin_dir_url( __FILE__ ) . 'fontello/css/fontello.css' );
        }

		/**
		 * Return the instance.
		 *
		 * Since an abstract class cannot initialize itself you will have to add the following
		 * function to your class.
		 *
		 * @return Shortcode_UI_Field_Term_Select
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self;
				self::$instance->initialize();
			}
			return self::$instance;
		}

		/**
		 * Load JS, CSS, and fonts.
		 */
		public function enqueue_shortcode_ui($hook) {
			// Was breaking the icons on the wp smush plugin admin screen
			if('media_page_wp-smush-bulk' !== $hook) {
				wp_enqueue_script( 'ecu-icon-select-fonticonpicker', plugin_dir_url( __FILE__ ) . 'js/jquery.fonticonpicker.min.js', array( 'jquery' ) );
				wp_enqueue_style( 'ecu-icon-select-fonticonpicker', plugin_dir_url( __FILE__ ) . 'css/jquery.fonticonpicker.min.css' );
				wp_enqueue_style( 'ecu-icon-select-fontello', plugin_dir_url( __FILE__ ) . 'fontello/css/fontello.css' );
			}
		}

		/**
		 * Add the field to the shortcode fields.
		 *
		 * @param $fields
		 * @return array
		 */
		public function filter_shortcode_ui_fields( $fields ) {
			$fields['ecu-icon-select'] = array(
				'template' => 'ecu-shortcode-ui-field-icon-select',
			);
			return $fields;
		}

		/**
		 * Output template used by post select field.
	     */
		public function shortcode_ui_loaded_editor() {
			//@formatter:off
			?>
			<script type="text/html" id="tmpl-ecu-shortcode-ui-field-icon-select">
				<div class="field-block shortcode-ui-field-text shortcode-ui-attribute-{{ data.attr }}">
					<label for="{{ data.id }}">{{{ data.label }}}</label>
					<input type="text" class="regular-text" name="{{ data.attr }}" id="{{ data.id }}" value="{{ data.value }}" {{{ data.meta }}}/>
					<# if ( typeof data.description == 'string' && data.description.length ) { #>
						<p class="description">{{{ data.description }}}</p>
					<# } #>
				</div>
				<!-- JavaScript -->
				<script type="text/javascript">
				    // Make sure to fire only when the DOM is ready
				    jQuery(document).ready(function($) {
					    var sourceIcons = {
							'Font Awesome' : [
								'icon-glass',
								'icon-music',
								'icon-search',
								'icon-mail',
								'icon-mail-alt',
								'icon-mail-squared',
								'icon-heart',
								'icon-heart-empty',
								'icon-star',
								'icon-star-empty',
								'icon-star-half',
								'icon-star-half-alt',
								'icon-user',
								'icon-user-plus',
								'icon-user-times',
								'icon-video',
								'icon-videocam',
								'icon-picture',
								'icon-camera',
								'icon-camera-alt',
								'icon-th-large',
								'icon-th',
								'icon-th-list',
								'icon-ok',
								'icon-ok-circled',
								'icon-ok-circled2',
								'icon-ok-squared',
								'icon-cancel',
								'icon-cancel-circled',
								'icon-cancel-circled2',
								'icon-minus-circled',
								'icon-minus-squared',
								'icon-minus-squared-alt',
								'icon-help',
								'icon-help-circled',
								'icon-info-circled',
								'icon-info',
								'icon-home',
								'icon-link',
								'icon-unlink',
								'icon-link-ext',
								'icon-link-ext-alt',
								'icon-attach',
								'icon-lock',
								'icon-lock-open',
								'icon-tags',
								'icon-bookmark',
								'icon-bookmark-empty',
								'icon-flag',
								'icon-flag-empty',
								'icon-flag-checkered',
								'icon-thumbs-up',
								'icon-thumbs-down',
								'icon-thumbs-up-alt',
								'icon-thumbs-down-alt',
								'icon-download',
								'icon-upload',
								'icon-download-cloud',
								'icon-upload-cloud',
								'icon-reply',
								'icon-export',
								'icon-export-alt',
								'icon-share',
								'icon-share-squared',
								'icon-pencil',
								'icon-pencil-squared',
								'icon-edit',
								'icon-print',
								'icon-retweet',
								'icon-keyboard',
								'icon-gamepad',
								'icon-comment',
								'icon-chat',
								'icon-comment-empty',
								'icon-chat-empty',
								'icon-attention',
								'icon-attention-circled',
								'icon-location',
								'icon-direction',
								'icon-compass',
								'icon-trash',
								'icon-trash-empty',
								'icon-doc',
								'icon-docs',
								'icon-doc-text',
								'icon-doc-inv',
								'icon-doc-text-inv',
								'icon-file-pdf',
								'icon-file-word',
								'icon-file-excel',
								'icon-file-code',
								'icon-folder',
								'icon-folder-open',
								'icon-folder-empty',
								'icon-folder-open-empty',
								'icon-box',
								'icon-rss',
								'icon-rss-squared',
								'icon-phone',
								'icon-phone-squared',
								'icon-fax',
								'icon-menu',
								'icon-cog',
								'icon-cog-alt',
								'icon-wrench',
								'icon-calendar-empty',
								'icon-login',
								'icon-logout',
								'icon-mic',
								'icon-mute',
								'icon-volume-off',
								'icon-volume-down',
								'icon-volume-up',
								'icon-headphones',
								'icon-clock',
								'icon-lightbulb',
								'icon-block',
								'icon-resize-full',
								'icon-resize-full-alt',
								'icon-resize-small',
								'icon-down-circled2',
								'icon-up-circled2',
								'icon-left-circled2',
								'icon-right-circled2',
								'icon-down-dir',
								'icon-up-dir',
								'icon-left-dir',
								'icon-right-dir',
								'icon-down-open',
								'icon-left-open',
								'icon-right-open',
								'icon-up-open',
								'icon-angle-left',
								'icon-angle-right',
								'icon-angle-up',
								'icon-angle-double-left',
								'icon-angle-double-right',
								'icon-angle-double-up',
								'icon-angle-double-down',
								'icon-down',
								'icon-left',
								'icon-right',
								'icon-up',
								'icon-down-big',
								'icon-left-big',
								'icon-right-big',
								'icon-up-big',
								'icon-right-hand',
								'icon-left-hand',
								'icon-up-hand',
								'icon-cw',
								'icon-ccw',
								'icon-arrows-cw',
								'icon-level-up',
								'icon-level-down',
								'icon-shuffle',
								'icon-exchange',
								'icon-history',
								'icon-expand',
								'icon-collapse',
								'icon-expand-right',
								'icon-collapse-left',
								'icon-play',
								'icon-play-circled',
								'icon-play-circled2',
								'icon-to-start-alt',
								'icon-fast-fw',
								'icon-fast-bw',
								'icon-eject',
								'icon-target',
								'icon-signal',
								'icon-wifi',
								'icon-award',
								'icon-desktop',
								'icon-laptop',
								'icon-tablet',
								'icon-mobile',
								'icon-inbox',
								'icon-globe',
								'icon-sun',
								'icon-fighter-jet',
								'icon-paper-plane',
								'icon-paper-plane-empty',
								'icon-space-shuttle',
								'icon-leaf',
								'icon-font',
								'icon-bold',
								'icon-medium',
								'icon-italic',
								'icon-header',
								'icon-paragraph',
								'icon-text-height',
								'icon-text-width',
								'icon-align-left',
								'icon-align-center',
								'icon-list-bullet',
								'icon-list-numbered',
								'icon-strike',
								'icon-underline',
								'icon-superscript',
								'icon-subscript',
								'icon-table',
								'icon-columns',
								'icon-crop',
								'icon-scissors',
								'icon-paste',
								'icon-briefcase',
								'icon-suitcase',
								'icon-ellipsis',
								'icon-ellipsis-vert',
								'icon-book',
								'icon-adjust',
								'icon-tint',
								'icon-toggle-off',
								'icon-toggle-on',
								'icon-check',
								'icon-check-empty',
								'icon-circle',
								'icon-circle-empty',
								'icon-circle-thin',
								'icon-circle-notch',
								'icon-dot-circled',
								'icon-asterisk',
								'icon-gift',
								'icon-fire',
								'icon-ticket',
								'icon-credit-card',
								'icon-floppy',
								'icon-megaphone',
								'icon-hdd',
								'icon-key',
								'icon-fork',
								'icon-rocket',
								'icon-bug',
								'icon-certificate',
								'icon-tasks',
								'icon-filter',
								'icon-beaker',
								'icon-magic',
								'icon-cab',
								'icon-train',
								'icon-subway',
								'icon-ship',
								'icon-money',
								'icon-euro',
								'icon-pound',
								'icon-dollar',
								'icon-rupee',
								'icon-yen',
								'icon-rouble',
								'icon-shekel',
								'icon-try',
								'icon-won',
								'icon-bitcoin',
								'icon-viacoin',
								'icon-sort-name-up',
								'icon-sort-name-down',
								'icon-sort-number-up',
								'icon-sort-number-down',
								'icon-hammer',
								'icon-gauge',
								'icon-sitemap',
								'icon-spinner',
								'icon-coffee',
								'icon-food',
								'icon-beer',
								'icon-user-md',
								'icon-stethoscope',
								'icon-heartbeat',
								'icon-ambulance',
								'icon-building-filled',
								'icon-bank',
								'icon-smile',
								'icon-frown',
								'icon-meh',
								'icon-anchor',
								'icon-terminal',
								'icon-eraser',
								'icon-puzzle',
								'icon-shield',
								'icon-extinguisher',
								'icon-bullseye',
								'icon-wheelchair',
								'icon-language',
								'icon-graduation-cap',
								'icon-tree',
								'icon-database',
								'icon-server',
								'icon-lifebuoy',
								'icon-rebel',
								'icon-empire',
								'icon-bomb',
								'icon-soccer-ball',
								'icon-tty',
								'icon-binoculars',
								'icon-plug',
								'icon-newspaper',
								'icon-calc',
								'icon-copyright',
								'icon-at',
								'icon-venus',
								'icon-mars',
								'icon-mercury',
								'icon-transgender',
								'icon-transgender-alt',
								'icon-venus-double',
								'icon-mars-double',
								'icon-venus-mars',
								'icon-mars-stroke',
								'icon-mars-stroke-v',
								'icon-mars-stroke-h',
								'icon-neuter',
								'icon-cc-visa',
								'icon-cc-mastercard',
								'icon-cc-discover',
								'icon-angellist',
								'icon-apple',
								'icon-behance',
								'icon-behance-squared',
								'icon-bitbucket',
								'icon-bitbucket-squared',
								'icon-buysellads',
								'icon-cc',
								'icon-codeopen',
								'icon-connectdevelop',
								'icon-css3',
								'icon-dashcube',
								'icon-delicious',
								'icon-deviantart',
								'icon-digg',
								'icon-facebook-official',
								'icon-flickr',
								'icon-forumbee',
								'icon-foursquare',
								'icon-git-squared',
								'icon-git',
								'icon-github',
								'icon-github-squared',
								'icon-github-circled',
								'icon-gittip',
								'icon-google',
								'icon-gplus',
								'icon-gplus-squared',
								'icon-gwallet',
								'icon-hacker-news',
								'icon-lastfm',
								'icon-lastfm-squared',
								'icon-leanpub',
								'icon-linkedin-squared',
								'icon-linux',
								'icon-linkedin',
								'icon-maxcdn',
								'icon-meanpath',
								'icon-openid',
								'icon-pagelines',
								'icon-paypal',
								'icon-pied-piper-squared',
								'icon-pied-piper-alt',
								'icon-pinterest',
								'icon-pinterest-circled',
								'icon-sellsy',
								'icon-shirtsinbulk',
								'icon-simplybuilt',
								'icon-skyatlas',
								'icon-skype',
								'icon-slack',
								'icon-slideshare',
								'icon-soundcloud',
								'icon-spotify',
								'icon-stackexchange',
								'icon-stackoverflow',
								'icon-steam',
								'icon-steam-squared',
								'icon-stumbleupon',
								'icon-stumbleupon-circled',
								'icon-twitter-squared',
								'icon-twitter',
								'icon-vimeo-squared',
								'icon-vine',
								'icon-vkontakte',
								'icon-whatsapp',
								'icon-wechat',
								'icon-weibo',
								'icon-windows',
								'icon-wordpress',
								'icon-xing',
								'icon-xing-squared',
								'icon-yelp',
								'icon-youtube',
								'icon-yahoo',
								'icon-y-combinator',
								'icon-optin-monster',
								'icon-opencart',
								'icon-expeditedssl',
								'icon-battery-4',
								'icon-battery-3',
								'icon-battery-2',
								'icon-battery-1',
								'icon-battery-0',
								'icon-mouse-pointer',
								'icon-i-cursor',
								'icon-object-group',
								'icon-object-ungroup',
								'icon-sticky-note',
								'icon-sticky-note-o',
								'icon-hourglass-1',
								'icon-hourglass-2',
								'icon-hourglass-3',
								'icon-hourglass',
								'icon-hand-grab-o',
								'icon-hand-paper-o',
								'icon-hand-scissors-o',
								'icon-hand-lizard-o',
								'icon-hand-spock-o',
								'icon-hand-pointer-o',
								'icon-hand-peace-o',
								'icon-trademark',
								'icon-registered',
								'icon-creative-commons',
								'icon-gg',
								'icon-wikipedia-w',
								'icon-safari',
								'icon-chrome',
								'icon-firefox',
								'icon-opera',
								'icon-internet-explorer',
								'icon-television',
								'icon-contao',
								'icon-500px',
								'icon-amazon',
								'icon-calendar-plus-o',
								'icon-calendar-minus-o',
								'icon-calendar-times-o',
								'icon-calendar-check-o',
								'icon-industry',
								'icon-commenting-o',
								'icon-houzz',
								'icon-vimeo',
								'icon-black-tie',
								'icon-fonticons',
								'icon-reddit-alien',
								'icon-edge',
								'icon-credit-card-alt',
								'icon-codiepie',
								'icon-modx',
								'icon-fort-awesome',
								'icon-usb',
								'icon-product-hunt',
								'icon-mixcloud',
								'icon-scribd',
								'icon-shopping-basket',
								'icon-hashtag',
								'icon-bluetooth',
								'icon-bluetooth-b',
								'icon-percent',
								'icon-gitlab',
								'icon-wpbeginner',
								'icon-wpforms',
								'icon-envira',
								'icon-universal-access',
								'icon-wheelchair-alt',
								'icon-question-circle-o',
								'icon-blind',
								'icon-audio-description',
								'icon-volume-control-phone',
								'icon-glide-g',
								'icon-sign-language',
								'icon-low-vision',
								'icon-viadeo',
								'icon-viadeo-square',
								'icon-snapchat',
								'icon-snapchat-ghost',
								'icon-snapchat-square',
								'icon-pied-piper',
								'icon-first-order',
								'icon-yoast',
								'icon-themeisle',
								'icon-google-plus-circle',
								'icon-font-awesome',
								'icon-handshake-o',
								'icon-address-card',
								'icon-address-card-o',
								'icon-user-circle',
								'icon-user-circle-o',
								'icon-user-o',
								'icon-id-badge',
								'icon-id-card',
								'icon-id-card-o',
								'icon-quora',
								'icon-free-code-camp',
								'icon-telegram',
								'icon-thermometer',
								'icon-thermometer-3',
								'icon-thermometer-2',
								'icon-thermometer-quarter',
								'icon-window-minimize',
								'icon-window-restore',
								'icon-window-close',
								'icon-window-close-o',
								'icon-bandcamp',
								'icon-grav',
								'icon-etsy',
								'icon-imdb',
								'icon-ravelry',
								'icon-eercast',
								'icon-microchip',
								'icon-snowflake-o',
								'icon-superpowers',
								'icon-wpexplorer',
								'icon-meetup',
								'icon-users',
								'icon-male',
								'icon-female',
								'icon-child',
								'icon-user-secret',
								'icon-plus',
								'icon-plus-circled',
								'icon-plus-squared',
								'icon-plus-squared-alt',
								'icon-minus',
								'icon-lock-open-alt',
								'icon-pin',
								'icon-eye',
								'icon-eye-off',
								'icon-tag',
								'icon-reply-all',
								'icon-forward',
								'icon-quote-left',
								'icon-quote-right',
								'icon-code',
								'icon-bell',
								'icon-bell-alt',
								'icon-bell-off',
								'icon-bell-off-empty',
								'icon-attention-alt',
								'icon-file-powerpoint',
								'icon-file-image',
								'icon-file-archive',
								'icon-file-audio',
								'icon-file-video',
								'icon-sliders',
								'icon-basket',
								'icon-cart-plus',
								'icon-cart-arrow-down',
								'icon-calendar',
								'icon-resize-vertical',
								'icon-resize-horizontal',
								'icon-move',
								'icon-zoom-in',
								'icon-zoom-out',
								'icon-angle-down',
								'icon-angle-circled-left',
								'icon-angle-circled-right',
								'icon-angle-circled-up',
								'icon-angle-circled-down',
								'icon-down-hand',
								'icon-left-circled',
								'icon-right-circled',
								'icon-up-circled',
								'icon-down-circled',
								'icon-stop',
								'icon-pause',
								'icon-to-end',
								'icon-to-end-alt',
								'icon-to-start',
								'icon-cloud',
								'icon-flash',
								'icon-moon',
								'icon-umbrella',
								'icon-flight',
								'icon-align-right',
								'icon-align-justify',
								'icon-list',
								'icon-indent-left',
								'icon-indent-right',
								'icon-off',
								'icon-road',
								'icon-list-alt',
								'icon-qrcode',
								'icon-barcode',
								'icon-magnet',
								'icon-chart-bar',
								'icon-chart-area',
								'icon-chart-pie',
								'icon-chart-line',
								'icon-taxi',
								'icon-truck',
								'icon-bus',
								'icon-bicycle',
								'icon-motorcycle',
								'icon-sort',
								'icon-sort-down',
								'icon-sort-up',
								'icon-sort-alt-up',
								'icon-sort-alt-down',
								'icon-medkit',
								'icon-h-sigh',
								'icon-bed',
								'icon-hospital',
								'icon-building',
								'icon-paw',
								'icon-spoon',
								'icon-cube',
								'icon-cubes',
								'icon-recycle',
								'icon-eyedropper',
								'icon-brush',
								'icon-birthday',
								'icon-diamond',
								'icon-street-view',
								'icon-cc-amex',
								'icon-cc-paypal',
								'icon-cc-stripe',
								'icon-adn',
								'icon-android',
								'icon-dribbble',
								'icon-dropbox',
								'icon-drupal',
								'icon-facebook',
								'icon-facebook-squared',
								'icon-html5',
								'icon-instagram',
								'icon-ioxhost',
								'icon-joomla',
								'icon-jsfiddle',
								'icon-pinterest-squared',
								'icon-qq',
								'icon-reddit',
								'icon-reddit-squared',
								'icon-renren',
								'icon-tencent-weibo',
								'icon-trello',
								'icon-tumblr',
								'icon-tumblr-squared',
								'icon-twitch',
								'icon-youtube-squared',
								'icon-youtube-play',
								'icon-blank',
								'icon-lemon',
								'icon-genderless',
								'icon-cc-jcb',
								'icon-cc-diners-club',
								'icon-clone',
								'icon-balance-scale',
								'icon-hourglass-o',
								'icon-gg-circle',
								'icon-tripadvisor',
								'icon-odnoklassniki',
								'icon-odnoklassniki-square',
								'icon-get-pocket',
								'icon-map-pin',
								'icon-map-signs',
								'icon-map-o',
								'icon-map',
								'icon-commenting',
								'icon-pause-circle',
								'icon-pause-circle-o',
								'icon-stop-circle',
								'icon-stop-circle-o',
								'icon-shopping-bag',
								'icon-braille',
								'icon-assistive-listening-systems',
								'icon-american-sign-language-interpreting',
								'icon-asl-interpreting',
								'icon-glide',
								'icon-envelope-open',
								'icon-envelope-open-o',
								'icon-linode',
								'icon-address-book',
								'icon-address-book-o',
								'icon-thermometer-0',
								'icon-shower',
								'icon-bath',
								'icon-podcast',
								'icon-window-maximize'
							],
  							'Fontelico': [
								'icon-emo-happy',
								'icon-emo-wink',
								'icon-emo-wink2',
								'icon-emo-unhappy',
								'icon-emo-sleep',
								'icon-emo-thumbsup',
								'icon-emo-displeased',
								'icon-emo-grin',
								'icon-emo-angry',
								'icon-emo-saint',
								'icon-emo-cry',
								'icon-emo-squint',
								'icon-emo-laugh',
								'icon-firefox-1',
								'icon-chrome-1',
								'icon-opera-1',
								'icon-ie',
								'icon-crown',
								'icon-crown-plus',
								'icon-crown-minus',
								'icon-marquee',
								'icon-emo-devil',
								'icon-emo-surprised',
								'icon-emo-tongue',
								'icon-emo-coffee',
								'icon-emo-sunglasses'
							],
							'Maki' : [
								'icon-aboveground-rail',
								'icon-airfield',
								'icon-airport',
								'icon-art-gallery',
								'icon-bar',
								'icon-baseball',
								'icon-basketball',
								'icon-beer-2',
								'icon-belowground-rail',
								'icon-bicycle-1',
								'icon-bus-1',
								'icon-cafe',
								'icon-campsite',
								'icon-cemetery',
								'icon-cinema',
								'icon-college',
								'icon-commerical-building',
								'icon-credit-card-2',
								'icon-cricket',
								'icon-embassy',
								'icon-fast-food',
								'icon-ferry',
								'icon-fire-station',
								'icon-football',
								'icon-fuel',
								'icon-garden',
								'icon-giraffe',
								'icon-golf',
								'icon-grocery-store',
								'icon-harbor',
								'icon-heliport',
								'icon-hospital-1',
								'icon-industrial-building',
								'icon-library',
								'icon-lodging',
								'icon-london-underground',
								'icon-minefield',
								'icon-monument',
								'icon-museum',
								'icon-pharmacy',
								'icon-pitch',
								'icon-police',
								'icon-post',
								'icon-prison',
								'icon-rail',
								'icon-religious-christian',
								'icon-religious-islam',
								'icon-religious-jewish',
								'icon-restaurant',
								'icon-roadblock',
								'icon-school',
								'icon-shop',
								'icon-skiing',
								'icon-soccer',
								'icon-swimming',
								'icon-tennis',
								'icon-theatre',
								'icon-toilet',
								'icon-town-hall',
								'icon-trash-2',
								'icon-tree-2',
								'icon-tree-3',
								'icon-warehouse'
							],
							'Meteocons' : [
								'icon-windy-rain-inv',
								'icon-snow-inv',
								'icon-snow-heavy-inv',
								'icon-hail-inv',
								'icon-clouds-inv',
								'icon-clouds-flash-inv',
								'icon-temperature',
								'icon-windy-inv',
								'icon-sunrise',
								'icon-sun-1',
								'icon-moon-1',
								'icon-eclipse',
								'icon-mist',
								'icon-wind',
								'icon-windy-rain',
								'icon-snow',
								'icon-snow-alt',
								'icon-snow-heavy',
								'icon-hail',
								'icon-clouds',
								'icon-clouds-flash',
								'icon-compass-1',
								'icon-na',
								'icon-celcius',
								'icon-fahrenheit',
								'icon-clouds-flash-alt',
								'icon-sun-inv',
								'icon-moon-inv',
								'icon-cloud-sun-inv',
								'icon-cloud-moon-inv',
								'icon-cloud-inv',
								'icon-cloud-flash-inv',
								'icon-drizzle-inv',
								'icon-rain-inv',
								'icon-snowflake',
								'icon-cloud-sun',
								'icon-cloud-moon',
								'icon-fog-sun',
								'icon-fog-moon',
								'icon-fog-cloud',
								'icon-fog',
								'icon-cloud-1',
								'icon-cloud-flash',
								'icon-cloud-flash-alt',
								'icon-drizzle',
								'icon-rain',
								'icon-windy'
							]
					    }

				        $('#{{ data.id }}').fontIconPicker({
				            source: sourceIcons,
				            theme: 'fip-bootstrap',
				            emptyIcon: {{ data.empty_icon }},
				            hasSearch: true
				        }).on('change', function() {
				            selectedIcon = $(this).val();
				            $('#{{ data.id }}').val(selectedIcon);
				        }); // Load with default options
				    });
				</script>
			</script>
			<?php
			//@formatter:on
		}

	}

	Ecu_Icon_Select::get_instance();
