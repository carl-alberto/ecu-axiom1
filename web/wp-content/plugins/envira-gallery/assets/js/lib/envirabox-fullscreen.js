// ==========================================================================
//
// FullScreen
// Adds fullscreen functionality
//
// ==========================================================================
(function (document, $) {
	'use strict';

	// Collection of methods supported by user browser
	var fn = (function () {
		var fnMap = [
			[
				'requestFullscreen',
				'exitFullscreen',
				'fullscreenElement',
				'fullscreenEnabled',
				'fullscreenchange',
				'fullscreenerror',
			],
			// new WebKit
			[
				'webkitRequestFullscreen',
				'webkitExitFullscreen',
				'webkitFullscreenElement',
				'webkitFullscreenEnabled',
				'webkitfullscreenchange',
				'webkitfullscreenerror',
			],
			// old WebKit (Safari 5.1)
			[
				'webkitRequestFullScreen',
				'webkitCancelFullScreen',
				'webkitCurrentFullScreenElement',
				'webkitCancelFullScreen',
				'webkitfullscreenchange',
				'webkitfullscreenerror',
			],
			[
				'mozRequestFullScreen',
				'mozCancelFullScreen',
				'mozFullScreenElement',
				'mozFullScreenEnabled',
				'mozfullscreenchange',
				'mozfullscreenerror',
			],
			[
				'msRequestFullscreen',
				'msExitFullscreen',
				'msFullscreenElement',
				'msFullscreenEnabled',
				'MSFullscreenChange',
				'MSFullscreenError',
			],
		];

		var val;
		var ret = {};
		var i, j;

		for (i = 0; i < fnMap.length; i++) {
			val = fnMap[i];

			if (val && val[1] in document) {
				for (j = 0; j < val.length; j++) {
					ret[fnMap[0][j]] = val[j];
				}

				return ret;
			}
		}

		return false;
	})();

	// If browser does not have Full Screen API, then simply unset default button template and stop
	if (!fn) {
		if ($ && $.envirabox) {
			$.envirabox.defaults.btnTpl.fullScreen = false;
		}

		return;
	}

	var FullScreen = {
		request: function (elem) {
			elem = elem || document.documentElement;

			elem[fn.requestFullscreen](elem.ALLOW_KEYBOARD_INPUT);
		},
		exit: function () {
			document[fn.exitFullscreen]();
			$.envirabox.close(true);
		},
		toggle: function (elem) {
			elem = elem || document.documentElement;

			if (this.isFullscreen()) {
				this.exit();
			} else {
				this.request(elem);
			}
		},
		isFullscreen: function () {
			return Boolean(document[fn.fullscreenElement]);
		},
		enabled: function () {
			return Boolean(document[fn.fullscreenEnabled]);
		},
	};

	$(document).on({
		'onInit.eb': function (e, instance) {
			var $container;

			var $button = instance.$refs.toolbar.find(
				'[data-envirabox-fullscreen]',
			);

			if (
				instance &&
				!instance.FullScreen &&
				instance.group[instance.currIndex].opts.fullScreen
			) {
				$container = instance.$refs.container;

				$container.on(
					'click.eb-fullscreen',
					'[data-envirabox-fullscreen]',
					function (e) {
						e.stopPropagation();
						e.preventDefault();

						FullScreen.toggle($container[0]);
					},
				);

				if (
					instance.opts.fullScreen &&
					instance.opts.fullScreen.autoStart === true
				) {
					FullScreen.request($container[0]);
				}

				// Expose API
				instance.FullScreen = FullScreen;
			} else {
				$button.hide();
			}
		},

		'afterKeydown.eb': function (e, instance, current, keypress, keycode) {
			// "P" or Spacebar
			if (instance && instance.FullScreen && keycode === 70) {
				keypress.preventDefault();

				instance.FullScreen.toggle(instance.$refs.container[0]);
			}
		},

		'beforeClose.eb': function (instance) {
			if (instance && instance.FullScreen) {
				FullScreen.exit();
			}
		},
	});

	$(document).on(fn.fullscreenchange, function () {
		var instance = $.envirabox.getInstance();

		// If image is zooming, then force to stop and reposition properly
		if (
			instance.current &&
			instance.current.type === 'image' &&
			instance.isAnimating
		) {
			instance.current.$content.css('transition', 'none');

			instance.isAnimating = false;

			instance.update(true, true, 0);
		}

		$(document).trigger('onFullscreenChange', FullScreen.isFullscreen());
	});
})(document, window.jQuery);
