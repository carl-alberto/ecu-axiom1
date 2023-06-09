// ==========================================================================
//
// Media
// Adds additional media type support
//
// ==========================================================================
(function ($) {
	'use strict';

	// Formats matching url to final form
	var format = function (url, rez, params) {
		if (!url) {
			return;
		}

		params = params || '';

		if ($.type(params) === 'object') {
			params = $.param(params, true);
		}

		$.each(rez, function (key, value) {
			url = url.replace('$' + key, value || '');
		});

		if (params.length) {
			url += (url.indexOf('?') > 0 ? '&' : '?') + params;
		}

		return url;
	};

	// Object containing properties for each media type
	var defaults = {
		youtube_playlist: {
			matcher:
				/^http:\/\/(?:www\.)?youtube\.com\/watch\?((v=[^&\s]*&list=[^&\s]*)|(list=[^&\s]*&v=[^&\s]*))(&[^&\s]*)*$/,
			params: {
				autoplay: 1,
				autohide: 1,
				fs: 1,
				rel: 0,
				hd: 1,
				wmode: 'transparent',
				enablejsapi: 1,
				html5: 1,
			},
			paramPlace: 8,
			type: 'iframe',
			url: '//www.youtube.com/embed/videoseries?list=$4',
			thumb: '//img.youtube.com/vi/$4/hqdefault.jpg',
		},

		youtube: {
			matcher:
				/(youtube\.com|youtu\.be|youtube\-nocookie\.com)\/(watch\?(.*&)?v=|v\/|u\/|embed\/?)?(videoseries\?list=(.*)|[\w-]{11}|\?listType=(.*)&list=(.*))(.*)/i,
			params: {
				autoplay: 1,
				autohide: 1,
				fs: 1,
				rel: 0,
				hd: 1,
				wmode: 'transparent',
				enablejsapi: 1,
				html5: 1,
			},
			paramPlace: 8,
			type: 'iframe',
			url: '//www.youtube.com/embed/$4',
			thumb: '//img.youtube.com/vi/$4/hqdefault.jpg',
		},

		vimeo: {
			matcher: /^.+vimeo.com\/(.*\/)?([\d]+)(.*)?/,
			params: {
				autoplay: 1,
				hd: 1,
				show_title: 1,
				show_byline: 1,
				show_portrait: 0,
				fullscreen: 1,
				api: 1,
			},
			paramPlace: 3,
			type: 'iframe',
			url: '//player.vimeo.com/video/$2',
		},

		metacafe: {
			matcher: /metacafe.com\/watch\/(\d+)\/(.*)?/,
			type: 'iframe',
			url: '//www.metacafe.com/embed/$1/?ap=1',
		},

		dailymotion: {
			matcher: /dailymotion.com\/video\/(.*)\/?(.*)/,
			params: {
				additionalInfos: 0,
				autoStart: 1,
			},
			type: 'iframe',
			url: '//www.dailymotion.com/embed/video/$1',
		},

		facebook: {
			matcher: /facebook.com\/facebook\/videos\/(.*)\/?(.*)/,
			type: 'genericDiv',
			subtype: 'facebook',
			url: '//www.facebook.com/facebook/videos/$1',
		},

		instagram: {
			matcher: /(instagr\.am|instagram\.com)\/p\/([a-zA-Z0-9_\-]+)\/?/i,
			type: 'image',
			url: '//$1/p/$2/media/?size=l',
		},

		instagram_tv: {
			matcher: /(instagr\.am|instagram\.com)\/tv\/([a-zA-Z0-9_\-]+)\/?/i,
			type: 'iframe',
			url: '//$1/p/$2/media/?size=l',
		},

		wistia: {
			matcher: /wistia.com\/medias\/(.*)\/?(.*)/,
			type: 'iframe',
			url: '//fast.wistia.net/embed/iframe/$1',
		},

		// Example: //player.twitch.tv/?video=270862436ß
		twitch: {
			matcher: /player.twitch.tv\/[\\?&]video=([^&#]*)/,
			type: 'iframe',
			url: '//player.twitch.tv/?video=$1',
		},

		// Example: //videopress.com/v/DK5mLrbr    ?at=1374&loop=1&autoplay=1
		videopress: {
			matcher: /videopress.com\/v\/(.*)\/?(.*)/,
			type: 'iframe',
			url: '//videopress.com/embed/$1',
		},

		// Examples:
		// http://maps.google.com/?ll=48.857995,2.294297&spn=0.007666,0.021136&t=m&z=16
		// https://www.google.com/maps/@37.7852006,-122.4146355,14.65z
		// https://www.google.com/maps/place/Googleplex/@37.4220041,-122.0833494,17z/data=!4m5!3m4!1s0x0:0x6c296c66619367e0!8m2!3d37.4219998!4d-122.0840572
		gmap_place: {
			matcher:
				/(maps\.)?google\.([a-z]{2,3}(\.[a-z]{2})?)\/(((maps\/(place\/(.*)\/)?\@(.*),(\d+.?\d+?)z))|(\?ll=))(.*)?/i,
			type: 'iframe',
			url: function (rez) {
				return (
					'//maps.google.' +
					rez[2] +
					'/?ll=' +
					(rez[9]
						? rez[9] +
						  '&z=' +
						  Math.floor(rez[10]) +
						  (rez[12] ? rez[12].replace(/^\//, '&') : '')
						: rez[12]) +
					'&output=' +
					(rez[12] && rez[12].indexOf('layer=c') > 0
						? 'svembed'
						: 'embed')
				);
			},
		},

		// Examples:
		// https://www.google.com/maps/search/Empire+State+Building/
		// https://www.google.com/maps/search/?api=1&query=centurylink+field
		// https://www.google.com/maps/search/?api=1&query=47.5951518,-122.3316393
		gmap_search: {
			matcher:
				/(maps\.)?google\.([a-z]{2,3}(\.[a-z]{2})?)\/(maps\/search\/)(.*)/i,
			type: 'iframe',
			url: function (rez) {
				return (
					'//maps.google.' +
					rez[2] +
					'/maps?q=' +
					rez[5].replace('query=', 'q=').replace('api=1', '') +
					'&output=embed'
				);
			},
		},
	};

	$(document).on('onInit.eb', function (e, instance) {
		$.each(instance.group, function (i, item) {
			// console.log('!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!');
			// console.log(item.src);
			// console.log(item);
			var url_to_check = item.src || '',
				type = false,
				subtype = false,
				url = false,
				media,
				thumb,
				rez,
				params,
				urlParams,
				o,
				provider = false;

			// Skip items that already have content type
			if (item.type) {
				return;
			}

			media = $.extend(true, {}, defaults, item.opts.media);

			// Look for any matching media type
			$.each(media, function (n, el) {
				// console.log( 'provider: ' + n + ' - matcher: ' + el.matcher + ' - url: ' + url_to_check + ' - url: ' + url );
				rez = url_to_check.match(el.matcher);
				o = {};

				if (!rez) {
					return;
				}

				provider = n;

				type = el.type;
				if (el.subtype !== undefined) {
					subtype = el.subtype;
				}

				if (el.paramPlace && rez[el.paramPlace]) {
					urlParams = rez[el.paramPlace];

					if (urlParams[0] == '?') {
						urlParams = urlParams.substring(1);
					}

					urlParams = urlParams.split('&');

					for (var m = 0; m < urlParams.length; ++m) {
						var p = urlParams[m].split('=', 2);

						if (p.length == 2) {
							o[p[0]] = decodeURIComponent(
								p[1].replace(/\+/g, ' '),
							);
						}
					}
				}

				params = $.extend(true, {}, el.params, item.opts[n], o);

				url =
					$.type(el.url) === 'function'
						? el.url.call(this, rez, params, item)
						: format(el.url, rez, params);
				thumb =
					$.type(el.thumb) === 'function'
						? el.thumb.call(this, rez, params, item)
						: format(el.thumb, rez);

				if (provider === 'vimeo') {
					url = url.replace('&%23', '#');
				}

				return false;
			});

			if (type) {
				// console.log ('MATCHED URL!!!!!!! ' + url);
				item.src = url;
				item.type = type;
				item.subtype = subtype;

				if (
					!item.opts.thumb &&
					!(item.opts.$thumb && item.opts.$thumb.length)
				) {
					item.opts.thumb = thumb;
				}

				if (type === 'iframe') {
					$.extend(true, item.opts, {
						iframe: {
							preload: false,
							provider: provider,
							attr: {
								scrolling: 'no',
							},
						},
					});

					item.contentProvider = provider;

					item.opts.slideClass +=
						' envirabox-slide--' +
						(provider == 'gmap_place' || provider == 'gmap_search'
							? 'map'
							: 'video');
				}
			} else {
				// If no content type is found, then set it to `image` as fallback
				item.type = 'image';
			}
		});
	});
})(window.jQuery);
