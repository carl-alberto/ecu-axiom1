"use strict";function _classCallCheck(instance,Constructor){if(!(instance instanceof Constructor))throw new TypeError("Cannot call a class as a function")}var _createClass=function(){function defineProperties(target,props){for(var i=0;i<props.length;i++){var descriptor=props[i];descriptor.enumerable=descriptor.enumerable||!1,descriptor.configurable=!0,"value"in descriptor&&(descriptor.writable=!0),Object.defineProperty(target,descriptor.key,descriptor)}}return function(Constructor,protoProps,staticProps){return protoProps&&defineProperties(Constructor.prototype,protoProps),staticProps&&defineProperties(Constructor,staticProps),Constructor}}();(function(){var ImagePicker,ImagePickerOption,both_array_are_equal,sanitized_options,indexOf=[].indexOf;jQuery.fn.extend({imagepicker:function(){var opts=arguments.length>0&&void 0!==arguments[0]?arguments[0]:{};return this.each(function(){var select;if((select=jQuery(this)).data("picker")&&select.data("picker").destroy(),select.data("picker",new ImagePicker(this,sanitized_options(opts))),null!=opts.initialized)return opts.initialized.call(select.data("picker"))})}}),sanitized_options=function(opts){var default_options;return default_options={hide_select:!0,show_label:!1,initialized:void 0,changed:void 0,clicked:void 0,selected:void 0,limit:void 0,limit_reached:void 0,font_awesome:!1},jQuery.extend(default_options,opts)},both_array_are_equal=function(a,b){var i,j,len,x;if(!a||!b||a.length!==b.length)return!1;for(a=a.slice(0),b=b.slice(0),a.sort(),b.sort(),i=j=0,len=a.length;j<len;i=++j)if(x=a[i],b[i]!==x)return!1;return!0},ImagePicker=function(){function ImagePicker(select_element){var opts1=arguments.length>1&&void 0!==arguments[1]?arguments[1]:{};_classCallCheck(this,ImagePicker),this.sync_picker_with_select=this.sync_picker_with_select.bind(this),this.opts=opts1,this.select=jQuery(select_element),this.multiple="multiple"===this.select.attr("multiple"),null!=this.select.data("limit")&&(this.opts.limit=parseInt(this.select.data("limit"))),this.build_and_append_picker()}return _createClass(ImagePicker,[{key:"destroy",value:function(){var j,len,ref;for(j=0,len=(ref=this.picker_options).length;j<len;j++)ref[j].destroy();return this.picker.remove(),this.select.off("change",this.sync_picker_with_select),this.select.removeData("picker"),this.select.show()}},{key:"build_and_append_picker",value:function(){return this.opts.hide_select&&this.select.hide(),this.select.on("change",this.sync_picker_with_select),null!=this.picker&&this.picker.remove(),this.create_picker(),this.select.after(this.picker),this.sync_picker_with_select()}},{key:"sync_picker_with_select",value:function(){var j,len,option,ref,results;for(results=[],j=0,len=(ref=this.picker_options).length;j<len;j++)(option=ref[j]).is_selected()?results.push(option.mark_as_selected()):results.push(option.unmark_as_selected());return results}},{key:"create_picker",value:function(){return this.picker=jQuery("<ul class='thumbnails image_picker_selector'></ul>"),this.picker_options=[],this.recursively_parse_option_groups(this.select,this.picker),this.picker}},{key:"recursively_parse_option_groups",value:function(scoped_dom,target_container){var container,j,k,len,len1,option,option_group,ref,ref1,results;for(j=0,len=(ref=scoped_dom.children("optgroup")).length;j<len;j++)option_group=ref[j],option_group=jQuery(option_group),(container=jQuery("<ul></ul>")).append(jQuery("<li class='group_title'>"+option_group.attr("label")+"</li>")),target_container.append(jQuery("<li class='group'>").append(container)),this.recursively_parse_option_groups(option_group,container);for(ref1=function(){var l,len1,ref1,results1;for(results1=[],l=0,len1=(ref1=scoped_dom.children("option")).length;l<len1;l++)option=ref1[l],results1.push(new ImagePickerOption(option,this,this.opts));return results1}.call(this),results=[],k=0,len1=ref1.length;k<len1;k++)option=ref1[k],this.picker_options.push(option),option.has_image()&&results.push(target_container.append(option.node));return results}},{key:"has_implicit_blanks",value:function(){var option;return function(){var j,len,ref,results;for(results=[],j=0,len=(ref=this.picker_options).length;j<len;j++)(option=ref[j]).is_blank()&&!option.has_image()&&results.push(option);return results}.call(this).length>0}},{key:"selected_values",value:function(){return this.multiple?this.select.val()||[]:[this.select.val()]}},{key:"toggle",value:function(imagepicker_option,original_event){var new_values,old_values,selected_value;if(old_values=this.selected_values(),selected_value=imagepicker_option.value().toString(),this.multiple?indexOf.call(this.selected_values(),selected_value)>=0?((new_values=this.selected_values()).splice(jQuery.inArray(selected_value,old_values),1),this.select.val([]),this.select.val(new_values)):null!=this.opts.limit&&this.selected_values().length>=this.opts.limit?null!=this.opts.limit_reached&&this.opts.limit_reached.call(this.select):this.select.val(this.selected_values().concat(selected_value)):this.has_implicit_blanks()&&imagepicker_option.is_selected()?this.select.val(""):this.select.val(selected_value),!both_array_are_equal(old_values,this.selected_values())&&(this.select.change(),null!=this.opts.changed))return this.opts.changed.call(this.select,old_values,this.selected_values(),original_event)}}]),ImagePicker}(),ImagePickerOption=function(){function ImagePickerOption(option_element,picker){var opts1=arguments.length>2&&void 0!==arguments[2]?arguments[2]:{};_classCallCheck(this,ImagePickerOption),this.clicked=this.clicked.bind(this),this.picker=picker,this.opts=opts1,this.option=jQuery(option_element),this.create_node()}return _createClass(ImagePickerOption,[{key:"destroy",value:function(){return this.node.find(".thumbnail").off("click",this.clicked)}},{key:"has_image",value:function(){return null!=this.option.data("img-src")}},{key:"is_blank",value:function(){return!(null!=this.value()&&""!==this.value())}},{key:"is_selected",value:function(){var select_value;return select_value=this.picker.select.val(),this.picker.multiple?jQuery.inArray(this.value(),select_value)>=0:this.value()===select_value}},{key:"mark_as_selected",value:function(){return this.node.find(".thumbnail").addClass("selected")}},{key:"unmark_as_selected",value:function(){return this.node.find(".thumbnail").removeClass("selected")}},{key:"value",value:function(){return this.option.val()}},{key:"label",value:function(){return this.option.data("img-label")?this.option.data("img-label"):this.option.text()}},{key:"clicked",value:function(event){if(this.picker.toggle(this,event),null!=this.opts.clicked&&this.opts.clicked.call(this.picker.select,this,event),null!=this.opts.selected&&this.is_selected())return this.opts.selected.call(this.picker.select,this,event)}},{key:"create_node",value:function(){var image,imgAlt,imgClass,thumbnail;return this.node=jQuery("<li/>"),this.option.data("font_awesome")?(image=jQuery("<i>")).attr("class","fa-fw "+this.option.data("img-src")):(image=jQuery("<img class='image_picker_image'/>")).attr("src",this.option.data("img-src")),thumbnail=jQuery("<div class='thumbnail'>"),(imgClass=this.option.data("img-class"))&&(this.node.addClass(imgClass),image.addClass(imgClass),thumbnail.addClass(imgClass)),(imgAlt=this.option.data("img-alt"))&&image.attr("alt",imgAlt),thumbnail.on("click",this.clicked),thumbnail.append(image),this.opts.show_label&&thumbnail.append(jQuery("<p/>").html(this.label())),this.node.append(thumbnail),this.node}}]),ImagePickerOption}()}).call(void 0);

jQuery(document).ready(function($) {
	// Image Size: Random
	// conditional-fields doesn't support multiple conditions, so we manually show/hide
	// the Random Image Sizes option depending on the Image Size value
	$('select[name="_envira_gallery[image_size]"]').on('change', function() {
		if ($(this).val() === 'envira_gallery_random') {
			$('tr#envira-config-image-sizes-random-box').show();
		} else {
			$('tr#envira-config-image-sizes-random-box').hide();
		}
	});

	// Run the above conditions on load.
	$('select[name="_envira_gallery[image_size]"]').trigger('change');

	$('#envira-gallery.postbox').on('dragstart', function(e) {
		return false;
	});
});

/*jshint unused: false, node: true */
/**
 * Handles retrieving and outputting images for the Gallery Preview metabox
 *
 * @since 1.5.0
 */ (function($) {
	$(function() {
		// Setup vars
		var envira_preview_updating = false;

		// Show or hide the Preview metabox, depending on the Gallery Type
		if (
			$('input[name="_envira_gallery[type]"]:checked').val() ===
			'default'
		) {
			$('#envira-gallery-preview').hide();
		} else {
			$('#envira-gallery-preview').show();
		}

		// Show or hide the Preview metabox, when the Gallery Type is changed, or the enviraGalleryPreview
		// action is fired.
		$(document).on('enviraGalleryType enviraGalleryPreview', function() {
			// Setup some vars
			var envira_gallery_type = $(
					'input[name="_envira_gallery[type]"]:checked',
				).val(),
				envira_spinner = $('#envira-gallery-preview .spinner'),
				envira_gallery_preview = $('#envira-gallery-preview-main');

			// If the gallery type is default, hide the preview and return.
			if (envira_gallery_type === 'default') {
				$(envira_gallery_preview).hide();
				return;
			}

			// If the preview is still updating from a previous AJAX call, don't do anything else.
			if (envira_preview_updating) {
				return;
			}

			// Update the flag to indicate we're running an AJAX request.
			envira_preview_updating = true;

			// Remove the content from the preview
			$(envira_gallery_preview).html('');

			// Make an AJAX call to get the content for the tab
			$.ajax({
				type: 'post',
				url: envira_gallery_metabox.ajax,
				dataType: 'json',
				data: {
					action: 'envira_gallery_change_preview',
					post_id: envira_gallery_metabox.id,
					type: envira_gallery_type,
					data: $('form#post').serializeArray(),
					nonce: envira_gallery_metabox.preview_nonce,
				},
				success: function(response) {
					// Inject the response into the preview area
					$(envira_gallery_preview).html(response);

					// Hide the spinner
					$(envira_spinner).hide();

					// We've finished updating the preview.
					envira_preview_updating = false;
				},
				error: function(textStatus, errorThrown) {
					// Inject the error message into the tab settings area
					$(envira_gallery_preview).html(
						'<div class="error"><p>' +
							textStatus.responseText +
							'</p></div>',
					);

					// Hide the spinner
					$(envira_spinner).hide();

					// We've finished updating the preview.
					envira_preview_updating = false;
				},
			});
		});
	});
})(jQuery);

/**
 * Handles changing Gallery Types, for example from Default to Instagram
 */ (function($) {
	$(function() {
		// Change the radio checked option and fire the change event when a Gallery Type is clicked
		$('#envira-gallery-types-nav').on('click', 'li', function(e) {
			$('input[name="_envira_gallery[type]"]', $(this))
				.prop('checked', true)
				.trigger('change');
		});

		// Retrieve the settings HTML when the Gallery Type is changed, so the relevent options are displayed
		$(document).on(
			'change',
			'input[name="_envira_gallery[type]"]:radio',
			function(e) {
				// Setup some vars
				var envira_gallery_type = $(this).val(),
					envira_spinner = $(
						'#envira-tabs #envira-tab-images .spinner',
					),
					envira_tab_settings = $(
						'#envira-tabs #envira-tab-images #envira-gallery-main',
					);

				// Display the spinner, so the user knows something is happening
				$(envira_spinner).css('visibility', 'visible');

				// Remove the envira-active class from all Gallery Types
				$(
					'li',
					$(this).closest('#envira-gallery-types-nav'),
				).removeClass('envira-active');

				// Add the envira-active class to the chosen Gallery Type
				$(this)
					.closest('li')
					.addClass('envira-active');

				// Switch the Settings Metabox to the first tab (Images)
				$('a', $('#envira-tabs-nav li').first()).trigger('click');

				// Remove the content from the now displayed tab settings
				$(envira_tab_settings).html('');

				// Make an AJAX call to get the content for the tab
				$.ajax({
					type: 'post',
					url: envira_gallery_metabox.ajax,
					dataType: 'json',
					data: {
						action: 'envira_gallery_change_type',
						post_id: envira_gallery_metabox.id,
						type: envira_gallery_type,
						nonce: envira_gallery_metabox.change_nonce,
					},
					success: function(response) {
						// Inject the response into the tab settings area
						$(envira_tab_settings).html(response.html);

						// Fire an event to tell Addons that the Gallery Type has changed.
						// (e.g. Featured Content Addon uses this to initialize some JS with the DOM).
						$(document).trigger(
							'enviraGalleryType',
							response,
						);

						// Hide the spinner
						$(envira_spinner).hide();
					},
					error: function(textStatus, errorThrown) {
						// Inject the error message into the tab settings area
						$(envira_tab_settings).html(
							'<div class="error"><p>' +
								textStatus.responseText +
								'</p></div>',
						);

						// Hide the spinner
						$(envira_spinner).hide();
					},
				});
			},
		);
	});
})(jQuery);

/**
 * Handles:
 * - Inline Video Help
 *
 * @since 1.5.0
 */

// Setup vars
var envira_video_link = 'p.envira-intro a.envira-video',
	envira_close_video_link = 'a.envira-video-close';

jQuery(document).ready(function($) {
	/**
	 * Display Video Inline on Video Link Click
	 */
	$(document).on('click', envira_video_link, function(e) {
		// Prevent default action
		e.preventDefault();

		// Get the video URL
		var envira_video_url = $(this).attr('href');

		// Destroy any other instances of Envira Video iframes
		$('div.envira-video-help').remove();

		// Get the intro paragraph
		var envira_video_paragraph = $(this).closest('p.envira-intro');

		// Load the video below the intro paragraph on the current tab
		$(envira_video_paragraph).append(
			'<div class="envira-video-help"><iframe src="' +
				envira_video_url +
				'" /><a href="#" class="envira-video-close dashicons dashicons-no"></a></div>',
		);
	});

	/**
	 * Destroy Video when closed
	 */
	$(document).on('click', envira_close_video_link, function(e) {
		e.preventDefault();

		$(this)
			.closest('.envira-video-help')
			.remove();
	});
});

/* global quicktags, wp, QTags */

/**
 * Single Image View
 * - Renders an <li> element within the bulk edit view
 */
var EnviraGalleryBulkEditImageView = wp.Backbone.View.extend({
	/**
	 * The Tag Name and Tag's Class(es)
	 */
	tagName: 'li',
	className: 'attachment',

	/**
	 * Template
	 * - The template to load inside the above tagName element
	 */
	template: wp.template('envira-meta-bulk-editor-image'),

	/**
	 * Initialize
	 *
	 * @param object model   EnviraGalleryImage Backbone Model
	 */
	initialize: function(args) {
		// Assign the model to this view
		this.model = args.model;
	},

	/**
	 * Render
	 * - Binds the model to the view, so we populate the view's fields and data
	 */
	render: function() {
		// Get HTML
		this.$el.html(this.template(this.model.attributes));
		return this;
	},
});

/**
 * Bulk Edit View
 */
var EnviraGalleryBulkEditView = wp.Backbone.View.extend({
	/**
	 * The Tag Name and Tag's Class(es)
	 */
	tagName: 'div',
	className: 'edit-attachment-frame mode-select hide-menu hide-router',

	/**
	 * Template
	 * - The template to load inside the above tagName element
	 */
	template: wp.template('envira-meta-bulk-editor'),

	/**
	 * Events
	 * - Functions to call when specific events occur
	 */
	events: {
		'keyup input': 'updateItem',
		'keyup textarea': 'updateItem',
		'change input': 'updateItem',
		'change textarea': 'updateItem',
		'blur textarea': 'updateItem',
		'change select': 'updateItem',

		'click .actions a.envira-gallery-meta-submit': 'saveItem',

		'keyup input#link-search': 'searchLinks',
		'click div.query-results li': 'insertLink',

		'click button.media-file': 'insertMediaFileLink',
		'click button.attachment-page': 'insertAttachmentPageLink',
	},

	/**
	 * Initialize
	 *
	 * @param object model   EnviraGalleryImage Backbone Model
	 */
	initialize: function(args) {
		// Define loading and loaded events, which update the UI with what's happening.
		this.on('loading', this.loading, this);
		this.on('loaded', this.loaded, this);

		// Set some flags
		this.is_loading = false;
		this.collection = args.collection;
		this.child_views = args.child_views;

		// The model will be blank, as we want the user's settings for each
		// option to then apply to the entire collection
		this.model = new EnviraGalleryImage();
	},

	/**
	 * Render
	 * - Binds the collection to the view, so we populate the view's attachments grid
	 */
	render: function() {
		// Get HTML
		this.$el.html(this.template(this.model.toJSON()));

		// Render selected items
		this.collection.forEach(function(model) {
			// Init with model
			var child_view = new EnviraGalleryBulkEditImageView({
				model: model,
			});

			// Render view within our main view
			this.$el.find('ul.attachments').append(child_view.render().el);
		}, this);

		// If any child views exist, render them now
		if (this.child_views.length > 0) {
			this.child_views.forEach(function(View) {
				// Init with model
				var child_view = new View({
					model: this.model,
				});

				// Render view within our main view
				this.$el
					.find('div.envira-addons')
					.append(child_view.render().el);
			}, this);
		}

		// Init QuickTags on the caption editor
		// Delay is required for the first load for some reason
		setTimeout(function() {
			quicktags({
				id: 'caption',
				buttons: 'strong,em,link,ul,ol,li,close',
			});
			QTags._buttonsInit();
		}, 500);
		this.model.set('status', 'active');
		// Init Link Searching
		wpLink.init;

		// Return
		return this;
	},

	/**
	 * Renders an error using
	 * wp.media.view.EnviraGalleryError
	 */
	renderError: function(error) {
		// Define model
		var model = {};
		model.error = error;

		// Define view
		var view = new wp.media.view.EnviraGalleryError({
			model: model,
		});

		// Return rendered view
		return view.render().el;
	},

	/**
	 * Tells the view we're loading by displaying a spinner
	 */
	loading: function() {
		// Set a flag so we know we're loading data
		this.is_loading = true;

		// Show the spinner
		this.$el.find('.spinner').css('visibility', 'visible');
	},

	/**
	 * Hides the loading spinner
	 */
	loaded: function(response) {
		// Set a flag so we know we're not loading anything now
		this.is_loading = false;

		// Hide the spinner
		this.$el.find('.spinner').css('visibility', 'hidden');

		// Display the error message, if it's provided
		if (typeof response !== 'undefined') {
			this.$el
				.find('ul.attachments')
				.before(this.renderError(response));
		}
	},

	/**
	 * Updates the model based on the changed view data
	 */
	updateItem: function(event) {
		let value;
		// Check if the target has a name. If not, it's not a model value we want to store
		if (event.target.name === '') {
			return;
		}

		// Update the model's value, depending on the input type
		if (event.target.type === 'checkbox') {
			value = event.target.checked ? 1 : 0;
		} else {
			value = event.target.value;
		}

		// Update the model
		this.model.set(event.target.name, value);
	},

	/**
	 * Saves the image metadata
	 */
	saveItem: function(event) {
		event.preventDefault();

		let value;

		// Tell the View we're loading
		this.trigger('loading');

		// Build an array of image IDs
		var image_ids = [];
		this.collection.forEach(function(model) {
			image_ids.push(model.id);
		}, this);

		// Make an AJAX request to save the image metadata for the collection's images
		wp.media.ajax('envira_gallery_save_bulk_meta', {
			context: this,
			data: {
				nonce: envira_gallery_metabox.save_nonce,
				post_id: envira_gallery_metabox.id,
				meta: this.model.attributes,
				image_ids: image_ids,
			},
			success: function(response) {
				// For each image, update the model based on the edited information before inserting it as JSON
				// into the underlying image.
				this.collection.forEach(function(model) {
					for (var key in this.model.attributes) {
						value = this.model.attributes[key];

						// If the value is not blank, assign the value to the image model
						if (value.length > 0) {
							if (key == 'tags') {
								model.set(
									key,
									response[model.get('id')],
								);
							} else {
								model.set(key, value);
							}
						}
					}

					var item = JSON.stringify(model.attributes),
						$this = jQuery(
							'ul#envira-gallery-output li#' +
								model.get('id') +
								' .envira-item-status',
						),
						$data = $this.data('status'),
						$parent = $this.hasClass('list-status')
							? $this.parent()
							: $this.parent(),
						$list_view = $parent.find(
							'.envira-item-status.list-status',
						),
						$grid_view = $parent.find(
							'.envira-item-status.grid-status',
						),
						id = $this.data('id'),
						$icon = $grid_view.find('span.dashicons'),
						$text = $list_view.find('span'),
						$status = model.get('status');

					// Assign the model to the underlying image item in the DOM
					jQuery(
						'ul#envira-gallery-output li#' + model.get('id'),
					).attr('data-envira-gallery-image-model', item);
					jQuery(
						'ul#envira-gallery-output li#' +
							model.get('id') +
							' div.title',
					).text(model.get('title'));

					if ($status === 'active') {
						// Toggle Classes
						$grid_view
							.removeClass('envira-draft-item')
							.addClass('envira-active-item');
						$list_view
							.removeClass('envira-draft-item')
							.addClass('envira-active-item');

						// Set the proper icons
						$icon.removeClass('dashicons-hidden').addClass(
							'dashicons-visibility',
						);

						// Set the Text
						$text.text(envira_gallery_metabox.active);

						$grid_view.attr(
							'data-envira-tooltip',
							envira_gallery_metabox.active,
						);

						// Set the Data
						$list_view.data('status', 'active');
						$grid_view.data('status', 'active');
					} else {
						// Toggle Classes
						$grid_view
							.removeClass('envira-active-item')
							.addClass('envira-draft-item');
						$list_view
							.removeClass('envira-active-item')
							.addClass('envira-draft-item');

						// Set the proper icons
						$icon.removeClass(
							'dashicons-visibility',
						).addClass('dashicons-hidden');

						// Set the text
						$text.text(envira_gallery_metabox.draft);

						// Set the Data
						$list_view.data('status', 'pending');
						$grid_view.data('status', 'pending');
						$grid_view.attr(
							'data-envira-tooltip',
							envira_gallery_metabox.draft,
						);
					}
				}, this);

				// Deselect all images by triggering the change event on the 'Select All' checkbox
				jQuery('nav.envira-tab-options input[type=checkbox]')
					.prop('checked', false)
					.trigger('change');

				// Tell the view we've finished successfully
				this.trigger('loaded loaded:success');

				// Close the modal
				EnviraGalleryModalWindow.close();
			},
			error: function(error_message) {
				// Tell wp.media we've finished, but there was an error
				this.trigger('loaded loaded:error', error_message);
			},
		});
	},

	/**
	 * Inserts the direct media link for the Media Library item
	 *
	 * The button triggering this event is only displayed if we are editing a
	 * Media Library item, so there's no need to perform further checks
	 */
	insertMediaFileLink: function(event) {
		// Tell the View we're loading
		this.trigger('loading');

		// Update model
		this.model.set('link', response.media_link);

		// Tell the view we've finished successfully
		this.trigger('loaded loaded:success');

		// Re-render the view
		this.render();
	},

	/**
	 * Inserts the attachment page link for the Media Library item
	 *
	 * The button triggering this event is only displayed if we are editing a
	 * Media Library item, so there's no need to perform further checks
	 */
	insertAttachmentPageLink: function(event) {
		// Tell the View we're loading
		this.trigger('loading');

		// Update model
		this.model.set('link', response.media_link);

		// Tell the view we've finished successfully
		this.trigger('loaded loaded:success');

		// Re-render the view
		this.render();
	},
});

jQuery(document).ready(function($) {
	// Edit Images
	$('#envira-gallery-main').on(
		'click',
		'a.envira-gallery-images-edit',
		function(e) {
			// Prevent default action
			e.preventDefault();

			// (Re)populate the collection
			// The collection can change based on whether the user previously selected specific images
			EnviraGalleryImagesUpdate(true); // true = only selected images

			// Pass the collection of images for this gallery to the modal view, as well
			// as the selected attachment
			EnviraGalleryModalWindow.content(
				new EnviraGalleryBulkEditView({
					collection: EnviraGalleryImages,
					child_views: EnviraGalleryChildViews,
				}),
			);

			// Open the modal window
			EnviraGalleryModalWindow.open();
		},
	);
});

/**
 * Image Model
 */
var EnviraGalleryImage = Backbone.Model.extend({
	/**
	 * Defaults
	 * As we always populate this model with existing data, we
	 * leave these blank to just show how this model is structured.
	 */
	defaults: {
		id: '',
		title: '',
		caption: '',
		alt: '',
		link: '',
	},
});

/**
 * Images Collection
 * - Comprises of all images in an Envira Gallery
 * - Each image is represented by an EnviraGalleryImage Model
 */
var EnviraGalleryImages = new Backbone.Collection();

/**
 * Modal Window
 * - Used by most Envira Backbone views to display information e.g. bulk edit, edit single image etc.
 */
if (typeof EnviraGalleryModalWindow == 'undefined') {
	var EnviraGalleryModalWindow = new wp.media.view.Modal({
		controller: {
			trigger: function() {},
		},
	});
}

/**
 * View
 */
var EnviraGalleryEditView = wp.Backbone.View.extend({
	/**
	 * The Tag Name and Tag's Class(es)
	 */
	tagName: 'div',
	className: 'edit-attachment-frame mode-select hide-menu hide-router',

	/**
	 * Template
	 * - The template to load inside the above tagName element
	 */
	template: wp.template('envira-meta-editor'),

	/**
	 * Events
	 * - Functions to call when specific events occur
	 */
	events: {
		'click .edit-media-header .left': 'loadPreviousItem',
		'click .edit-media-header .right': 'loadNextItem',

		'keyup input': 'updateItem',
		'keyup textarea': 'updateItem',
		'change input': 'updateItem',
		'change textarea': 'updateItem',
		'blur textarea': 'updateItem',
		'change select': 'updateItem',

		'click .actions a.envira-gallery-meta-submit': 'saveItem',

		'keyup input#link-search': 'searchLinks',
		'click div.query-results li': 'insertLink',

		'click button.media-file': 'insertMediaFileLink',
		'click button.attachment-page': 'insertAttachmentPageLink',
	},

	/**
	 * Initialize
	 *
	 * @param object model   EnviraGalleryImage Backbone Model
	 */
	initialize: function(args) {
		// Define loading and loaded events, which update the UI with what's happening.
		this.on('loading', this.loading, this);
		this.on('loaded', this.loaded, this);

		// Set some flags
		this.is_loading = false;
		this.collection = args.collection;
		this.child_views = args.child_views;
		this.attachment_id = args.attachment_id;
		this.attachment_index = 0;
		this.search_timer = '';

		// Get the model from the collection
		var count = 0;
		this.collection.each(function(model) {
			// If this model's id matches the attachment id, this is the model we want
			if (model.get('id') == this.attachment_id) {
				this.model = model;
				this.attachment_index = count;
				return false;
			}
			// Increment the index count
			count++;
		}, this);
	},

	/**
	 * Render
	 * - Binds the model to the view, so we populate the view's fields and data
	 */
	render: function() {
		// Get HTML
		this.$el.html(this.template(this.model.attributes));
		// If any child views exist, render them now
		if (this.child_views.length > 0) {
			this.child_views.forEach(function(view) {
				// Init with model
				var child_view = new view({
					model: this.model,
				});

				// Render view within our main view
				this.$el
					.find('div.envira-addons')
					.append(child_view.render().el);
			}, this);
		}

		// Set caption
		this.$el
			.find('textarea[name=caption]')
			.val(this.model.get('caption'));

		// Init QuickTags on the caption editor
		// Delay is required for the first load for some reason
		setTimeout(function() {
			quicktags({
				id: 'caption',
				buttons: 'strong,em,link,ul,ol,li,close',
			});
			QTags._buttonsInit();
		}, 500);

		// Init Link Searching
		wpLink.init;

		// Enable / disable the buttons depending on the index
		if (this.attachment_index == 0) {
			// Disable left button
			this.$el.find('button.left').addClass('disabled');
		}
		if (this.attachment_index == this.collection.length - 1) {
			// Disable right button
			this.$el.find('button.right').addClass('disabled');
		}

		jQuery(document).trigger('enviraRenderMeta');

		// Return
		return this;
	},

	/**
	 * Renders an error using
	 * wp.media.view.EnviraGalleryError
	 */
	renderError: function(error) {
		// Define model
		var model = {};
		model.error = error;

		// Define view
		var view = new wp.media.view.EnviraGalleryError({
			model: model,
		});

		// Return rendered view
		return view.render().el;
	},

	/**
	 * Tells the view we're loading by displaying a spinner
	 */
	loading: function() {
		// Set a flag so we know we're loading data
		this.is_loading = true;

		// Show the spinner
		this.$el.find('.spinner').css('visibility', 'visible');
	},

	/**
	 * Hides the loading spinner
	 */
	loaded: function(response) {
		// Set a flag so we know we're not loading anything now
		this.is_loading = false;

		// Hide the spinner
		this.$el.find('.spinner').css('visibility', 'hidden');

		// Display the error message, if it's provided
		if (typeof response !== 'undefined') {
			this.$el
				.find('div.media-toolbar')
				.after(this.renderError(response));
		}
	},

	/**
	 * Load the previous model in the collection
	 */
	loadPreviousItem: function(event) {
		// Save and Update So User Doesn't Have To Press Update Button?
		this.saveItem(event);
		this.updateItem(event);

		// Decrement the index
		this.attachment_index--;

		// Get the model at the new index from the collection
		this.model = this.collection.at(this.attachment_index);

		// Update the attachment_id
		this.attachment_id = this.model.get('id');

		// Re-render the view
		this.render();
	},

	/**
	 * Load the next model in the collection
	 */
	loadNextItem: function(event) {
		// Save and Update So User Doesn't Have To Press Update Button?
		this.saveItem(event);
		this.updateItem(event);

		// Increment the index
		this.attachment_index++;

		// Get the model at the new index from the collection
		this.model = this.collection.at(this.attachment_index);

		// Update the attachment_id
		this.attachment_id = this.model.get('id');

		// Re-render the view
		this.render();
	},

	/**
	 * Updates the model based on the changed view data
	 */
	updateItem: function(event) {
		let value;
		// Check if the target has a name. If not, it's not a model value we want to store
		if (event.target.name == '') {
			return;
		}

		// Update the model's value, depending on the input type
		if (event.target.type == 'checkbox') {
			value = event.target.checked ? event.target.value : 0;
		} else {
			value = event.target.value;
		}

		// Update the model
		this.model.set(event.target.name, value);
	},

	/**
	 * Saves the image metadata
	 */
	saveItem: function(event) {
		event.preventDefault();

		var self = this;

		// Tell the View we're loading
		this.trigger('loading');

		// Make an AJAX request to save the image metadata
		wp.media.ajax('envira_gallery_save_meta', {
			context: this,
			data: {
				nonce: envira_gallery_metabox.save_nonce,
				post_id: envira_gallery_metabox.id,
				attach_id: this.model.get('id'),
				meta: this.model.attributes,
			},
			success: function(response) {
				// Tell the view we've finished successfully
				self.trigger('loaded loaded:success');

				// Assign the model's JSON string back to the underlying item
				var item = JSON.stringify(self.model.attributes),
					item_element = jQuery(
						'ul#envira-gallery-output li#' +
						self.model.get('id'),
					);

				// Assign the JSON
				jQuery(item_element).attr(
					'data-envira-gallery-image-model',
					item,
				);

				// Update the title and hint
				jQuery('div.meta div.title span', item_element).text(
					self.model.get('title'),
				);
				jQuery('div.meta div.title a.hint', item_element).attr(
					'title',
					self.model.get('title'),
				);

				// Display or hide the title hint depending on the title length
				if (self.model.get('title').length > 20) {
					jQuery(
						'div.meta div.title a.hint',
						item_element,
					).removeClass('hidden');
				} else {
					jQuery(
						'div.meta div.title a.hint',
						item_element,
					).addClass('hidden');
				}

				// Show the user the 'saved' notice for 1.5 seconds
				var saved = self.$el.find('.saved');
				saved.fadeIn();
				setTimeout(function() {
					saved.fadeOut();
				}, 1500);
			},
			error: function(error_message) {
				// Tell wp.media we've finished, but there was an error
				self.trigger('loaded loaded:error', error_message);
			},
		});
	},

	/**
	 * Searches Links
	 */
	searchLinks: function(event) {},

	/**
	 * Inserts the clicked link into the URL field
	 */
	insertLink: function(event) {},

	/**
	 * Inserts the direct media link for the Media Library item
	 *
	 * The button triggering this event is only displayed if we are editing a
	 * Media Library item, so there's no need to perform further checks
	 */
	insertMediaFileLink: function(event) {
		// Tell the View we're loading
		this.trigger('loading');

		// Make an AJAX request to get the media link
		wp.media.ajax('envira_gallery_get_attachment_links', {
			context: this,
			data: {
				nonce: envira_gallery_metabox.save_nonce,
				attachment_id: this.model.get('id'),
			},
			success: function(response) {
				// Update model
				this.model.set('link', response.media_link);

				// Tell the view we've finished successfully
				this.trigger('loaded loaded:success');

				// Re-render the view
				this.render();
			},
			error: function(error_message) {
				// Tell wp.media we've finished, but there was an error
				this.trigger('loaded loaded:error', error_message);
			},
		});
	},

	/**
	 * Inserts the attachment page link for the Media Library item
	 *
	 * The button triggering this event is only displayed if we are editing a
	 * Media Library item, so there's no need to perform further checks
	 */
	insertAttachmentPageLink: function(event) {
		// Tell the View we're loading
		this.trigger('loading');

		// Make an AJAX request to get the media link
		wp.media.ajax('envira_gallery_get_attachment_links', {
			context: this,
			data: {
				nonce: envira_gallery_metabox.save_nonce,
				attachment_id: this.model.get('id'),
			},
			success: function(response) {
				// Update model
				this.model.set('link', response.attachment_page);

				// Tell the view we've finished successfully
				this.trigger('loaded loaded:success');

				// Re-render the view
				this.render();
			},
			error: function(error_message) {
				// Tell wp.media we've finished, but there was an error
				this.trigger('loaded loaded:error', error_message);
			},
		});
	},
});

/**
 * Sub Views
 * - Addons must populate this array with their own Backbone Views, which will be appended
 * to the settings region
 */
var EnviraGalleryChildViews = [];

/**
 * Populates the EnviraGalleryImages Backbone collection, which comprises of a set of Envira Gallery Images
 *
 * Called when images are added, deleted, reordered or selected
 *
 * @global           EnviraGalleryImages     The backbone collection of images
 * @param    bool    selected_only           Only populate collection with images the user has selected
 */
 function EnviraGalleryImagesUpdate(selected_only) {
	// Clear the collection
	EnviraGalleryImages.reset();

	// Iterate through the gallery images in the DOM, adding them to the collection
	var selector =
		'ul#envira-gallery-output li.envira-gallery-image' +
		(selected_only ? '.selected' : ''),
		the_image_model = '';

	jQuery(selector).each(function() {

		the_image_model = jQuery(this).attr('data-envira-gallery-image-model');
		the_image_model = the_image_model.replace(/(\r\n|\n|\r)/gm, ""); // remove any line breaks in the JSON (see GH 4254)

		// Build an EnviraGalleryImage Backbone Model from the JSON supplied in the element
		var envira_gallery_image = jQuery.parseJSON(
			the_image_model,
		);

		// Strip slashes from some fields
		envira_gallery_image.alt = EnviraGalleryStripslashes(
			envira_gallery_image.alt,
		);

		// Add the model to the collection
		EnviraGalleryImages.add(new EnviraGalleryImage(envira_gallery_image));
	});

	// Update the count in the UI
	// jQuery( '#envira-gallery-main span.count' ).text( jQuery( 'ul#envira-gallery-output li.envira-gallery-image' ).length );
}

/**
 * Strips slashes from the given string, which may have been added to escape certain characters
 *
 * @since 1.4.2.0
 *
 * @param    string  str     String
 * @return   string          String without slashes
 */
function EnviraGalleryStripslashes(str) {
	return (str + '').replace(/\\(.?)/g, function(s, n1) {
		switch (n1) {
			case '\\':
				return '\\';
			case '0':
				return '\u0000';
			case '':
				return '';
			default:
				return n1;
		}
	});
}

/**
 * Creates and handles a wp.media instance for Envira Galleries, allowing
 * the user to insert images from the WordPress Media Library into their Gallery
 */
jQuery(document).ready(function($) {
	// Select Files from Other Sources
	$('a.envira-media-library').on('click', function(e) {
		// Prevent default action
		e.preventDefault();

		// If the wp.media.frames.envira instance already exists, reopen it
		if (wp.media.frames.envira) {
			wp.media.frames.envira.open();
			return;
		} else {
			// Create the wp.media.frames.envira instance (one time)
			wp.media.frames.envira = wp.media({
				frame: 'post',
				title: wp.media.view.l10n.insertIntoPost,
				library: {
					type: ['image'],
				},
				button: {
					text: wp.media.view.l10n.insertIntoPost,
				},
				multiple: true,
			});
		}

		// Mark existing Gallery images as selected when the modal is opened
		wp.media.frames.envira.on('open', function() {
			// Get any previously selected images
			var selection = wp.media.frames.envira.state().get('selection');

			// Get images that already exist in the gallery, and select each one in the modal
			$('ul#envira-gallery-output li').each(function() {
				var attachment = wp.media.attachment($(this).attr('id'));
				if (selection != undefined) {
					selection.add(attachment ? [attachment] : []);
				}
			});
		});

		// Insert into Gallery Button Clicked
		wp.media.frames.envira.on('insert', function(selection) {
			// Get state
			var state = wp.media.frames.envira.state(),
				images = [];

			// Iterate through selected images, building an images array
			selection.each(function(attachment) {
				// Get the chosen options for this image (size, alignment, link type, link URL)
				var display = state.display(attachment).toJSON();

				// Change the image link parameter based on the "Link To" setting the user chose in the media view
				switch (display.link) {
					case 'none':
						// Because users cry when their images aren't linked, we need to actually set this to the attachment URL
						attachment.set('link', attachment.get('url'));
						break;
					case 'file':
						attachment.set('link', attachment.get('url'));
						break;
					case 'post':
						// Already linked to post by default
						break;
					case 'custom':
						attachment.set('link', display.linkUrl);
						break;
				}

				// Add the image to the images array
				images.push(attachment.toJSON());
			}, this);

			// Make visible the "items are being added"
			$(document)
				.find('.envira-progress-adding-images')
				.css('display', 'block');

			// Send the ajax request with our data to be processed.
			$.post(
				envira_gallery_metabox.ajax,
				{
					action: 'envira_gallery_insert_images',
					nonce: envira_gallery_metabox.insert_nonce,
					post_id: envira_gallery_metabox.id,
					order: $('#envira-config-image-sort').val(),
					direction: $('#envira-config-image-sort-dir').val(),
					// make this a JSON string so we can send larger amounts of data (images), otherwise max is around 20 by default for most server configs
					images: JSON.stringify(images),
				},
				function(response) {
					// Response should be a JSON success with the HTML for the image grid
					if (response && response.success) {
						// Set the image grid to the HTML we received
						$('#envira-gallery-output').html(
							response.success,
						);

						$(document).trigger('enviraInsert');

						// Repopulate the Envira Gallery Image Collection
						EnviraGalleryImagesUpdate(false);

						$(document)
							.find('.envira-progress-adding-images')
							.css('display', 'none');
					}
				},
				'json',
			);
		});

		// Open the media frame
		wp.media.frames.envira.open();
		// Remove the 'Create Gallery' left hand menu item in the modal, as we don't
		// want users inserting galleries!
		$('div.media-menu #menu-item-gallery').css('display', 'none');
		$('div.media-menu a.media-menu-item:nth-child(4)').addClass('hidden');
		$('div.media-menu #menu-item-playlist').css('display', 'none');
		$('div.media-menu #menu-item-video-playlist').css('display', 'none');
		$('div.media-menu #menu-item-featured-image').css('display', 'none');

		return;
	});
});

/* global envira_gallery_metabox, wp */
/**
 * Handles Mangement functions, deselection and sorting of media in an Envira gallery
 */
var envira_manage = window.envira_manage || {};

(function($, window, document, envira_manage, envira_gallery_metabox) {
	'use strict';
	// Setup some vars
	var output = '#envira-gallery-output',
		list = $(output + ' li').length,
		shift_key_pressed = false,
		last_selected_image = false;

	window.envira_manage = envira_manage = {
		init: function() {
			var self = this;

			// Select Functions
			self.select();
			self.select_all();
			self.clear_selected();
			self.image_select();

			// Sortable
			self.sortable();
			self.sort_images();

			// List/Grid Display
			self.display_toggle();

			// Items
			self.delete_item();
			self.bulk_delete();
			self.edit_meta();
			self.toggle_status();

			self.tooltip();

			// Determine whether the shift key is pressed or not
			$(document).on('keyup keydown', function(e) {
				shift_key_pressed = e.shiftKey;
			});

			// Filter Functions
			self.image_filter();

			// Envira Admin Init Trigger
			$(document).trigger('envriaAdminInit');
		},

		image_select: function () {
			$('#envira-config-layouts').imagepicker({show_label: true})
		},
		image_filter: function () {

			$( '#envira-filter-images' ).on(
				'keyup',
				function () {

					var $this = $( this ),
					val       = $this.val(),
					items     = $( '.envira-gallery-image' ),
					selected  = '';

					if (val != '') {

						items.each(
							function (e) {

								if ($( this ).find( '.title' ).text().search( val ) > -1) {

									$( this ).show().fadeIn( 1000 );

								} else {

									$( this ).hide().fadeOut( 1000 );
									$( this ).removeClass( 'selected' );
									// Get the new selected count
									selected = $( output + ' li.selected' ).length;

									last_selected_image = false;

									if (selected !== 0) {

										$( '.select-all' ).text( envira_gallery_metabox.selected );
										$( '.envira-count' ).text( selected.toString() );
										$( '.envira-clear-selected' ).fadeIn();

									} else {

										list = $( output + ' li' ).length;

										$( '.select-all' ).text( envira_gallery_metabox.select_all );
										$( '.envira-count' ).text( list.toString() );
										$( '.envira-clear-selected' ).fadeOut();


								$('.select-all').text(
									envira_gallery_metabox.select_all,
								);
								$('.envira-count').text(
									list.toString(),
								);
								$('.envira-clear-selected').fadeOut();
							}
						}
					});
				} else {
					items.show();
					// show all items
				}
			});
		},
		// Toggle Image States
		toggle_status: function() {
			$(output).on(
				'click.enviraStatus',
				'.envira-item-status',
				function(e) {
					// Prevent default action
					e.preventDefault();
					e.stopPropagation();

					var $this = $(this),
						$data = $this.data('status'),
						$parent = $this.hasClass('list-status')
							? $this
									.parent()
									.parent()
									.parent()
							: $this.parent(),
						$list_view = $parent.find(
							'.envira-item-status.list-status',
						),
						$grid_view = $parent.find(
							'.envira-item-status.grid-status',
						),
						id = $this.data('id'),
						$icon = $grid_view.find('span.dashicons'),
						$text = $list_view.find('span'),
						$status =
							$data === 'active' ? 'pending' : 'active',
						opts = {
							url: envira_gallery_metabox.ajax,
							type: 'post',
							async: true,
							cache: false,
							dataType: 'json',
							data: {
								action: 'envira_change_image_status',
								post_id: envira_gallery_metabox.id,
								gallery_id: id,
								status: $status,
								nonce:
									envira_gallery_metabox.save_nonce,
							},
							success: function(response) {
								if (response.success) {
									if ($status === 'active') {
										// Toggle Classes
										$grid_view
											.removeClass(
												'envira-draft-item',
											)
											.addClass(
												'envira-active-item',
											);
										$list_view
											.removeClass(
												'envira-draft-item',
											)
											.addClass(
												'envira-active-item',
											);

										// Set the proper icons
										$icon.removeClass(
											'dashicons-hidden',
										).addClass(
											'dashicons-visibility',
										);

										// Set the Text
										$text.text(
											envira_gallery_metabox.active,
										);

										$grid_view.attr(
											'data-envira-tooltip',
											envira_gallery_metabox.active,
										);

										// Set the Data
										$list_view.data(
											'status',
											'active',
										);
										$grid_view.data(
											'status',
											'active',
										);
									} else {
										// Toggle Classes
										$grid_view
											.removeClass(
												'envira-active-item',
											)
											.addClass(
												'envira-draft-item',
											);
										$list_view
											.removeClass(
												'envira-active-item',
											)
											.addClass(
												'envira-draft-item',
											);

										// Set the proper icons
										$icon.removeClass(
											'dashicons-visibility',
										).addClass(
											'dashicons-hidden',
										);

										// Set the text
										$text.text(
											envira_gallery_metabox.draft,
										);
										// Set the Data
										$list_view.data(
											'status',
											'pending',
										);
										$grid_view.data(
											'status',
											'pending',
										);
										$grid_view.attr(
											'data-envira-tooltip',
											envira_gallery_metabox.draft,
										);
									}

									$(document).trigger(
										'envriaChangeStatus ',
									);
								}
							},
							error: function(xhr, textStatus, e) {
								return;
							},
						};

					$.ajax(opts);
				},
			);
		},

		// Simple Tooltip
		tooltip: function() {
			$('[data-envira-tooltip]').on('mouseover', function(e) {
				e.preventDefault();
				var $this = $(this),
					$data = $this.data('envira-tooltip');
			});
		},

		// Select All images
		select_all: function() {
			// Toggle Select All / Deselect All
			$(document).on(
				'change',
				'nav.envira-tab-options input',
				function(e) {
					if ($(this).prop('checked')) {
						$('li', $(output)).addClass('selected');
						$('nav.envira-select-options').fadeIn();

						var selected = $(output + ' li.selected').length;
						$('.select-all').text(
							envira_gallery_metabox.selected,
						);
						$('.envira-count').text(selected.toString());
						$('.envira-clear-selected').fadeIn();
					} else {
						$('li', $(output)).removeClass('selected');
						$('nav.envira-select-options').fadeOut();
						list = $(output + ' li').length;

						$('.select-all').text(
							envira_gallery_metabox.select_all,
						);
						$('.envira-count').text(list.toString());
						$('.envira-clear-selected').fadeOut();
					}

					$(document).trigger('enviraSelectAll');
				},
			);
		},

		// Sort Images
		sort_images: function() {
			$(document).on(
				'change',
				'#envira-config-image-sort, #envira-config-image-sort-dir',
				function() {
					var $this = $(this),
						$sort = $('#envira-config-image-sort').val(),
						$direction = $(
							'#envira-config-image-sort-dir',
						).val(),
						opts = {
							url: envira_gallery_metabox.ajax,
							type: 'post',
							async: true,
							cache: false,
							dataType: 'json',
							data: {
								action: 'envira_sort_publish',
								post_id: envira_gallery_metabox.id,
								order: $sort,
								direction: $direction,
								nonce:
									envira_gallery_metabox.save_nonce,
							},
							success: function(response) {
								// Response should be a JSON success with the HTML for the image grid
								if (response) {
									// Set the image grid to the HTML we received
									$(output).html(response.data);

									EnviraGalleryImagesUpdate(false);

									if (
										$sort === 'manual' ||
										$sort == '0'
									) {
										$(output).attr(
											'data-sortable',
											'1',
										);
									} else {
										$(output).attr(
											'data-sortable',
											'0',
										);
									}

									// Re-Trigger sortable
									envira_manage.sortable();
								}
							},
							error: function(xhr, textStatus, e) {
								return;
							},
						};

					$.ajax(opts);
				},
			);
		},

		// Drag and drop
		sortable: function() {
			var is_sortable = $(output).attr('data-sortable');

			if (is_sortable === '1') {
				if ($(output).hasClass('ui-sortable')) {
					$(output).sortable('enable');
				}

				// Add sortable support to Envira Gallery Media items
				$(output).sortable({
					containment: output,
					items: 'li',
					cursor: 'move',
					forcePlaceholderSize: true,
					placeholder: 'dropzone',
					helper: function(e, item) {
						// Basically, if you grab an unhighlighted item to drag, it will deselect (unhighlight) everything else
						if (!item.hasClass('selected')) {
							item.addClass('selected')
								.siblings()
								.removeClass('selected');
						}

						// Clone the selected items into an array
						var elements = item
							.parent()
							.children('.selected')
							.clone();

						// Add a property to `item` called 'multidrag` that contains the
						// selected items, then remove the selected items from the source list
						item.data('multidrag', elements)
							.siblings('.selected')
							.remove();

						// Now the selected items exist in memory, attached to the `item`,
						// so we can access them later when we get to the `stop()` callback
						// Create the helper
						var helper = $('<li/>');
						return helper.append(elements);
					},
					stop: function(e, ui) {
						// Remove the helper so we just display the sorted items
						var elements = ui.item.data('multidrag');
						ui.item.after(elements).remove();

						// Send AJAX request to store the new sort order
						$.ajax({
							url: envira_gallery_metabox.ajax,
							type: 'post',
							async: true,
							cache: false,
							dataType: 'json',
							data: {
								action: 'envira_gallery_sort_images',
								order: $(output)
									.sortable('toArray')
									.toString(),
								post_id: envira_gallery_metabox.id,
								nonce: envira_gallery_metabox.sort,
							},
							success: function(response) {
								// Repopulate the Envira Gallery Backbone Image Collection
								EnviraGalleryImagesUpdate(false);
								return;
							},
							error: function(xhr, textStatus, e) {
								// Inject the error message into the tab settings area
								$(output).before(
									'<div class="error"><p>' +
										textStatus.responseText +
										'</p></div>',
								);
							},
						});
					},
				});
			} else {
				if ($(output).hasClass('ui-sortable')) {
					$(output).sortable('disable');
				}
			}
		},

		// Select Single Images
		select: function() {
			// Select / deselect images
			$(document).on(
				'click',
				'ul#envira-gallery-output li.envira-gallery-image > img, li.envira-gallery-image > div, li.envira-gallery-image > a.check',
				function(e) {
					// Prevent default action
					e.preventDefault();

					// Get the selected gallery item
					var $this = $(this),
						$gallery_item = $this.parent(),
						selected = '';

					if ($gallery_item.hasClass('selected')) {
						$gallery_item.removeClass('selected');

						// Get the new selected count
						selected = $(output + ' li.selected').length;

						last_selected_image = false;

						if (selected !== 0) {
							$('.select-all').text(
								envira_gallery_metabox.selected,
							);
							$('.envira-count').text(selected.toString());
							$('.envira-clear-selected').fadeIn();
						} else {
							list = $(output + ' li').length;

							$('.select-all').text(
								envira_gallery_metabox.select_all,
							);
							$('.envira-count').text(list.toString());
							$('.envira-clear-selected').fadeOut();
						}
					} else {
						// If the shift key is being held down, and there's another image selected, select every image between this clicked image
						// and the other selected image
						if (
							shift_key_pressed &&
							last_selected_image !== false
						) {
							// Get index of the selected image and the last image
							var start_index = $(
									'ul#envira-gallery-output li',
								).index($(last_selected_image)),
								end_index = $(
									'ul#envira-gallery-output li',
								).index($($gallery_item)),
								i = 0;

							// Select images within the range
							if (start_index < end_index) {
								for (
									i = start_index;
									i <= end_index;
									i++
								) {
									$(
										'ul#envira-gallery-output li:eq( ' +
											i +
											')',
									).addClass('selected');
								}
							} else {
								for (
									i = end_index;
									i <= start_index;
									i++
								) {
									$(
										'ul#envira-gallery-output li:eq( ' +
											i +
											')',
									).addClass('selected');
								}
							}
						}

						// Select the clicked image
						$($gallery_item).addClass('selected');

						last_selected_image = $($gallery_item);

						selected = $(output + ' li.selected').length;
						$('.envira-clear-selected').fadeIn();

						$('.select-all').text(
							envira_gallery_metabox.selected,
						);
						$('.envira-count').text(selected.toString());
					}

					// Show/hide buttons depending on whether
					// any galleries have been selected
					if (
						$('ul#envira-gallery-output > li.selected')
							.length > 0
					) {
						$('nav.envira-select-options').fadeIn();
					} else {
						$('nav.envira-select-options').fadeOut();
					}
				},
			);
		},

		// Clear Selection
		clear_selected: function() {
			$('.envira-clear-selected').on('click', function(e) {
				e.preventDefault();

				$(output + ' li.selected').removeClass('selected');

				list = $(output + ' li').length;

				$('.select-all').text(envira_gallery_metabox.select_all);
				$('.envira-count').text(list.toString());
				$('.envira-select-all').prop('checked', false);
				$('nav.envira-select-options').fadeOut();

				$(this).fadeOut();

				$(document).trigger('enviraClearSelected');
			});
		},

		// Toggle List / Grid View
		display_toggle: function() {
			$(document).on('click', 'nav.envira-tab-options a', function(e) {
				e.preventDefault();

				// Get the view the user has chosen
				var envira_tab_nav = $(this).closest('.envira-tab-options'),
					envira_tab_view = $(this).data('view'),
					envira_tab_view_style = $(this).data('view-style');

				// If this view style is already displayed, don't do anything
				if ($(envira_tab_view).hasClass(envira_tab_view_style)) {
					return;
				}

				// Update the view class
				$(envira_tab_view)
					.removeClass('list')
					.removeClass('grid')
					.addClass(envira_tab_view_style);

				// Mark the current view icon as selected
				$('a', envira_tab_nav).removeClass('selected');
				$(this).addClass('selected');

				// Send an AJAX request to store this user's preference for the view
				// This means when they add or edit any other Gallery, the image view will default to this setting
				$.ajax({
					url: envira_gallery_metabox.ajax,
					type: 'post',
					dataType: 'json',
					data: {
						action: 'envira_gallery_set_user_setting',
						name: 'envira_gallery_image_view',
						value: envira_tab_view_style,
						nonce:
							envira_gallery_metabox.set_user_setting_nonce,
					},
					success: function(response) {
						$(document).trigger('enviraDisplayToggle');
					},
					error: function(xhr, textStatus, e) {
						// Inject the error message into the tab settings area
						$(envira_gallery_output).before(
							'<div class="error"><p>' +
								textStatus.responseText +
								'</p></div>',
						);
					},
				});
			});
		},

		// Chosen Select boxes
		chosen: function() {
			// Create the Select boxes
			$('.envira-chosen').each(function() {
				alert('b');

				// Get the options from the data.
				var data_options = $(this).data('envira-chosen-options');

				$(this).chosen(data_options);
			});
		},

		// Update Item Count
		update_count: function() {
			list = $(output + ' li').length;

			// update the count value
			$('.envira-count').text(list.toString());

			if (list > 0) {
				$('#envira-empty-itemr')
					.fadeOut()
					.addClass('envira-hidden');
				$('.envira-item-header')
					.removeClass('envira-hidden')
					.fadeIn();
				$('.envira-bulk-actions').fadeOut();
			}
		},

		// Deletes an items out of the gallery
		delete_item: function() {
			/**
			 * Delete Single Image
			 */
			$(document).on(
				'click',
				'#envira-gallery-main .envira-gallery-remove-image',
				function(e) {
					e.preventDefault();

					// Bail out if the user does not actually want to remove the image.
					var confirm_delete = confirm(
						envira_gallery_metabox.remove,
					);
					if (!confirm_delete) {
						return;
					}

					// Send an AJAX request to delete the selected items from the Gallery
					var attach_id = $(this)
						.parent()
						.attr('id');
					$.ajax({
						url: envira_gallery_metabox.ajax,
						type: 'post',
						dataType: 'json',
						data: {
							action: 'envira_gallery_remove_image',
							attachment_id: attach_id,
							post_id: envira_gallery_metabox.id,
							nonce: envira_gallery_metabox.remove_nonce,
						},
						success: function(response) {
							$('#' + attach_id).fadeOut(
								'normal',
								function() {
									$(this).remove();

									// Refresh the modal view to ensure no items are still checked if they have been removed.
									$('.envira-gallery-load-library')
										.attr(
											'data-envira-gallery-offset',
											0,
										)
										.addClass('has-search')
										.trigger('click');

									// Repopulate the Envira Gallery Image Collection
									EnviraGalleryImagesUpdate(false);

									envira_manage.start_screen();
								},
							);
						},
						error: function(xhr, textStatus, e) {
							// Inject the error message into the tab settings area
							$(envira_gallery_output).before(
								'<div class="error"><p>' +
									textStatus.responseText +
									'</p></div>',
							);
						},
					});
				},
			);
		},
		// Bulk Deletes selected items
		bulk_delete: function() {
			/**
			 * Delete Multiple Images
			 */
			$(document).on(
				'click',
				'a.envira-gallery-images-delete',
				function(e) {
					e.preventDefault();

					// Bail out if the user does not actually want to remove the image.
					var confirm_delete = confirm(
						envira_gallery_metabox.remove_multiple,
					);
					if (!confirm_delete) {
						return false;
					}

					// Build array of image attachment IDs
					var attach_ids = [];
					$('ul#envira-gallery-output > li.selected').each(
						function() {
							attach_ids.push($(this).attr('id'));
						},
					);

					// Send an AJAX request to delete the selected items from the Gallery
					var attach_id = $(this)
						.parent()
						.attr('id');
					$.ajax({
						url: envira_gallery_metabox.ajax,
						type: 'post',
						dataType: 'json',
						data: {
							action: 'envira_gallery_remove_images',
							attachment_ids: attach_ids,
							post_id: envira_gallery_metabox.id,
							nonce: envira_gallery_metabox.remove_nonce,
						},
						success: function(response) {
							if (response) {
								// Remove each image
								$(output + ' > li.selected').remove();

								// Hide Select Options
								$(
									'nav.envira-select-options',
								).fadeOut();

								// Refresh the modal view to ensure no items are still checked if they have been removed.
								$('.envira-gallery-load-library')
									.attr(
										'data-envira-gallery-offset',
										0,
									)
									.addClass('has-search')
									.trigger('click');

								// Repopulate the Envira Gallery Image Collection
								EnviraGalleryImagesUpdate(false);
								envira_manage.update_count();
								envira_manage.start_screen();
								$('.envira-select-all').prop(
									'checked',
									false,
								);
							}
						},
						error: function(xhr, textStatus, e) {
							// Inject the error message into the tab settings area
							$(envira_gallery_output).before(
								'<div class="error"><p>' +
									textStatus.responseText +
									'</p></div>',
							);
						},
					});
				},
			);
		},

		// Trigger edit meta screen
		edit_meta: function() {
			// Edit Image
			$(document).on(
				'click',
				'#envira-gallery-main a.envira-gallery-modify-image',
				function(e) {
					// Prevent default action
					e.preventDefault();

					// (Re)populate the collection
					// The collection can change based on whether the user previously selected specific images
					EnviraGalleryImagesUpdate(false);

					// Get the selected attachment
					var attachment_id = $(this)
						.parent()
						.data('envira-gallery-image');

					// Pass the collection of images for this gallery to the modal view, as well
					// as the selected attachment
					EnviraGalleryModalWindow.content(
						new EnviraGalleryEditView({
							collection: EnviraGalleryImages,
							child_views: EnviraGalleryChildViews,
							attachment_id: attachment_id,
						}),
					);

					// Open the modal window
					EnviraGalleryModalWindow.open();

					$(document).trigger('enviraEditOpen');
				},
			);
		},

		start_screen: function() {
			// Get Slide Count
			list = $(output + ' li').length;

			// If there are no slides
			if (list === 0) {
				// Make sure bulk actions are out of view
				$('nav.envira-select-options').fadeOut();

				// Fade out Settings header
				$('.envira-content-images')
					.fadeOut()
					.addClass('envira-hidden');

				// Add Empty Slider Content
				$('#envira-empty-gallery')
					.removeClass('envira-hidden')
					.fadeIn();
			} else {
				// Fade out Settings header
				$('#envira-empty-gallery')
					.fadeOut()
					.addClass('envira-hidden');

				// Add Empty Slider Content
				$('.envira-content-images')
					.removeClass('envira-hidden')
					.fadeIn();
			}
		},
		update_selection: function() {},
	};

	// DOM ready
	$(function() {
		envira_manage.init();
	});

	// Re init on type change
	$(document).on('enviraGalleryType', function() {
		envira_manage.init();
	});

	// Update slide count
	$(document).on('enviraInsert', function() {
		envira_manage.start_screen();
		envira_manage.update_count();
	});
})(jQuery, window, document, envira_manage, envira_gallery_metabox);

/**
 * Handles moving media from the on-screen Gallery to another Gallery,
 * by displaying the gallery-select.js Backbone Modal and running
 * the necessary AJAX command once the user has chosen a Gallery and
 * clicked the Move button
 *
 * @since 1.5.0.3
 */
jQuery(document).ready(function($) {
	// Edit Images
	$('#envira-gallery-main').on(
		'click',
		'a.envira-gallery-images-move',
		function(e) {
			// Prevent default action
			e.preventDefault();

			// Get the action
			var action = $(this).data('action');

			// Define the modal's view
			EnviraGalleryModalWindow.content(
				new EnviraGallerySelectionView({
					action: action, // gallery|album
					multiple: false, // Allow multiple Galleries / Albums to be selected
					sidebar_view: 'envira-meta-move-media-sidebar',
					modal_title:
						envira_gallery_metabox.move_media_modal_title,
					insert_button_label:
						envira_gallery_metabox.move_media_insert_button_label,
					onInsert: function() {
						// Refresh the underlying collection of selected images now
						EnviraGalleryImagesUpdate(true); // true = only selected images

						// Build array of imag	es
						var envira_gallery_move_image_ids = [];
						EnviraGalleryImages.forEach(function(image) {
							envira_gallery_move_image_ids.push(
								image.get('id'),
							);
						});

						// Get the chosen Gallery
						// This forEach loop will only run once, as we only allow the user
						// to select a single gallery.
						this.selection.forEach(function(gallery) {
							// Perform AJAX request to move the given images from this gallery
							// to the selected gallery.
							// Action will be either:
							// envira_gallery_move_media
							// envira_albums_move_media
							wp.media.ajax(
								'envira_' + action + '_move_media',
								{
									context: this,
									data: {
										nonce:
											envira_gallery_metabox.move_media_nonce,
										from_gallery_id:
											envira_gallery_metabox.id,
										to_gallery_id: gallery.id,
										image_ids: envira_gallery_move_image_ids,
									},
									success: function(response) {
										// Remove each image from this Gallery, as the move was successful.
										$(
											'ul#envira-gallery-output > li.selected',
										).remove();

										// Hide Select Options
										$(
											'nav.envira-select-options',
										).fadeOut();

										// Repopulate the Envira Gallery Image Collection
										EnviraGalleryImagesUpdate(
											false,
										);

										// Close the modal
										EnviraGalleryModalWindow.close();
									},
									error: function(error_message) {
										alert(error_message);
									},
								},
							);
						});
					},
				}),
			);

			// Open the modal window
			EnviraGalleryModalWindow.open();
		},
	);
});

/**
 * Hooks into the global Plupload instance ('uploader'), which is set when includes/admin/metaboxes.php calls media_form()
 * We hook into this global instance and apply our own changes during and after the upload.
 *
 * @since 1.3.1.3
 */
(function($) {
	$(function() {
		if (typeof uploader !== 'undefined') {
			// Change "Select Files" button in the pluploader to "Select Files from Your Computer"
			$('input#plupload-browse-button').val(
				envira_gallery_metabox.uploader_files_computer,
			);

			// Set a custom progress bar
			var envira_bar = $('#envira-gallery .envira-progress-bar'),
				envira_progress = $(
					'#envira-gallery .envira-progress-bar div.envira-progress-bar-inner',
				),
				envira_status = $(
					'#envira-gallery .envira-progress-bar div.envira-progress-bar-status',
				),
				envira_output = $('#envira-gallery-output'),
				envira_error = $('#envira-gallery-upload-error'),
				envira_file_count = 0;

			// Uploader has initialized
			uploader.bind('Init', function(up) {
				// Fade in the uploader, as it's hidden with CSS so the user doesn't see elements reposition on screen and look messy.
				$('#drag-drop-area').fadeIn();
				$('a.envira-media-library.button').fadeIn();
			});

			// Files Added for Uploading
			uploader.bind('FilesAdded', function(up, files) {
				// Hide any existing errors
				$(envira_error).html('');

				// Get the number of files to be uploaded
				envira_file_count = files.length;

				// Set the status text, to tell the user what's happening
				$('.uploading .current', $(envira_status)).text('1');
				$('.uploading .total', $(envira_status)).text(
					envira_file_count,
				);
				$('.uploading', $(envira_status)).show();
				$('.done', $(envira_status)).hide();

				if (files[0].name.includes('.zip')) {
					$('.uploading').hide();
				}

				// Fade in the upload progress bar
				$(envira_bar).fadeIn('fast', function() {
					$('p.max-upload-size').css('padding-top', '10px');
				});
			});

			// File Uploading - show progress bar
			uploader.bind('UploadProgress', function(up, file) {
				// Update the status text
				if (file.name.includes('.zip')) {
					// If this is a zip file, display a different message...
					$('.uploading').hide();
					$('.opening_zip', $(envira_status)).show();
				} else {
					// ...otherwise display how far along we are in uploading the files
					$('.uploading .current', $(envira_status)).text(
						envira_file_count - up.total.queued + 1,
					);
				}

				// Update the progress bar
				$(envira_progress).css({
					width: up.total.percent + '%',
				});
			});

			// File Uploaded - AJAX call to process image and add to screen.
			uploader.bind('FileUploaded', function(up, file, info) {
				// Update the status text
				if (file.name.includes('.zip')) {
					// If this is a zip file, display a different message...
					$('.uploading').hide();
					$('.done').hide();
					$('.uploading_zip').hide();

					$('.envira_bar').show();
					$('.envira_status').show();
					$('.opening_zip').show();
				} else {
					// ...otherwise display how far along we are in uploading the files
					$('.uploading .current', $(envira_status)).text(
						envira_file_count - up.total.queued + 1,
					);
				}

				// AJAX call to Envira to store the newly uploaded image in the meta against this Gallery
				$.post(
					envira_gallery_metabox.ajax,
					{
						action: 'envira_gallery_load_image',
						nonce: envira_gallery_metabox.load_image,
						id: info.response,
						post_id: envira_gallery_metabox.id,
					},
					function(res) {
						// Prepend or append the new image to the existing grid of images,
						// depending on the media_position setting
						switch (envira_gallery_metabox.media_position) {
							case 'before':
								$(envira_output).prepend(res);
								break;
							case 'after':
							default:
								$(envira_output).append(res);
								break;
						}

						$(document).trigger('enviraInsert');

						// Repopulate the Envira Gallery Image Collection
						EnviraGalleryImagesUpdate(false);

						if (file.name.includes('.zip')) {
							$('.opening_zip').hide();
							$('.uploading_zip').hide();
							$('.done_zip', $(envira_status)).show();

							setTimeout(function() {
								$(envira_bar).fadeOut();
								$('.done_zip', $(envira_status)).hide();
								$('p.max-upload-size').css(
									'padding-top',
									'0',
								);
							}, 8000);
						}
					},
					'json',
				);
			});

			// Files Uploaded
			uploader.bind('UploadComplete', function(up, files) {
				if (files[files.length - 1].name.includes('.zip')) {
					// if this a zip file, return back and let fileuploaded handle it
					$('.done').hide();
					$('.uploading_zip', $(envira_status)).hide();
					return;
				}

				// Update status
				$('.uploading', $(envira_status)).hide();
				$('.done', $(envira_status)).show();

				// Hide Progress Bar
				setTimeout(function() {
					$(envira_bar).fadeOut('fast', function() {
						$('p.max-upload-size').css('padding-top', '0');
					});
				}, 1000);
			});

			// File Upload Error
			uploader.bind('Error', function(up, err) {
				// Show message
				$('#envira-gallery-upload-error').html(
					'<div class="error fade"><p>' +
						err.file.name +
						': ' +
						err.message +
						'</p></div>',
				);
				up.refresh();
			});
		}
	});
})(jQuery);
