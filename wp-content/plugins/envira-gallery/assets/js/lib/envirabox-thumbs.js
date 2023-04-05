// ==========================================================================
//
// Thumbs
// Displays thumbnails in a grid
//
// ==========================================================================
(function (document, $) {
	'use strict';

	const EnviraboxThumbs = function (instance) {
		this.instance = instance;
		this.init();
	};

	$.extend(EnviraboxThumbs.prototype, {
		$button: null,
		$grid: null,
		$list: null,
		isVisible: false,
		objects: null,

		init: function () {
			var self = this,
				handled = false,
				first = self.instance.group[0],
				second = self.instance.group[1];

			self.$button = self.instance.$refs.toolbar.find(
				'[data-envirabox-thumbs]',
			);

			if (
				self.instance.group.length > 1 &&
				self.instance.group[self.instance.currIndex].opts.thumbs &&
				(first.type == 'image' ||
					first.opts.thumb ||
					first.opts.$thumb) &&
				(second.type == 'image' ||
					second.opts.thumb ||
					second.opts.$thumb)
			) {
				self.$button.on('touchstart click', function (e) {
					e.stopImmediatePropagation();

					if (e.type == 'touchend') {
						handled = true;
						self.toggle();
					} else if (e.type == 'click' && !handled) {
						self.toggle();
					} else {
						handled = false;
					}
				});

				self.isActive = true;
			} else {
				self.$button.hide();

				self.isActive = false;
			}
		},

		create: function () {
			var self = this,
				instance = self.instance,
				options = instance.opts.thumbs,
				list,
				src,
				$the_height,
				thumbnail_play_icon,
				$row;

			self.$grid = $(
				'<div class="envirabox-thumbs envirabox-thumbs-' +
					options.position +
					'"></div>',
			).appendTo(instance.$refs.container);
			var defaultHeight =
				instance.opts.lightbox_theme === 'classical_dark' ||
				instance.opts.lightbox_theme === 'classical_light'
					? 52
					: 50;
			$row =
				options.rowHeight === 'auto'
					? defaultHeight
					: options.rowHeight;

			if (false === $row) {
				list = '<ul>';
			} else {
				list = '<ul style="height:' + $row + 'px">';
			}

			$.each(instance.group, function (i, item) {
				src =
					item.opts.thumb ||
					(item.opts.$thumb ? item.opts.$thumb.attr('src') : null);
				thumbnail_play_icon = item.opts.videoPlayIcon
					? item.opts.videoPlayIcon
					: false;

				if (!src && item.type === 'image') {
					src = item.src;
				}

				if (src && src.length) {
					var contentProvider =
						item.contentProvider !== undefined
							? item.contentProvider
							: 'none';
					list +=
						'<li data-index="' +
						i +
						'"  tabindex="0" class="envirabox-thumbs-loading envirabox-thumb-content-provider-' +
						contentProvider +
						' envirabox-thumb-type-' +
						item.type +
						'"><img class="envirabox-thumb-image-' +
						item.type +
						' envirabox-thumb-content-provider-' +
						contentProvider +
						'" data-src="' +
						src +
						'" />';
					if (
						thumbnail_play_icon &&
						(item.type == 'video' ||
							item.type == 'genericDiv' ||
							contentProvider == 'youtube' ||
							contentProvider == 'vimeo' ||
							contentProvider == 'instagram' ||
							contentProvider == 'twitch' ||
							contentProvider == 'dailymotion' ||
							contentProvider == 'metacafe' ||
							contentProvider == 'wistia' ||
							contentProvider == 'videopress')
					) {
						list +=
							'<div class="envira-video-play-icon">Play</div>';
					}
					list += '</li>';
				}
			});

			list += '</ul>';

			this.$list = $(list)
				.appendTo(this.$grid)
				.on('click touchstart', 'li', function () {
					instance.jumpTo($(this).data('index'));
				});

			this.$list
				.find('img')
				.one('load', function () {
					var $parent = $(this)
							.parent()
							.removeClass('envirabox-thumbs-loading'),
						thumbWidth = $(this).outerWidth(),
						thumbHeight = $(this).outerHeight(),
						width,
						height,
						widthRatio,
						heightRatio;

					width = this.naturalWidth || this.width;
					height = this.naturalHeight || this.height;

					// Calculate thumbnail width/height and center it
					widthRatio = width / thumbWidth;
					heightRatio = height / thumbHeight;

					if (widthRatio >= 1 && heightRatio >= 1) {
						if (widthRatio > heightRatio) {
							width = width / heightRatio;
							height = thumbHeight;
						} else {
							width = thumbWidth;
							height = height / widthRatio;
						}
					}

					$(this)
						.css({
							width: 'auto',
							height: Math.floor(height),
							'margin-top': Math.min(
								0,
								Math.floor(thumbHeight * 0.3 - height * 0.3),
							),
							'margin-left': Math.min(
								0,
								Math.floor(thumbWidth * 0.5 - width * 0.5),
							),
						})
						.show();
				})

				.each(function () {
					this.src = $(this).data('src');
				});
		},

		focus: function () {
			if (this.instance.current) {
				this.$list
					.children()
					.removeClass('envirabox-thumbs-active')
					.filter(
						'[data-index="' + this.instance.current.index + '"]',
					)
					.addClass('envirabox-thumbs-active')
					.focus();
			}
		},

		close: function () {
			this.$grid.hide();
		},

		update: function () {
			this.instance.$refs.container.toggleClass(
				'envirabox-show-thumbs',
				this.isVisible,
			);

			if (this.isVisible) {
				if (!this.$grid) {
					this.create();
				}

				this.instance.trigger('onThumbsShow');

				this.focus();
			} else if (this.$grid) {
				this.instance.trigger('onThumbsHide');
			}

			// Update content position
			this.instance.update();
		},

		hide: function () {
			this.isVisible = false;
			this.update();
		},

		show: function () {
			this.isVisible = true;
			this.update();
		},

		toggle: function () {
			this.isVisible = !this.isVisible;
			this.update();
		},
	});

	$(document).on({
		'onInit.eb': function (e, instance) {
			if (instance && !instance.Thumbs) {
				instance.Thumbs = new EnviraboxThumbs(instance);
			}
		},

		'beforeShow.eb': function (e, instance, item, firstRun) {
			const Thumbs = instance && instance.Thumbs;

			if (!Thumbs || !Thumbs.isActive) {
				return;
			}

			if (item.modal) {
				Thumbs.$button.hide();

				Thumbs.hide();

				return;
			}

			if (firstRun && item.opts.thumbs.autoStart === true) {
				Thumbs.show();
			}

			if (Thumbs.isVisible) {
				Thumbs.focus();
			}
		},

		'afterKeydown.eb': function (e, instance, current, keypress, keycode) {
			var Thumbs = instance && instance.Thumbs;

			// "G"
			if (Thumbs && Thumbs.isActive && keycode === 71) {
				keypress.preventDefault();

				Thumbs.toggle();
			}
		},

		'beforeClose.eb': function (e, instance) {
			const Thumbs = instance && instance.Thumbs;

			if (
				Thumbs &&
				Thumbs.isVisible &&
				instance.opts.thumbs.hideOnClose !== false
			) {
				Thumbs.close();
			}
		},
	});
})(document, window.jQuery);
