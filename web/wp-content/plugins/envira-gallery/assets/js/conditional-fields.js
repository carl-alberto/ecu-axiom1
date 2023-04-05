import './conditions';

/**
 * Handles showing and hiding fields conditionally
 */
jQuery(document).ready(function ($) {
	// Show/hide elements as necessary when a conditional field is changed
	$(
		'#envira-gallery-settings input:not([type=hidden]), #envira-gallery-settings select',
	).conditions([
		{
			// Main Theme Elements
			conditions: {
				element: '[name="_envira_gallery[lightbox_theme]"]',
				type: 'value',
				operator: 'array',
				condition: [
					'base',
					'captioned',
					'polaroid',
					'showcase',
					'sleek',
					'subtle',
				],
			},
			actions: {
				if: [
					{
						element:
							'#envira-config-lightbox-title-display-box, #envira-config-lightbox-arrows-box, #envira-config-lightbox-toolbar-box',
						action: 'show',
					},
				],
			},
		},
		{
			// Main Theme Elements
			conditions: {
				element: '[name="_envira_gallery[lightbox_theme]"]',
				type: 'value',
				operator: 'array',
				condition: ['base_dark', 'base_light'],
			},
			actions: {
				if: [
					{
						element: '#envira-config-lightbox-arrows-box',
						action: 'show',
					},
				],
			},
		},
		{
			conditions: {
				element: '[name="_envira_gallery[lightbox_theme]"]',
				type: 'value',
				operator: 'array',
				condition: ['base_dark', 'base_light'],
			},
			actions: {
				if: [
					{
						element:
							'#envira-config-lightbox-title-display-box, #envira-config-lightbox-toolbar-box',
						action: 'hide',
					},
				],
			},
		},
		{
			// Gallery arrows Dependant on Theme
			conditions: [
				{
					element: '[name="_envira_gallery[lightbox_theme]"]',
					type: 'value',
					operator: 'array',
					condition: [
						'base',
						'captioned',
						'polaroid',
						'showcase',
						'sleek',
						'subtle',
					],
				},
				{
					element: '[name="_envira_gallery[arrows]"]',
					type: 'checked',
					operator: 'is',
				},
			],
			actions: {
				if: {
					element: '#envira-config-lightbox-arrows-position-box',
					action: 'show',
				},
				else: {
					element: '#envira-config-lightbox-arrows-position-box',
					action: 'hide',
				},
			},
		},
		{
			// Gallery arrows Dependant on Theme
			conditions: [
				{
					element: '[name="_envira_gallery[lightbox_theme]"]',
					type: 'value',
					operator: 'array',
					condition: [
						'base',
						'captioned',
						'polaroid',
						'showcase',
						'sleek',
						'subtle',
					],
				},
			],
			actions: {
				if: {
					element:
						'#envira-config-fullscreen-box, #envira-config-open-fullscreen-box',
					action: 'hide',
				},
			},
		},
		{
			// Gallery arrows Dependant on Theme
			conditions: [
				{
					element: '[name="_envira_gallery[lightbox_theme]"]',
					type: 'value',
					operator: 'array',
					condition: ['base_dark', 'base_light'],
				},
			],
			actions: {
				if: {
					element:
						'#envira-config-fullscreen-box, #envira-config-open-fullscreen-box',
					action: 'show',
				},
			},
		},
		{
			// Gallery arrows Dependant on Theme
			conditions: [
				{
					element: '[name="_envira_gallery[lightbox_theme]"]',
					type: 'value',
					operator: 'array',
					condition: [
						'base',
						'captioned',
						'polaroid',
						'showcase',
						'sleek',
						'subtle',
					],
				},
				{
					element: '[name="_envira_gallery[toolbar]"]',
					type: 'checked',
					operator: 'is',
				},
			],
			actions: {
				if: {
					element:
						'#envira-config-fullscreen-box, #envira-config-open-fullscreen-box',
					action: 'show',
				},
			},
		},
		{
			// Items that are dependent on dark and new themes
			conditions: [
				{
					element: '[name="_envira_gallery[lightbox_theme]"]',
					type: 'value',
					operator: 'array',
					condition: [
						'base_dark',
						'base_light',
						'infinity_light',
						'infinity_dark',
						'classical_light',
						'classical_dark',
					],
				},
			],
			actions: {
				if: {
					element: '#envira-config-image-counter',
					action: 'show',
				},
				else: {
					element: '#envira-config-image-counter',
					action: 'hide',
				},
			},
		},
		{
			// Gallery Toolbar
			conditions: [
				{
					element: '[name="_envira_gallery[toolbar]"]',
					type: 'checked',
					operator: 'is',
				},
				{
					element: '[name="_envira_gallery[lightbox_theme]"]',
					type: 'value',
					operator: 'array',
					condition: [
						'base',
						'captioned',
						'polaroid',
						'showcase',
						'sleek',
						'subtle',
					],
				},
			],
			actions: {
				if: [
					{
						element:
							'#envira-config-lightbox-toolbar-title-box, #envira-config-lightbox-toolbar-position-box',
						action: 'show',
					},
				],
				else: [
					{
						element:
							'#envira-config-lightbox-toolbar-title-box, #envira-config-lightbox-toolbar-position-box',
						action: 'hide',
					},
				],
			},
		},
		{
			// Mobile Elements Dependant on Theme
			conditions: [
				{
					element: '[name="_envira_gallery[lightbox_theme]"]',
					type: 'value',
					operator: 'array',
					condition: [
						'base',
						'captioned',
						'polaroid',
						'showcase',
						'sleek',
						'subtle',
					],
				},
				{
					element: '[name="_envira_gallery[mobile_lightbox]"]',
					type: 'checked',
					operator: 'is',
				},
			],
			actions: {
				if: {
					element: '#envira-config-mobile-toolbar-box',
					action: 'show',
				},
				else: {
					element: '#envira-config-mobile-toolbar-box',
					action: 'hide',
				},
			},
		},
		{
			// Mobile Elements Independant of Theme
			conditions: {
				element: '[name="_envira_gallery[mobile_lightbox]"]',
				type: 'checked',
				operator: 'is',
			},
			actions: {
				if: {
					element:
						'#envira-config-mobile-touchwipe-box, #envira-config-mobile-touchwipe-close-box, #envira-config-mobile-thumbnails-box',
					action: 'show',
				},
				else: {
					element:
						'#envira-config-mobile-touchwipe-box, #envira-config-mobile-touchwipe-close-box, #envira-config-mobile-thumbnails-box',
					action: 'hide',
				},
			},
		},
		{
			// Mobile Elements Independant of Theme
			conditions: {
				element: '[name="_envira_gallery[mobile_lightbox]"]',
				type: 'checked',
				operator: 'is',
			},
			actions: {
				if: {
					element: '#envira-config-lightbox-mobile-enable-links',
					action: 'hide',
				},
				else: {
					element: '#envira-config-lightbox-mobile-enable-links',
					action: 'show',
				},
			},
		},
		{
			// Thumbnail Elements Dependant on Theme
			conditions: [
				{
					element: '[name="_envira_gallery[lightbox_theme]"]',
					type: 'value',
					operator: 'array',
					condition: [
						'base',
						'captioned',
						'polaroid',
						'showcase',
						'sleek',
						'subtle',
					],
				},
				{
					element: '[name="_envira_gallery[thumbnails]"]',
					type: 'checked',
					operator: 'is',
				},
			],
			actions: {
				if: {
					element: '#envira-config-thumbnails-position-box',
					action: 'show',
				},
				else: {
					element: '#envira-config-thumbnails-position-box',
					action: 'hide',
				},
			},
		},
		{
			// Thumbnail Elements Independant of Theme
			conditions: [
				{
					element: '[name="_envira_gallery[thumbnails]"]',
					type: 'checked',
					operator: 'is',
				},
			],
			actions: {
				if: {
					element: '#envira-config-thumbnails-custom-size',
					action: 'show',
				},
				else: {
					element: '#envira-config-thumbnails-custom-size',
					action: 'hide',
				},
			},
		},
		{
			// Thumbnail Elements Independant of Theme
			conditions: [
				{
					element: '[name="_envira_gallery[thumbnails_toggle]"]',
					type: 'checked',
					operator: 'is',
				},
			],
			actions: {
				if: {
					element: '#envira-config-thumbnail-hide',
					action: 'show',
				},
				else: {
					element: '#envira-config-thumbnail-hide',
					action: 'hide',
				},
			},
		},
		{
			// Thumbnail Elements Independant of Theme
			conditions: [
				{
					element: '[name="_envira_gallery[thumbnails_custom_size]"]',

					type: 'checked',
					operator: 'is',
				},
			],
			actions: {
				if: {
					element:
						'#envira-config-thumbnails-height-box, #envira-config-thumbnails-width-box',
					action: 'show',
				},
				else: {
					element:
						'#envira-config-thumbnails-height-box, #envira-config-thumbnails-width-box',
					action: 'hide',
				},
			},
		},
		{
			// Thumbnail Elements Dependant on Base Theme
			conditions: [
				{
					element: '[name="_envira_gallery[lightbox_theme]"]',
					type: 'value',
					operator: 'array',
					condition: ['base_dark', 'base_light'],
				},
				{
					element: '[name="_envira_gallery[thumbnails]"]',
					type: 'checked',
					operator: 'is',
				},
			],
			actions: {
				if: {
					element: '#envira-config-thumbnail-button',
					action: 'show',
				},
				else: {
					element: '#envira-config-thumbnail-button',
					action: 'hide',
				},
			},
		},
		{
			// Automatic
			conditions: {
				element: '[name="_envira_gallery[layout]"]',
				type: 'value',
				operator: 'array',
				condition: ['automatic'],
			},
			actions: {
				if: [
					{
						element:
							'#envira-config-standard-settings-box, #envira-config-additional-copy-box, #envira-config-title-caption-column-mobile',
						action: 'hide',
					},
					{
						element:
							'#envira-config-justified-settings-box, #envira-config-mobile-justified-row-height, #envira-config-additional-copy-box-automatic, #envira-config-title-caption-automatic-mobile',
						action: 'show',
					},
				],
				else: [
					{
						element:
							'#envira-config-standard-settings-box, #envira-config-additional-copy-box, #envira-config-title-caption-column-mobile',
						action: 'show',
					},
					{
						element:
							'#envira-config-justified-settings-box, #envira-config-mobile-justified-row-height, #envira-config-additional-copy-box-automatic, #envira-config-title-caption-automatic-mobile',
						action: 'hide',
					},
				],
			},
		},
		{
			// Automatic
			conditions: {
				element: '[name="_envira_gallery[layout]"]',
				type: 'value',
				operator: 'array',
				condition: ['blogroll', 'automatic'],
			},
			actions: {
				if: [
					{
						element: '#envira-config-columns-box',
						action: 'hide',
					},
				],
				else: [
					{
						element: '#envira-config-columns-box',
						action: 'show',
					},
				],
			},
		},

		{
			// Gallery Description
			conditions: {
				element: '[name="_envira_gallery[description_position]"]',
				type: 'value',
				operator: 'array',
				condition: ['0'],
			},
			actions: {
				if: [
					{
						element: '#envira-config-description-box',
						action: 'hide',
					},
				],
				else: [
					{
						element: '#envira-config-description-box',
						action: 'show',
					},
				],
			},
		},
		{
			// Gallery image size
			conditions: {
				element: '[name="_envira_gallery[image_size]"]',
				type: 'value',
				operator: 'array',
				condition: ['default'],
			},
			actions: {
				if: [
					{
						element:
							'#envira-config-crop-size-box, #envira-config-crop-box',
						action: 'show',
					},
				],
				else: [
					{
						element:
							'#envira-config-crop-size-box, #envira-config-crop-box',
						action: 'hide',
					},
				],
			},
		},
		{
			// Automatic
			conditions: {
				element: '[name="_envira_gallery[layout]"]',
				type: 'value',
				operator: 'array',
				condition: ['blogroll'],
			},
			actions: {
				if: [
					{
						element:
							'#envira-config-crop-size-box, #envira-config-crop-box',

						action: 'hide',
					},
				],
				else: [
					{
						element:
							'#envira-config-crop-size-box, #envira-config-crop-box',

						action: 'show',
					},
				],
			},
		},
		{
			// Automatic
			conditions: {
				element: '[name="_envira_gallery[layout]"]',
				type: 'value',
				operator: 'array',
				condition: ['square'],
			},
			actions: {
				if: [
					{
						element: '#envira-config-square-size-box',
						action: 'show',
					},
					{
						element:
							'#envira-config-crop-size-box, #envira-config-crop-box, #envira-config-image-size-box',
						action: 'hide',
					},
				],
				else: [
					{
						element: '#envira-config-square-size-box',
						action: 'hide',
					},
					{
						element:
							'#envira-config-crop-size-box, #envira-config-crop-box, #envira-config-image-size-box',
						action: 'show',
					},
				],
			},
		},
		{
			// Gallery Lightbox
			conditions: {
				element: '[name="_envira_gallery[lightbox_enabled]"]',
				type: 'checked',
				operator: 'is',
			},
			actions: {
				if: [
					{
						element: '#envira-lightbox-settings',
						action: 'show',
					},
				],
				else: [
					{
						element: '#envira-lightbox-settings',
						action: 'hide',
					},
				],
			},
		},
		{
			// Gallery Lightbox
			conditions: {
				element: '[name="_envira_gallery[lightbox_enabled]"]',
				type: 'checked',
				operator: 'is',
			},
			actions: {
				if: [
					{
						element: '#envira-config-lightbox-enabled-link',
						action: 'hide',
					},
				],
				else: [
					{
						element: '#envira-config-lightbox-enabled-link',
						action: 'show',
					},
				],
			},
		},
		{
			// Album Mobile Images
			conditions: {
				element: '[name="_envira_gallery[mobile]"]',
				type: 'checked',
				operator: 'is',
			},
			actions: {
				if: [
					{
						element: '#envira-config-mobile-size-box',
						action: 'show',
					},
				],
				else: [
					{
						element: '#envira-config-mobile-size-box',
						action: 'hide',
					},
				],
			},
		},
		{
			conditions: [
				{
					element: '[name="_envira_gallery[mobile_thumbnails]"]',

					type: 'checked',
					operator: 'is',
				},
				{
					element: '[name="_envira_gallery[mobile_lightbox]"]',
					type: 'checked',
					operator: 'is',
				},
				{
					element: '[name="_envira_gallery[mobile_lightbox]"]',
					type: 'checked',
					operator: 'is',
				},
			],
			actions: {
				if: {
					element:
						'#envira-config-mobile-thumbnails-width-box, #envira-config-mobile-thumbnails-height-box',
					action: 'show',
				},
				else: {
					element:
						'#envira-config-mobile-thumbnails-width-box, #envira-config-mobile-thumbnails-height-box',
					action: 'hide',
				},
			},
		},
		{
			// Album Mobile Touchwipe
			conditions: {
				element: '[name="_envira_gallery[lazy_loading]"]',
				type: 'checked',
				operator: 'is',
			},

			actions: {
				if: [
					{
						element: '#envira-config-lazy-loading-delay',
						action: 'show',
					},
				],
				else: [
					{
						element: '#envira-config-lazy-loading-delay',
						action: 'hide',
					},
				],
			},
		},
		{
			// Gallery Sorting
			conditions: {
				element: '#envira-config-sorting-defaults',
				type: 'value',
				operator: 'array',
				condition: ['0', '1'],
			},
			actions: {
				if: [
					{
						element: '#envira-config-sorting-direction-box',
						action: 'hide',
					},
				],
				else: [
					{
						element: '#envira-config-sorting-direction-box',
						action: 'show',
					},
				],
			},
		},
	]);
});
