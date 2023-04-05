/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ 617:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

/* provided dependency */ var $ = __webpack_require__(311);
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }
function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }
var Envira_Link = /*#__PURE__*/function () {
  function Envira_Link(data, images, lightbox) {
    _classCallCheck(this, Envira_Link);
    var self = this;

    // Setup our Vars
    self.data = data;
    self.images = images;
    self.id = this.get_config('gallery_id');
    self.envirabox_config = lightbox;

    // Log if ENVIRA_DEBUG enabled
    self.log(self.data);
    self.log(self.images);
    self.log(self.envirabox_config);
    self.log(self.id);
    self.init();
  }
  _createClass(Envira_Link, [{
    key: "init",
    value: function init() {
      var self = this;
      self.lightbox();
    }

    /**
     * Outputs the gallery init script in the footer.
     *
     * @since 1.7.1
     */
  }, {
    key: "lightbox",
    value: function lightbox() {
      var self = this,
        touch = self.get_config('mobile_touchwipe') ? {
          vertical: true,
          momentum: true
        } : true,
        thumbs = self.get_config('thumbnails') ? {
          autoStart: true,
          hideOnClose: true,
          position: self.get_lightbox_config('thumbs_position')
        } : false,
        slideshow = self.get_config('slideshow') ? {
          autoStart: self.get_config('autoplay'),
          speed: self.get_config('ss_speed')
        } : false,
        fullscreen = self.get_config('fullscreen') && self.get_config('open_fullscreen') ? {
          autoStart: true
        } : true,
        animationEffect = self.get_config('lightbox_open_close_effect') == 'zomm-in-out' ? 'zoom-in-out' : self.get_config('lightbox_open_close_effect'),
        transitionEffect = self.get_config('effect') == 'zomm-in-out' ? 'zoom' : self.get_config('effect'),
        lightbox_images = [];
      self.lightbox_options = {
        selector: '[data-envirabox="' + self.id + '"]',
        loop: self.get_config('loop'),
        // Enable infinite gallery navigation
        margin: self.get_lightbox_config('margins'),
        // Space around image, ignored if zoomed-in or viewport width is smaller than 800px
        gutter: self.get_lightbox_config('gutter'),
        // Horizontal space between slides
        keyboard: self.get_config('keyboard'),
        // Enable keyboard navigation
        arrows: self.get_lightbox_config('arrows'),
        // Should display navigation arrows at the screen edges
        arrow_position: self.get_lightbox_config('arrow_position'),
        infobar: self.get_lightbox_config('infobar'),
        // Should display infobar (counter and arrows at the top)
        toolbar: self.get_lightbox_config('toolbar'),
        // Should display toolbar (buttons at the top)
        idleTime: self.get_lightbox_config('idle_time') ? self.get_lightbox_config('idle_time') : false,
        // by default there shouldn't be any, otherwise value is in seconds
        smallBtn: self.get_lightbox_config('show_smallbtn'),
        protect: false,
        // Disable right-click and use simple image protection for images
        image: {
          preload: false
        },
        animationEffect: animationEffect,
        animationDuration: self.get_lightbox_config('animation_duration') ? self.get_lightbox_config('animation_duration') : 300,
        // Duration in ms for open/close animation
        btnTpl: {
          smallBtn: self.get_lightbox_config('small_btn_template')
        },
        zoomOpacity: 'auto',
        transitionEffect: transitionEffect,
        // Transition effect between slides
        transitionDuration: self.get_lightbox_config('transition_duration') ? self.get_lightbox_config('transition_duration') : 200,
        // Duration in ms for transition animation
        baseTpl: self.get_lightbox_config('base_template'),
        // Base template for layout
        spinnerTpl: '<div class="envirabox-loading"></div>',
        // Loading indicator template
        errorTpl: self.get_lightbox_config('error_template'),
        // Error message template
        fullScreen: self.get_config('fullscreen') ? fullscreen : false,
        touch: touch,
        // Set `touch: false` to disable dragging/swiping
        hash: false,
        insideCap: self.get_lightbox_config('inner_caption'),
        capPosition: self.get_lightbox_config('caption_position'),
        capTitleShow: self.get_config('lightbox_title_caption') && self.get_config('lightbox_title_caption') != 'none' && self.get_config('lightbox_title_caption') != '0' ? self.get_config('lightbox_title_caption') : false,
        media: {
          youtube: {
            params: {
              autoplay: 0
            }
          }
        },
        wheel: this.get_config('mousewheel') == false ? false : true,
        slideShow: slideshow,
        thumbs: thumbs,
        mobile: {
          clickContent: function clickContent(current, event) {
            return current.type === 'image' ? 'toggleControls' : false;
          },
          clickSlide: function clickSlide(current, event) {
            return current.type === 'image' ? 'toggleControls' : 'close';
          },
          dblclickContent: false,
          dblclickSlide: false
        },
        // Clicked on the content
        clickContent: self.get_lightbox_config('click_content') ? self.get_lightbox_config('click_content') : 'toggleControls',
        // clicked on the image itself
        clickSlide: self.get_lightbox_config('click_slide') ? self.get_lightbox_config('click_slide') : 'close',
        // clicked on the slide
        clickOutside: self.get_lightbox_config('click_outside') ? self.get_lightbox_config('click_outside') : 'toggleControls',
        // clicked on the background (backdrop) element

        // Same as previous two, but for double click
        dblclickContent: false,
        dblclickSlide: false,
        dblclickOutside: false,
        // Video settings
        videoPlayPause: self.get_config('videos_playpause') ? true : false,
        videoProgressBar: self.get_config('videos_progress') ? true : false,
        videoPlaybackTime: self.get_config('videos_current') ? true : false,
        videoVideoLength: self.get_config('videos_duration') ? true : false,
        videoVolumeControls: self.get_config('videos_volume') ? true : false,
        videoControlBar: self.get_config('videos_controls') ? true : false,
        videoFullscreen: self.get_config('videos_fullscreen') ? true : false,
        videoDownload: self.get_config('videos_download') ? true : false,
        videoPlayIcon: self.get_config('videos_play_icon_thumbnails') ? true : false,
        // Callbacks
        // ==========
        onInit: function onInit(instance, current) {
          $(document).trigger('envirabox_api_on_init', [self, instance, current]);
        },
        beforeLoad: function beforeLoad(instance, current) {
          $(document).trigger('envirabox_api_before_load', [self, instance, current]);
        },
        afterLoad: function afterLoad(instance, current) {
          $(document).trigger('envirabox_api_after_load', [self, instance, current]);
        },
        beforeShow: function beforeShow(instance, current) {
          $(document).trigger('envirabox_api_before_show', [self, instance, current]);
        },
        afterShow: function afterShow(instance, current) {
          if (prepend == undefined || prepend_cap == undefined) {
            var prepend = false,
              prepend_cap = false;
          }
          if (prepend != true) {
            $('.envirabox-position-overlay').each(function () {
              $(this).prependTo(current.$content);
            });
            prepend = true;
          }

          /* support older galleries or if someone overrides the keyboard configuration via a filter, etc. */

          if (self.get_config('keyboard') !== undefined && self.get_config('keyboard') === 0) {
            $(window).keypress(function (event) {
              if ([32, 37, 38, 39, 40].indexOf(event.keyCode) > -1) {
                event.preventDefault();
              }
            });
          }

          /* legacy theme we hide certain elements initially to prevent user seeing them for a second in the upper left until the CSS fully loads */
          $('.envirabox-caption').show();
          $('.envirabox-navigation').show();
          $('.envirabox-navigation-inside').show();
          $(document).trigger('envirabox_api_after_show', [self, instance, current]);
        },
        beforeClose: function beforeClose(instance, current) {
          $(document).trigger('envirabox_api_before_close', [self, instance, current]);
        },
        afterClose: function afterClose(instance, current) {
          $(document).trigger('envirabox_api_after_close', [self, instance, current]);
        },
        onActivate: function onActivate(instance, current) {
          $(document).trigger('envirabox_api_on_activate', [self, instance, current]);
        },
        onDeactivate: function onDeactivate(instance, current) {
          $(document).trigger('envirabox_api_on_deactivate', [self, instance, current]);
        }
      };
      $(document).trigger('envirabox_options', self);

      // Mobile Overrides
      if (self.is_mobile()) {
        if (self.get_config('mobile_thumbnails') !== 1) {
          self.lightbox_options.thumbs = false;
        }
      }
      $.each(self.images, function (i) {
        lightbox_images.push(this);

        // Formats matching url to final form
        var format = function format(url, rez, params) {
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

        /* is video? */
        var video_defaults = {
          youtube_playlist: {
            matcher: /^http:\/\/(?:www\.)?youtube\.com\/watch\?((v=[^&\s]*&list=[^&\s]*)|(list=[^&\s]*&v=[^&\s]*))(&[^&\s]*)*$/,
            params: {
              autoplay: 1,
              autohide: 1,
              fs: 1,
              rel: 0,
              hd: 1,
              wmode: 'transparent',
              enablejsapi: 1,
              html5: 1
            },
            paramPlace: 8,
            type: 'iframe',
            url: '//www.youtube.com/embed/videoseries?list=$4',
            thumb: '//img.youtube.com/vi/$4/hqdefault.jpg'
          },
          youtube: {
            matcher: /(youtube\.com|youtu\.be|youtube\-nocookie\.com)\/(watch\?(.*&)?v=|v\/|u\/|embed\/?)?(videoseries\?list=(.*)|[\w-]{11}|\?listType=(.*)&list=(.*))(.*)/i,
            params: {
              autoplay: 1,
              autohide: 1,
              fs: 1,
              rel: 0,
              hd: 1,
              wmode: 'transparent',
              enablejsapi: 1,
              html5: 1
            },
            paramPlace: 8,
            type: 'iframe',
            url: '//www.youtube.com/embed/$4',
            thumb: '//img.youtube.com/vi/$4/hqdefault.jpg'
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
              muted: 1,
              // not sure is this is a real setting
              api: 1
            },
            paramPlace: 3,
            type: 'iframe',
            url: '//player.vimeo.com/video/$2'
          },
          metacafe: {
            matcher: /metacafe.com\/watch\/(\d+)\/(.*)?/,
            type: 'iframe',
            url: '//www.metacafe.com/embed/$1/?ap=1'
          },
          dailymotion: {
            matcher: /dailymotion.com\/video\/(.*)\/?(.*)/,
            params: {
              additionalInfos: 0,
              autoStart: 1
            },
            type: 'iframe',
            url: '//www.dailymotion.com/embed/video/$1'
          },
          facebook: {
            matcher: /facebook.com\/facebook\/videos\/(.*)\/?(.*)/,
            type: 'genericDiv',
            subtype: 'facebook',
            url: '//www.facebook.com/facebook/videos/$1'
          },
          instagram: {
            matcher: /(instagr\.am|instagram\.com)\/p\/([a-zA-Z0-9_\-]+)\/?/i,
            type: 'image',
            url: '//$1/p/$2/media/?size=l'
          },
          instagram_tv: {
            matcher: /(instagr\.am|instagram\.com)\/tv\/([a-zA-Z0-9_\-]+)\/?/i,
            type: 'iframe',
            url: '//$1/p/$2/media/?size=l'
          },
          // https://scontent-mia3-1.cdninstagram.com/vp/f31edadb32713082be585c276d336aa8/5C66C69A/t50.2886-16/26203486_930585467119082_6733111627396153344_n.mp4?_nc_ht=scontent-mia3-1.cdninstagram.com

          instagram_video: {
            matcher: /(cdninstagr\.am|cdninstagram\.com)\/vp\/([a-zA-Z0-9_\-]+)\/?/i,
            type: 'instagram_video',
            url: '//$1/p/$2/media/?size=l'
          },
          wistia: {
            matcher: /wistia.com\/medias\/(.*)\/?(.*)/,
            type: 'iframe',
            url: '//fast.wistia.net/embed/iframe/$1'
          },
          // Example: //player.twitch.tv/?video=270862436ß
          twitch: {
            matcher: /player.twitch.tv\/[\\?&]video=([^&#]*)/,
            type: 'iframe',
            url: '//player.twitch.tv/?video=$1'
          },
          // Example: //videopress.com/v/DK5mLrbr    ?at=1374&loop=1&autoplay=1
          videopress: {
            matcher: /videopress.com\/v\/(.*)\/?(.*)/,
            type: 'iframe',
            url: '//videopress.com/embed/$1'
          },
          self_hosted: {
            matcher: /(.mp4|.flv|.ogv|.webm)/,
            type: 'video'
          },
          pdf: {
            matcher: /(.pdf)/,
            type: 'iframe'
          },
          image: {
            matcher: /(.jpg)/,
            type: null
          }
        };
        var image = this,
          url = this.link || '',
          type = false,
          subtype = false,
          media,
          thumb,
          rez,
          params,
          urlParams,
          o,
          provider = false,
          media = $.extend(true, {}, video_defaults, this.opts.media);

        // Look for any matching media type
        $.each(media, function (n, el) {
          rez = url.match(el.matcher);
          o = {};
          if (!rez || rez === undefined) {
            return;
          }
          provider = n;
          type = el.type;
          subtype = el.subtype !== undefined ? el.subtype : false;
          if (el.paramPlace && rez[el.paramPlace]) {
            urlParams = rez[el.paramPlace];
            if (urlParams[0] == '?') {
              urlParams = urlParams.substring(1);
            }
            urlParams = urlParams.split('&');
            for (var m = 0; m < urlParams.length; ++m) {
              var p = urlParams[m].split('=', 2);
              if (p.length == 2) {
                o[p[0]] = decodeURIComponent(p[1].replace(/\+/g, ' '));
              }
            }
          }
          if (el.type !== 'instagram_video' && el.url !== undefined) {
            url = $.type(el.url) === 'function' ? el.url.call(this, rez, params, item) : format(el.url, rez, params);
          }
          if (url === undefined) {
            return;
          }
          if (self.data.videos_autoplay == 1) {
            if (provider === 'vimeo' || provider === 'youtube') {
              url = url + '/?autoplay=1';
            }
          }
          if (provider === 'vimeo') {
            url = url.replace('&%23', '#');
          }
        });

        // If it is found, then change content type and update the url
        if (type) {
          this.video = true;
          this.src = url;
          this.type = type;
          this.subtype = subtype;
          if (type === 'iframe') {
            $.extend(true, this.opts, {
              iframe: {
                preload: true,
                provider: provider,
                attr: {
                  scrolling: 'no'
                }
              }
            });
            this.contentProvider = provider;
          }
        } else {
          // If no content type is found, then set it to `image` as fallback
          this.type = 'image';
        }
      });
      $('.envira-gallery-links').on('click', function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        var $this = $(this),
          images = [],
          $envira_images = $this.data('gallery-images'),
          sorted_ids = $this.data('gallery-sort-ids'),
          // sort by sort ids, not by output of gallery-images, because retaining object key order between unserialisation and serialisation in JavaScript is never guaranteed.
          sorting_factor = sorted_ids !== undefined && self.data.gallery_sort == 'gallery' ? 'id' : 'image',
          sorting_factor_data = sorted_ids !== undefined && self.data.gallery_sort == 'gallery' ? sorted_ids : $envira_images,
          active = $.envirabox.getInstance();

        // backup plan in case there isn't sorted_ids, we keep the lightbox_images even though this is probably not sorted
        if (sorted_ids !== undefined && sorted_ids != '') {
          lightbox_images = [];
          $.each(sorted_ids, function (i, val) {
            lightbox_images.push(sorting_factor_data[val]);
          });
        }
        if (active) {
          return;
        }
        $.envirabox.open(lightbox_images, self.lightbox_options);
      });
    }

    /**
     * Get a config option based off of a key.
     *
     * @since 1.7.1
     */
  }, {
    key: "get_config",
    value: function get_config(key) {
      var self = this;
      return self.data[key];
    }

    /**
     * Helper method to get config by key.
     *
     * @since 1.7.1
     */
  }, {
    key: "get_lightbox_config",
    value: function get_lightbox_config(key) {
      var self = this;
      return self.envirabox_config[key];
    }

    /**
     * Helper method to get image from id
     *
     * @since 1.7.1
     */
  }, {
    key: "get_image",
    value: function get_image(id) {
      var self = this;
      return self.images[id];
    }
  }, {
    key: "is_mobile",
    value: function is_mobile() {
      if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
        return true;
      }
      return false;
    }
    /**
     * Helper method for logging if ENVIRA_DEBUG is true.
     *
     * @since 1.7.1
     */
  }, {
    key: "log",
    value: function log(_log) {
      // Bail if debug or log is not set.
      if (envira_gallery.debug == undefined || !envira_gallery.debug || _log == undefined) {
        return;
      }
      console.log(_log);
    }
  }]);
  return Envira_Link;
}();
module.exports = Envira_Link;

/***/ }),

/***/ 556:
/***/ ((__unused_webpack_module, __unused_webpack_exports, __webpack_require__) => {

/* provided dependency */ var jQuery = __webpack_require__(311);
/*!
 * Justified Gallery / Envira Extensions and Overrides - v3.6.2
 * Copyright (c) 2016 David Bisset, Benjamin Rojas
 * Licensed under the MIT license.
 */

(function ($) {
  var justifiedGallery = $.fn.justifiedGallery,
    EnviraJustifiedGallery = {};
  $.fn.enviraJustifiedGallery = function () {
    var obj = justifiedGallery.apply(this, arguments);
    EnviraJustifiedGallery = obj.data('jg.controller');
    if (EnviraJustifiedGallery !== undefined) {
      EnviraJustifiedGallery.displayEntryCaption = function ($entry) {
        var $image = this.imgFromEntry($entry);
        if ($image !== null && this.settings.captions) {
          var $imgCaption = this.captionFromEntry($entry);

          // Create it if it doesn't exists
          if ($imgCaption === null) {
            var caption = $image.data('automatic-caption'),
              revised_caption = '';
            if (caption !== undefined && typeof caption === 'string') {
              caption = caption.replace('<', '&lt;');
              revised_caption = $('<textarea />').html(caption).text();
            }
            if (revised_caption !== undefined) {
              if (this.isValidCaption(revised_caption)) {
                // Create only we found something
                $imgCaption = $('<div class="caption">' + revised_caption + '</div>');
                $image.after($imgCaption);
                $entry.data('jg.createdCaption', true);
              }
            }
          }

          // Create events (we check again the $imgCaption because it can be still inexistent)
          if ($imgCaption !== null) {
            if (!this.settings.cssAnimation) {
              $imgCaption.stop().fadeTo(0, this.settings.captionSettings.nonVisibleOpacity);
            }
            // Adjust the positioning of overlay buttons so that it doesn't overlap the caption
            var imgCaptionHeight = $imgCaption.css('height');
            $entry.find('.envira-gallery-position-overlay.envira-gallery-bottom-left').css('bottom', imgCaptionHeight);
            $entry.find('.envira-gallery-position-overlay.envira-gallery-bottom-right').css('bottom', imgCaptionHeight);
            this.addCaptionEventsHandlers($entry);
          }
        } else {
          this.removeCaptionEventsHandlers($entry);
        }
      };
      return EnviraJustifiedGallery;
    }
  };
})(jQuery);

/***/ }),

/***/ 496:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

/* provided dependency */ var jQuery = __webpack_require__(311);
/* provided dependency */ var $ = __webpack_require__(311);
/*
 * Responsively Lazy
 * http://ivopetkov.com/b/lazy-load-responsive-images/
 * Copyright 2015-2016, Ivo Petkov
 * Free to use under the MIT license.
 */
var enviraLazy = function () {
  var hasWebPSupport = false,
    windowWidth = null,
    windowHeight = null,
    justifiedReady = false,
    galleryClass = false,
    hasIntersectionObserverSupport = typeof IntersectionObserver !== 'undefined';
  var isVisible = function isVisible(element) {
    if (windowWidth === null) {
      windowWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
      windowHeight = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
      if (windowWidth === null) {
        return false;
      }
    }
    var rect = element.getBoundingClientRect();
    var elementTop = rect.top;
    var elementLeft = rect.left;
    var elementWidth = rect.width;
    var elementHeight = rect.height;
    var test = elementTop < windowHeight && elementTop + elementHeight > 0 && elementLeft < windowWidth && elementLeft + elementWidth > 0;
    if (test === false) {}
    return test;
  };
  jQuery.fn.exists = function () {
    return this.length > 0;
  };
  var updateElement = function updateElement(container, element) {
    var options = element.getAttribute('data-envira-srcset');
    if (options !== null) {
      options = options.trim();
      if (options.length > 0) {
        options = options.split(',');
        var temp = [];
        var optionsCount = options.length;
        for (var j = 0; j < optionsCount; j++) {
          var option = options[j].trim();
          if (option.length === 0) {
            continue;
          }
          var spaceIndex = option.lastIndexOf(' ');
          if (spaceIndex === -1) {
            var optionImage = option;
            var optionWidth = 999998;
          } else {
            var optionImage = option.substr(0, spaceIndex);
            var optionWidth = parseInt(option.substr(spaceIndex + 1, option.length - spaceIndex - 2), 10);
          }
          var add = false;
          if (optionImage.indexOf('.webp', optionImage.length - 5) !== -1) {
            if (hasWebPSupport) {
              add = true;
            }
          } else {
            add = true;
          }
          if (add) {
            temp.push([optionImage, optionWidth]);
          }
        }
        temp.sort(function (a, b) {
          if (a[1] < b[1]) {
            return -1;
          }
          if (a[1] > b[1]) {
            return 1;
          }
          if (a[1] === b[1]) {
            if (b[0].indexOf('.webp', b[0].length - 5) !== -1) {
              return 1;
            }
            if (a[0].indexOf('.webp', a[0].length - 5) !== -1) {
              return -1;
            }
          }
          return 0;
        });
        options = temp;
      } else {
        options = [];
      }
    } else {
      options = [];
    }
    var containerWidth = container.offsetWidth * window.devicePixelRatio;
    var bestSelectedOption = null;
    var optionsCount = options.length;
    for (var j = 0; j < optionsCount; j++) {
      var optionData = options[j];
      if (optionData[1] >= containerWidth) {
        bestSelectedOption = optionData;
        break;
      } else {}
    }
    if (bestSelectedOption === null) {
      bestSelectedOption = [element.getAttribute('data-envira-src'), 999999];
    }
    if (typeof container.lastSetOption === 'undefined') {
      container.lastSetOption = ['', 0];
    }
    if (container.lastSetOption[1] < bestSelectedOption[1]) {
      var fireEvent = container.lastSetOption[1] === 0;
      var url = bestSelectedOption[0];
      var image = new Image();
      image.addEventListener('load', function () {
        element.setAttribute('srcset', url);
        element.setAttribute('src', url);
        if (fireEvent) {
          var handler = container.getAttribute('data-onlazyload');
          if (handler !== null) {
            new Function(handler).bind(container)();
          }
        }
      }, false);
      image.addEventListener('error', function () {
        container.lastSetOption = ['', 0];
      }, false);
      image.onload = function () {
        if (container.getAttribute('class') == 'envira-lazy' && $(container).not('img')) {
          // this is a legacy layout
          var the_image = container.firstElementChild;
          var the_container = container;
          var image_id = the_image.id;
          var image_src = the_image.src;
          var gallery_id = jQuery(the_image).data('envira-gallery-id');
          var item_id = jQuery(container).data('envira-item-id');
          var naturalWidth = this.naturalWidth;
          var naturalHeight = this.naturalHeight;
        } else {
          // we are going with the automatic
          var the_image = image;
          var the_container = container;
          var image_id = container.id;
          var image_src = container.src;
          var gallery_id = jQuery(container).data('envira-gallery-id');
          var item_id = jQuery(container).data('envira-item-id');
          var naturalWidth = this.naturalWidth;
          var naturalHeight = this.naturalHeight;
        }

        /* type check */

        if (gallery_id === undefined || gallery_id === null) {
          gallery_id = 0;
        }
        jQuery(document).trigger({
          type: 'envira_image_lazy_load_complete',
          container: the_container,
          image_src: image_src,
          image_id: image_id,
          item_id: item_id,
          gallery_id: gallery_id,
          naturalWidth: naturalWidth,
          naturalHeight: naturalHeight
        });
      };
      image.onerror = function () {
        console.error('Cannot load image');
        // do something else...
      };

      // Null assignment could have been for IE, but shows as 404 in Safari web console (although everything else is fine).
      // This should be confirmed to be working, then removed (Chrome/Firefox testing looks ok)
      // image.src = null;
      image.src = url;
      container.lastSetOption = bestSelectedOption;
    }
  };
  var updateWindowSize = function updateWindowSize() {
    windowWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
    windowHeight = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
  };
  var setGalleryClass = function setGalleryClass(theClass) {
    galleryClass = theClass;
    alert('setting - ' + galleryClass);
  };
  var run = function run(galleryClass) {
    if (typeof galleryClass === 'undefined') {
      return;
    }
    var update = function update(elements, unknownHeight) {
      var elementsCount = elements.length;
      for (var i = 0; i < elementsCount; i++) {
        var element = elements[i];
        var container = unknownHeight ? element : element.parentNode;
        if (isVisible(container) === true) {
          updateElement(container, element);
        }
      }
    };
    if (galleryClass) {
      if (typeof galleryClass !== 'string') {
        return;
      }
      if ('undefined' === typeof envira_gallery.ll_initial || 'undefined' === typeof envira_gallery.ll_delay || envira_gallery.ll_initial === false) {
        /* if we can't locate these vars, at least define the delay - there is no delay, super fast */
        envira_gallery.ll_delay = 0;
      }
      setTimeout(function () {
        if (jQuery(galleryClass + ' div.envira-lazy > img').exists()) {
          // alert('1');
          update(document.querySelectorAll(galleryClass + ' div.envira-lazy > img'), false);
        } else if (jQuery(galleryClass + ' img.envira-lazy').exists()) {
          // alert('2');
          update(document.querySelectorAll(galleryClass + ' img.envira-lazy'), true);
        }
        envira_gallery.ll_initial == true; // ok, we did the initial load so now delay can happen
      }, envira_gallery.ll_delay);
    }
    // update(document.querySelectorAll('img.envira-lazy'), true);
  };

  if ('srcset' in document.createElement('img') && typeof window.devicePixelRatio !== 'undefined' && typeof window.addEventListener !== 'undefined' && typeof document.querySelectorAll !== 'undefined') {
    updateWindowSize();
    var image = new Image();
    image.src = 'data:image/webp;base64,UklGRiQAAABXRUJQVlA4IBgAAAAwAQCdASoCAAEADMDOJaQAA3AA/uuuAAA=';
    image.onload = image.onerror = function () {
      hasWebPSupport = image.width === 2;
      if (hasIntersectionObserverSupport) {
        var updateIntersectionObservers = function updateIntersectionObservers() {
          var elements = document.querySelectorAll('.envira-lazy');
          var elementsCount = elements.length;
          for (var i = 0; i < elementsCount; i++) {
            var element = elements[i];
            if (typeof element.responsivelyLazyObserverAttached === 'undefined') {
              element.responsivelyLazyObserverAttached = true;
              intersectionObserver.observe(element);
            }
          }
        };
        var intersectionObserver = new IntersectionObserver(function (entries) {
          for (var i in entries) {
            var entry = entries[i];
            if (entry.intersectionRatio > 0) {
              var target = entry.target;
              if (target.tagName.toLowerCase() !== 'img') {
                var img = target.querySelector('img');
                if (img !== null) {
                  updateElement(target, img);
                }
              } else {
                updateElement(target, target);
              }
            }
          }
        });
        run();
      } else {
        var requestAnimationFrameFunction = window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame || function (callback) {
          window.setTimeout(callback, 1000 / 60);
        };
        var hasChange = true;
        var runIfHasChange = function runIfHasChange() {
          if (hasChange) {
            hasChange = false;
            // run();
          }

          requestAnimationFrameFunction.call(null, runIfHasChange);
        };
        var setChanged = function setChanged() {
          hasChange = true;
          runIfHasChange();
        };
        var updateParentNodesScrollListeners = function updateParentNodesScrollListeners() {
          var elements = document.querySelectorAll('.envira-lazy');
          var elementsCount = elements.length;
          for (var i = 0; i < elementsCount; i++) {
            var parentNode = elements[i].parentNode;
            while (parentNode && parentNode.tagName.toLowerCase() !== 'html') {
              if (typeof parentNode.responsivelyLazyScrollAttached === 'undefined') {
                parentNode.responsivelyLazyScrollAttached = true;
                parentNode.addEventListener('scroll', setChanged);
              }
              parentNode = parentNode.parentNode;
            }
          }
        };

        // runIfHasChange();
      }

      var attachEvents = function attachEvents() {
        if (hasIntersectionObserverSupport) {
          var resizeTimeout = null;
        }
        window.addEventListener('resize', function () {
          updateWindowSize();
          if (hasIntersectionObserverSupport) {
            window.clearTimeout(resizeTimeout);
            resizeTimeout = window.setTimeout(function () {
              run();
            }, 300);
          } else {
            setChanged();
          }
        });
        if (hasIntersectionObserverSupport) {
          window.addEventListener('load', run);
          updateIntersectionObservers();
        } else {
          window.addEventListener('scroll', setChanged);
          window.addEventListener('load', setChanged);
          updateParentNodesScrollListeners();
        }
        if (typeof MutationObserver !== 'undefined') {
          var observer = new MutationObserver(function () {
            if (hasIntersectionObserverSupport) {
              updateIntersectionObservers();
              run();
            } else {
              updateParentNodesScrollListeners();
              setChanged();
            }
          });
          observer.observe(document.querySelector('body'), {
            childList: true,
            subtree: true
          });
        }
      };
      if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', attachEvents);
      } else {
        attachEvents();
      }
    };
  }
  return {
    run: run,
    isVisible: isVisible,
    setGalleryClass: setGalleryClass
  };
}();
window.enviraLazy = enviraLazy;
module.exports = enviraLazy;

/***/ }),

/***/ 390:
/***/ (() => {

// ==========================================================================
//
// FullScreen
// Adds fullscreen functionality
//
// ==========================================================================
(function (document, $) {
  'use strict';

  // Collection of methods supported by user browser
  var fn = function () {
    var fnMap = [['requestFullscreen', 'exitFullscreen', 'fullscreenElement', 'fullscreenEnabled', 'fullscreenchange', 'fullscreenerror'],
    // new WebKit
    ['webkitRequestFullscreen', 'webkitExitFullscreen', 'webkitFullscreenElement', 'webkitFullscreenEnabled', 'webkitfullscreenchange', 'webkitfullscreenerror'],
    // old WebKit (Safari 5.1)
    ['webkitRequestFullScreen', 'webkitCancelFullScreen', 'webkitCurrentFullScreenElement', 'webkitCancelFullScreen', 'webkitfullscreenchange', 'webkitfullscreenerror'], ['mozRequestFullScreen', 'mozCancelFullScreen', 'mozFullScreenElement', 'mozFullScreenEnabled', 'mozfullscreenchange', 'mozfullscreenerror'], ['msRequestFullscreen', 'msExitFullscreen', 'msFullscreenElement', 'msFullscreenEnabled', 'MSFullscreenChange', 'MSFullscreenError']];
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
  }();

  // If browser does not have Full Screen API, then simply unset default button template and stop
  if (!fn) {
    if ($ && $.envirabox) {
      $.envirabox.defaults.btnTpl.fullScreen = false;
    }
    return;
  }
  var FullScreen = {
    request: function request(elem) {
      elem = elem || document.documentElement;
      elem[fn.requestFullscreen](elem.ALLOW_KEYBOARD_INPUT);
    },
    exit: function exit() {
      document[fn.exitFullscreen]();
      $.envirabox.close(true);
    },
    toggle: function toggle(elem) {
      elem = elem || document.documentElement;
      if (this.isFullscreen()) {
        this.exit();
      } else {
        this.request(elem);
      }
    },
    isFullscreen: function isFullscreen() {
      return Boolean(document[fn.fullscreenElement]);
    },
    enabled: function enabled() {
      return Boolean(document[fn.fullscreenEnabled]);
    }
  };
  $(document).on({
    'onInit.eb': function onInitEb(e, instance) {
      var $container;
      var $button = instance.$refs.toolbar.find('[data-envirabox-fullscreen]');
      if (instance && !instance.FullScreen && instance.group[instance.currIndex].opts.fullScreen) {
        $container = instance.$refs.container;
        $container.on('click.eb-fullscreen', '[data-envirabox-fullscreen]', function (e) {
          e.stopPropagation();
          e.preventDefault();
          FullScreen.toggle($container[0]);
        });
        if (instance.opts.fullScreen && instance.opts.fullScreen.autoStart === true) {
          FullScreen.request($container[0]);
        }

        // Expose API
        instance.FullScreen = FullScreen;
      } else {
        $button.hide();
      }
    },
    'afterKeydown.eb': function afterKeydownEb(e, instance, current, keypress, keycode) {
      // "P" or Spacebar
      if (instance && instance.FullScreen && keycode === 70) {
        keypress.preventDefault();
        instance.FullScreen.toggle(instance.$refs.container[0]);
      }
    },
    'beforeClose.eb': function beforeCloseEb(instance) {
      if (instance && instance.FullScreen) {
        FullScreen.exit();
      }
    }
  });
  $(document).on(fn.fullscreenchange, function () {
    var instance = $.envirabox.getInstance();

    // If image is zooming, then force to stop and reposition properly
    if (instance.current && instance.current.type === 'image' && instance.isAnimating) {
      instance.current.$content.css('transition', 'none');
      instance.isAnimating = false;
      instance.update(true, true, 0);
    }
    $(document).trigger('onFullscreenChange', FullScreen.isFullscreen());
  });
})(document, window.jQuery);

/***/ }),

/***/ 850:
/***/ ((__unused_webpack_module, __unused_webpack_exports, __webpack_require__) => {

/* provided dependency */ var jQuery = __webpack_require__(311);
// ==========================================================================
//
// Guestures
// Adds touch guestures, handles click and tap events
//
// ==========================================================================
(function (window, document, $) {
  'use strict';

  var requestAFrame = function () {
    return window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame || window.oRequestAnimationFrame ||
    // if all else fails, use setTimeout
    function (callback) {
      return window.setTimeout(callback, 1000 / 60);
    };
  }();
  var cancelAFrame = function () {
    return window.cancelAnimationFrame || window.webkitCancelAnimationFrame || window.mozCancelAnimationFrame || window.oCancelAnimationFrame || function (id) {
      window.clearTimeout(id);
    };
  }();
  var pointers = function pointers(e) {
    var result = [];
    e = e.originalEvent || e || window.e;
    e = e.touches && e.touches.length ? e.touches : e.changedTouches && e.changedTouches.length ? e.changedTouches : [e];
    for (var key in e) {
      if (e[key].pageX) {
        result.push({
          x: e[key].pageX,
          y: e[key].pageY
        });
      } else if (e[key].clientX) {
        result.push({
          x: e[key].clientX,
          y: e[key].clientY
        });
      }
    }
    return result;
  };
  var distance = function distance(point2, point1, what) {
    if (!point1 || !point2) {
      return 0;
    }
    if (what === 'x') {
      return point2.x - point1.x;
    } else if (what === 'y') {
      return point2.y - point1.y;
    }
    return Math.sqrt(Math.pow(point2.x - point1.x, 2) + Math.pow(point2.y - point1.y, 2));
  };
  var isClickable = function isClickable($el) {
    if ($el.is('a,area,button,[role="button"],input,label,select,summary,textarea') || $.isFunction($el.get(0).onclick) || $el.data('selectable')) {
      return true;
    }

    // Check for attributes like data-envirabox-next or data-envirabox-close
    for (var i = 0, atts = $el[0].attributes, n = atts.length; i < n; i++) {
      if (atts[i].nodeName.substr(0, 14) === 'data-envirabox-') {
        return true;
      }
    }
    return false;
  };
  var hasScrollbars = function hasScrollbars(el) {
    var overflowY = window.getComputedStyle(el)['overflow-y'];
    var overflowX = window.getComputedStyle(el)['overflow-x'];
    var vertical = (overflowY === 'scroll' || overflowY === 'auto') && el.scrollHeight > el.clientHeight;
    var horizontal = (overflowX === 'scroll' || overflowX === 'auto') && el.scrollWidth > el.clientWidth;
    return vertical || horizontal;
  };
  var isScrollable = function isScrollable($el) {
    var rez = false;
    while (true) {
      rez = hasScrollbars($el.get(0));
      if (rez) {
        break;
      }
      $el = $el.parent();
      if (!$el.length || $el.hasClass('envirabox-stage') || $el.is('body')) {
        break;
      }
    }
    return rez;
  };
  var Guestures = function Guestures(instance) {
    var self = this;
    self.instance = instance;
    self.$bg = instance.$refs.bg;
    self.$stage = instance.$refs.stage;
    self.$container = instance.$refs.container;
    self.destroy();
    self.$container.on('touchstart.eb.touch mousedown.eb.touch', $.proxy(self, 'ontouchstart'));
  };
  Guestures.prototype.destroy = function () {
    this.$container.off('.eb.touch');
  };
  Guestures.prototype.ontouchstart = function (e) {
    var self = this;
    var $target = $(e.target);
    var instance = self.instance;
    var current = instance.current;
    var $content = current.$content;
    var isTouchDevice = e.type == 'touchstart';

    // Do not respond to both events
    if (isTouchDevice) {
      self.$container.off('mousedown.eb.touch');
    }

    // Ignore clicks while zooming or closing
    if (!current || self.instance.isAnimating || self.instance.isClosing) {
      e.stopPropagation();
      e.preventDefault();
      return;
    }

    // Ignore right click
    if (e.originalEvent && e.originalEvent.button == 2) {
      return;
    }

    // Ignore taping on links, buttons, input elements
    if (!$target.length || isClickable($target) || isClickable($target.parent())) {
      return;
    }

    // Ignore clicks on the scrollbar
    if (e.originalEvent.clientX > $target[0].clientWidth + $target.offset().left) {
      return;
    }
    self.startPoints = pointers(e);

    // Prevent zooming if already swiping
    if (!self.startPoints || self.startPoints.length > 1 && instance.isSliding) {
      return;
    }
    self.$target = $target;
    self.$content = $content;
    self.canTap = true;
    self.opts = current.opts.touch;
    $(document).off('.eb.touch');
    $(document).on(isTouchDevice ? 'touchend.eb.touch touchcancel.eb.touch' : 'mouseup.eb.touch mouseleave.eb.touch', $.proxy(self, 'ontouchend'));
    $(document).on(isTouchDevice ? 'touchmove.eb.touch' : 'mousemove.eb.touch', $.proxy(self, 'ontouchmove'));
    if (!(self.opts || instance.canPan()) || !($target.is(self.$stage) || self.$stage.find($target).length)) {
      // Prevent ghosting
      if ($target.is('img')) {
        e.preventDefault();
      }
      return;
    }
    e.stopPropagation();
    if (!($.envirabox.isMobile && (isScrollable(self.$target) || isScrollable(self.$target.parent())))) {
      e.preventDefault();
    }
    self.canvasWidth = Math.round(current.$slide[0].clientWidth);
    self.canvasHeight = Math.round(current.$slide[0].clientHeight);
    self.startTime = new Date().getTime();
    self.distanceX = self.distanceY = self.distance = 0;
    self.isPanning = false;
    self.isSwiping = false;
    self.isZooming = false;
    self.sliderStartPos = self.sliderLastPos || {
      top: 0,
      left: 0
    };
    self.contentStartPos = $.envirabox.getTranslate(self.$content);
    self.contentLastPos = null;
    if (self.startPoints.length === 1 && !self.isZooming) {
      self.canTap = !instance.isSliding;
      if (current.type === 'image' && (self.contentStartPos.width > self.canvasWidth + 1 || self.contentStartPos.height > self.canvasHeight + 1)) {
        $.envirabox.stop(self.$content);
        self.$content.css('transition-duration', '0ms');
        self.isPanning = true;
      } else {
        self.isSwiping = true;
      }
      self.$container.addClass('envirabox-controls--isGrabbing');
    }
    if (self.startPoints.length === 2 && !instance.isAnimating && !current.hasError && current.type === 'image' && (current.isLoaded || current.$ghost)) {
      self.isZooming = true;
      self.isSwiping = false;
      self.isPanning = false;
      $.envirabox.stop(self.$content);
      self.$content.css('transition-duration', '0ms');
      self.centerPointStartX = (self.startPoints[0].x + self.startPoints[1].x) * 0.5 - $(window).scrollLeft();
      self.centerPointStartY = (self.startPoints[0].y + self.startPoints[1].y) * 0.5 - $(window).scrollTop();
      self.percentageOfImageAtPinchPointX = (self.centerPointStartX - self.contentStartPos.left) / self.contentStartPos.width;
      self.percentageOfImageAtPinchPointY = (self.centerPointStartY - self.contentStartPos.top) / self.contentStartPos.height;
      self.startDistanceBetweenFingers = distance(self.startPoints[0], self.startPoints[1]);
    }
  };
  Guestures.prototype.ontouchmove = function (e) {
    var self = this;
    self.newPoints = pointers(e);
    if ($.envirabox.isMobile && (isScrollable(self.$target) || isScrollable(self.$target.parent()))) {
      e.stopPropagation();
      self.canTap = false;
      return;
    }
    if (!(self.opts || self.instance.canPan()) || !self.newPoints || !self.newPoints.length) {
      return;
    }
    self.distanceX = distance(self.newPoints[0], self.startPoints[0], 'x');
    self.distanceY = distance(self.newPoints[0], self.startPoints[0], 'y');
    self.distance = distance(self.newPoints[0], self.startPoints[0]);

    // Skip false ontouchmove events (Chrome)
    if (self.distance > 0) {
      if (!(self.$target.is(self.$stage) || self.$stage.find(self.$target).length)) {
        return;
      }
      e.stopPropagation();
      e.preventDefault();
      if (self.isSwiping) {
        self.onSwipe();
      } else if (self.isPanning) {
        self.onPan();
      } else if (self.isZooming) {
        self.onZoom();
      }
    }
  };
  Guestures.prototype.onSwipe = function () {
    var self = this;
    var swiping = self.isSwiping;
    var left = self.sliderStartPos.left || 0;
    var angle;
    if (swiping === true) {
      if (Math.abs(self.distance) > 10) {
        self.canTap = false;
        if (self.instance.group.length < 2 && self.opts.vertical) {
          self.isSwiping = 'y';
        } else if (self.instance.isSliding || self.opts.vertical === false || self.opts.vertical === 'auto' && $(window).width() > 800) {
          self.isSwiping = 'x';
        } else {
          angle = Math.abs(Math.atan2(self.distanceY, self.distanceX) * 180 / Math.PI);
          self.isSwiping = angle > 45 && angle < 135 ? 'y' : 'x';
        }
        self.instance.isSliding = self.isSwiping;

        // Reset points to avoid jumping, because we dropped first swipes to calculate the angle
        self.startPoints = self.newPoints;
        $.each(self.instance.slides, function (index, slide) {
          $.envirabox.stop(slide.$slide);
          slide.$slide.css('transition-duration', '0ms');
          slide.inTransition = false;
          if (slide.pos === self.instance.current.pos) {
            self.sliderStartPos.left = $.envirabox.getTranslate(slide.$slide).left;
          }
        });

        // self.instance.current.isMoved = true;
        // Stop slideshow
        if (self.instance.SlideShow && self.instance.SlideShow.isActive) {
          self.instance.SlideShow.stop();
        }
      }
    } else {
      if (swiping == 'x') {
        // Sticky edges
        if (self.distanceX > 0 && (self.instance.group.length < 2 || self.instance.current.index === 0 && !self.instance.current.opts.loop)) {
          left = left + Math.pow(self.distanceX, 0.8);
        } else if (self.distanceX < 0 && (self.instance.group.length < 2 || self.instance.current.index === self.instance.group.length - 1 && !self.instance.current.opts.loop)) {
          left = left - Math.pow(-self.distanceX, 0.8);
        } else {
          left = left + self.distanceX;
        }
      }
      self.sliderLastPos = {
        top: swiping == 'x' ? 0 : self.sliderStartPos.top + self.distanceY,
        left: left
      };
      if (self.requestId) {
        cancelAFrame(self.requestId);
        self.requestId = null;
      }
      self.requestId = requestAFrame(function () {
        if (self.sliderLastPos) {
          $.each(self.instance.slides, function (index, slide) {
            var pos = slide.pos - self.instance.currPos;
            $.envirabox.setTranslate(slide.$slide, {
              top: self.sliderLastPos.top,
              left: self.sliderLastPos.left + pos * self.canvasWidth + pos * slide.opts.gutter
            });
          });
          self.$container.addClass('envirabox-is-sliding');
        }
      });
    }
  };
  Guestures.prototype.onPan = function () {
    var self = this;
    var newOffsetX, newOffsetY, newPos;
    self.canTap = false;
    if (self.contentStartPos.width > self.canvasWidth) {
      newOffsetX = self.contentStartPos.left + self.distanceX;
    } else {
      newOffsetX = self.contentStartPos.left;
    }
    newOffsetY = self.contentStartPos.top + self.distanceY;
    newPos = self.limitMovement(newOffsetX, newOffsetY, self.contentStartPos.width, self.contentStartPos.height);
    newPos.scaleX = self.contentStartPos.scaleX;
    newPos.scaleY = self.contentStartPos.scaleY;
    self.contentLastPos = newPos;
    if (self.requestId) {
      cancelAFrame(self.requestId);
      self.requestId = null;
    }
    self.requestId = requestAFrame(function () {
      $.envirabox.setTranslate(self.$content, self.contentLastPos);
    });
  };

  // Make panning sticky to the edges
  Guestures.prototype.limitMovement = function (newOffsetX, newOffsetY, newWidth, newHeight) {
    var self = this;
    var minTranslateX, minTranslateY, maxTranslateX, maxTranslateY;
    var canvasWidth = self.canvasWidth;
    var canvasHeight = self.canvasHeight;
    var currentOffsetX = self.contentStartPos.left;
    var currentOffsetY = self.contentStartPos.top;
    var distanceX = self.distanceX;
    var distanceY = self.distanceY;

    // Slow down proportionally to traveled distance
    minTranslateX = Math.max(0, canvasWidth * 0.5 - newWidth * 0.5);
    minTranslateY = Math.max(0, canvasHeight * 0.5 - newHeight * 0.5);
    maxTranslateX = Math.min(canvasWidth - newWidth, canvasWidth * 0.5 - newWidth * 0.5);
    maxTranslateY = Math.min(canvasHeight - newHeight, canvasHeight * 0.5 - newHeight * 0.5);
    if (newWidth > canvasWidth) {
      // ->
      if (distanceX > 0 && newOffsetX > minTranslateX) {
        newOffsetX = minTranslateX - 1 + Math.pow(-minTranslateX + currentOffsetX + distanceX, 0.8) || 0;
      }

      // <-
      if (distanceX < 0 && newOffsetX < maxTranslateX) {
        newOffsetX = maxTranslateX + 1 - Math.pow(maxTranslateX - currentOffsetX - distanceX, 0.8) || 0;
      }
    }
    if (newHeight > canvasHeight) {
      // \/
      if (distanceY > 0 && newOffsetY > minTranslateY) {
        newOffsetY = minTranslateY - 1 + Math.pow(-minTranslateY + currentOffsetY + distanceY, 0.8) || 0;
      }

      // \
      if (distanceY < 0 && newOffsetY < maxTranslateY) {
        newOffsetY = maxTranslateY + 1 - Math.pow(maxTranslateY - currentOffsetY - distanceY, 0.8) || 0;
      }
    }
    return {
      top: newOffsetY,
      left: newOffsetX
    };
  };
  Guestures.prototype.limitPosition = function (newOffsetX, newOffsetY, newWidth, newHeight) {
    var self = this;
    var canvasWidth = self.canvasWidth;
    var canvasHeight = self.canvasHeight;
    if (newWidth > canvasWidth) {
      newOffsetX = newOffsetX > 0 ? 0 : newOffsetX;
      newOffsetX = newOffsetX < canvasWidth - newWidth ? canvasWidth - newWidth : newOffsetX;
    } else {
      // Center horizontally
      newOffsetX = Math.max(0, canvasWidth / 2 - newWidth / 2);
    }
    if (newHeight > canvasHeight) {
      newOffsetY = newOffsetY > 0 ? 0 : newOffsetY;
      newOffsetY = newOffsetY < canvasHeight - newHeight ? canvasHeight - newHeight : newOffsetY;
    } else {
      // Center vertically
      newOffsetY = Math.max(0, canvasHeight / 2 - newHeight / 2);
    }
    return {
      top: newOffsetY,
      left: newOffsetX
    };
  };
  Guestures.prototype.onZoom = function () {
    var self = this;

    // Calculate current distance between points to get pinch ratio and new width and height
    var currentWidth = self.contentStartPos.width;
    var currentHeight = self.contentStartPos.height;
    var currentOffsetX = self.contentStartPos.left;
    var currentOffsetY = self.contentStartPos.top;
    var endDistanceBetweenFingers = distance(self.newPoints[0], self.newPoints[1]);
    var pinchRatio = endDistanceBetweenFingers / self.startDistanceBetweenFingers;
    var newWidth = Math.floor(currentWidth * pinchRatio);
    var newHeight = Math.floor(currentHeight * pinchRatio);

    // This is the translation due to pinch-zooming
    var translateFromZoomingX = (currentWidth - newWidth) * self.percentageOfImageAtPinchPointX;
    var translateFromZoomingY = (currentHeight - newHeight) * self.percentageOfImageAtPinchPointY;

    // Point between the two touches
    var centerPointEndX = (self.newPoints[0].x + self.newPoints[1].x) / 2 - $(window).scrollLeft();
    var centerPointEndY = (self.newPoints[0].y + self.newPoints[1].y) / 2 - $(window).scrollTop();

    // And this is the translation due to translation of the centerpoint
    // between the two fingers
    var translateFromTranslatingX = centerPointEndX - self.centerPointStartX;
    var translateFromTranslatingY = centerPointEndY - self.centerPointStartY;

    // The new offset is the old/current one plus the total translation
    var newOffsetX = currentOffsetX + (translateFromZoomingX + translateFromTranslatingX);
    var newOffsetY = currentOffsetY + (translateFromZoomingY + translateFromTranslatingY);
    var newPos = {
      top: newOffsetY,
      left: newOffsetX,
      scaleX: self.contentStartPos.scaleX * pinchRatio,
      scaleY: self.contentStartPos.scaleY * pinchRatio
    };
    self.canTap = false;
    self.newWidth = newWidth;
    self.newHeight = newHeight;
    self.contentLastPos = newPos;
    if (self.requestId) {
      cancelAFrame(self.requestId);
      self.requestId = null;
    }
    self.requestId = requestAFrame(function () {
      $.envirabox.setTranslate(self.$content, self.contentLastPos);
    });
  };
  Guestures.prototype.ontouchend = function (e) {
    var self = this;
    var dMs = Math.max(new Date().getTime() - self.startTime, 1);
    var swiping = self.isSwiping;
    var panning = self.isPanning;
    var zooming = self.isZooming;
    self.endPoints = pointers(e);
    self.$container.removeClass('envirabox-controls--isGrabbing');
    $(document).off('.eb.touch');
    if (self.requestId) {
      cancelAFrame(self.requestId);
      self.requestId = null;
    }
    self.isSwiping = false;
    self.isPanning = false;
    self.isZooming = false;
    if (self.canTap) {
      return self.onTap(e);
    }
    self.speed = 366;

    // Speed in px/ms
    self.velocityX = self.distanceX / dMs * 0.5;
    self.velocityY = self.distanceY / dMs * 0.5;
    self.speedX = Math.max(self.speed * 0.5, Math.min(self.speed * 1.5, 1 / Math.abs(self.velocityX) * self.speed));
    if (panning) {
      self.endPanning();
    } else if (zooming) {
      self.endZooming();
    } else {
      self.endSwiping(swiping);
    }
    return;
  };
  Guestures.prototype.endSwiping = function (swiping) {
    var self = this;
    var ret = false;
    self.instance.isSliding = false;
    self.sliderLastPos = null;

    // Close if swiped vertically / navigate if horizontally
    if (swiping == 'y' && Math.abs(self.distanceY) > 50) {
      // Continue vertical movement
      $.envirabox.animate(self.instance.current.$slide, {
        top: self.sliderStartPos.top + self.distanceY + self.velocityY * 150,
        opacity: 0
      }, 150);
      ret = self.instance.close(true, 300);
    } else if (swiping == 'x' && self.distanceX > 50 && self.instance.group.length > 1) {
      ret = self.instance.previous(self.speedX);
    } else if (swiping == 'x' && self.distanceX < -50 && self.instance.group.length > 1) {
      ret = self.instance.next(self.speedX);
    }
    if (ret === false && (swiping == 'x' || swiping == 'y')) {
      self.instance.jumpTo(self.instance.current.index, 150);
    }
    self.$container.removeClass('envirabox-is-sliding');
  };

  // Limit panning from edges
  // ========================
  Guestures.prototype.endPanning = function () {
    var self = this;
    var newOffsetX, newOffsetY, newPos;
    if (!self.contentLastPos) {
      return;
    }
    if (self.opts.momentum === false) {
      newOffsetX = self.contentLastPos.left;
      newOffsetY = self.contentLastPos.top;
    } else {
      // Continue movement
      newOffsetX = self.contentLastPos.left + self.velocityX * self.speed;
      newOffsetY = self.contentLastPos.top + self.velocityY * self.speed;
    }
    newPos = self.limitPosition(newOffsetX, newOffsetY, self.contentStartPos.width, self.contentStartPos.height);
    newPos.width = self.contentStartPos.width;
    newPos.height = self.contentStartPos.height;
    $.envirabox.animate(self.$content, newPos, 330);
  };
  Guestures.prototype.endZooming = function () {
    var self = this;
    var current = self.instance.current;
    var newOffsetX, newOffsetY, newPos, reset;
    var newWidth = self.newWidth;
    var newHeight = self.newHeight;
    if (!self.contentLastPos) {
      return;
    }
    newOffsetX = self.contentLastPos.left;
    newOffsetY = self.contentLastPos.top;
    reset = {
      top: newOffsetY,
      left: newOffsetX,
      width: newWidth,
      height: newHeight,
      scaleX: 1,
      scaleY: 1
    };

    // Reset scalex/scaleY values; this helps for perfomance and does not break animation
    $.envirabox.setTranslate(self.$content, reset);
    if (newWidth < self.canvasWidth && newHeight < self.canvasHeight) {
      self.instance.scaleToFit(150);
    } else if (newWidth > current.width || newHeight > current.height) {
      self.instance.scaleToActual(self.centerPointStartX, self.centerPointStartY, 150);
    } else {
      newPos = self.limitPosition(newOffsetX, newOffsetY, newWidth, newHeight);

      // Switch from scale() to width/height or animation will not work correctly
      $.envirabox.setTranslate(self.content, $.envirabox.getTranslate(self.$content));
      $.envirabox.animate(self.$content, newPos, 150);
    }
  };
  Guestures.prototype.onTap = function (e) {
    var self = this;
    var $target = $(e.target);
    var instance = self.instance;
    var current = instance.current;
    var endPoints = e && pointers(e) || self.startPoints;
    var tapX = endPoints[0] ? endPoints[0].x - self.$stage.offset().left : 0;
    var tapY = endPoints[0] ? endPoints[0].y - self.$stage.offset().top : 0;
    var where;
    var process = function process(prefix) {
      var action = current.opts[prefix];
      if ($.isFunction(action)) {
        action = action.apply(instance, [current, e]);
      }
      if (!action) {
        return;
      }
      switch (action) {
        case 'close':
          instance.close(self.startEvent);
          break;
        case 'toggleControls':
          instance.toggleControls(true);
          break;
        case 'next':
          instance.next();
          break;
        case 'nextOrClose':
          if (instance.group.length > 1) {
            instance.next();
          } else {
            instance.close(self.startEvent);
          }
          break;
        case 'zoom':
          if (current.type == 'image' && (current.isLoaded || current.$ghost)) {
            if (instance.canPan()) {
              instance.scaleToFit();
            } else if (instance.isScaledDown()) {
              instance.scaleToActual(tapX, tapY);
            } else if (instance.group.length < 2) {
              instance.close(self.startEvent);
            }
          }
          break;
      }
    };

    // Ignore right click
    if (e.originalEvent && e.originalEvent.button == 2) {
      return;
    }

    // Skip if current slide is not in the center
    if (instance.isSliding) {
      return;
    }

    // Skip if clicked on the scrollbar
    if (tapX > $target[0].clientWidth + $target.offset().left) {
      return;
    }

    // Check where is clicked
    if ($target.is('.envirabox-bg,.envirabox-inner,.envirabox-outer,.envirabox-container')) {
      where = 'Outside';
    } else if ($target.is('.envirabox-slide')) {
      where = 'Slide';
    } else if (instance.current.$content && instance.current.$content.has(e.target).length) {
      where = 'Content';
    } else {
      return;
    }

    // Check if this is a double tap
    if (self.tapped) {
      // Stop previously created single tap
      clearTimeout(self.tapped);
      self.tapped = null;

      // Skip if distance between taps is too big
      if (Math.abs(tapX - self.tapX) > 50 || Math.abs(tapY - self.tapY) > 50 || instance.isSliding) {
        return this;
      }

      // OK, now we assume that this is a double-tap
      process('dblclick' + where);
    } else {
      // Single tap will be processed if user has not clicked second time within 300ms
      // or there is no need to wait for double-tap
      self.tapX = tapX;
      self.tapY = tapY;
      if (current.opts['dblclick' + where] && current.opts['dblclick' + where] !== current.opts['click' + where]) {
        self.tapped = setTimeout(function () {
          self.tapped = null;
          process('click' + where);
        }, 300);
      } else {
        process('click' + where);
      }
    }
    return this;
  };
  $(document).on('onActivate.eb', function (e, instance) {
    if (instance && !instance.Guestures) {
      instance.Guestures = new Guestures(instance);
    }
  });
  $(document).on('beforeClose.eb', function (e, instance) {
    if (instance && instance.Guestures) {
      instance.Guestures.destroy();
    }
  });
})(window, document, window.jQuery || jQuery);

/***/ }),

/***/ 827:
/***/ (() => {

// ==========================================================================
//
// Media
// Adds additional media type support
//
// ==========================================================================
(function ($) {
  'use strict';

  // Formats matching url to final form
  var format = function format(url, rez, params) {
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
      matcher: /^http:\/\/(?:www\.)?youtube\.com\/watch\?((v=[^&\s]*&list=[^&\s]*)|(list=[^&\s]*&v=[^&\s]*))(&[^&\s]*)*$/,
      params: {
        autoplay: 1,
        autohide: 1,
        fs: 1,
        rel: 0,
        hd: 1,
        wmode: 'transparent',
        enablejsapi: 1,
        html5: 1
      },
      paramPlace: 8,
      type: 'iframe',
      url: '//www.youtube.com/embed/videoseries?list=$4',
      thumb: '//img.youtube.com/vi/$4/hqdefault.jpg'
    },
    youtube: {
      matcher: /(youtube\.com|youtu\.be|youtube\-nocookie\.com)\/(watch\?(.*&)?v=|v\/|u\/|embed\/?)?(videoseries\?list=(.*)|[\w-]{11}|\?listType=(.*)&list=(.*))(.*)/i,
      params: {
        autoplay: 1,
        autohide: 1,
        fs: 1,
        rel: 0,
        hd: 1,
        wmode: 'transparent',
        enablejsapi: 1,
        html5: 1
      },
      paramPlace: 8,
      type: 'iframe',
      url: '//www.youtube.com/embed/$4',
      thumb: '//img.youtube.com/vi/$4/hqdefault.jpg'
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
        api: 1
      },
      paramPlace: 3,
      type: 'iframe',
      url: '//player.vimeo.com/video/$2'
    },
    metacafe: {
      matcher: /metacafe.com\/watch\/(\d+)\/(.*)?/,
      type: 'iframe',
      url: '//www.metacafe.com/embed/$1/?ap=1'
    },
    dailymotion: {
      matcher: /dailymotion.com\/video\/(.*)\/?(.*)/,
      params: {
        additionalInfos: 0,
        autoStart: 1
      },
      type: 'iframe',
      url: '//www.dailymotion.com/embed/video/$1'
    },
    facebook: {
      matcher: /facebook.com\/facebook\/videos\/(.*)\/?(.*)/,
      type: 'genericDiv',
      subtype: 'facebook',
      url: '//www.facebook.com/facebook/videos/$1'
    },
    instagram: {
      matcher: /(instagr\.am|instagram\.com)\/p\/([a-zA-Z0-9_\-]+)\/?/i,
      type: 'image',
      url: '//$1/p/$2/media/?size=l'
    },
    instagram_tv: {
      matcher: /(instagr\.am|instagram\.com)\/tv\/([a-zA-Z0-9_\-]+)\/?/i,
      type: 'iframe',
      url: '//$1/p/$2/media/?size=l'
    },
    wistia: {
      matcher: /wistia.com\/medias\/(.*)\/?(.*)/,
      type: 'iframe',
      url: '//fast.wistia.net/embed/iframe/$1'
    },
    // Example: //player.twitch.tv/?video=270862436ß
    twitch: {
      matcher: /player.twitch.tv\/[\\?&]video=([^&#]*)/,
      type: 'iframe',
      url: '//player.twitch.tv/?video=$1'
    },
    // Example: //videopress.com/v/DK5mLrbr    ?at=1374&loop=1&autoplay=1
    videopress: {
      matcher: /videopress.com\/v\/(.*)\/?(.*)/,
      type: 'iframe',
      url: '//videopress.com/embed/$1'
    },
    // Examples:
    // http://maps.google.com/?ll=48.857995,2.294297&spn=0.007666,0.021136&t=m&z=16
    // https://www.google.com/maps/@37.7852006,-122.4146355,14.65z
    // https://www.google.com/maps/place/Googleplex/@37.4220041,-122.0833494,17z/data=!4m5!3m4!1s0x0:0x6c296c66619367e0!8m2!3d37.4219998!4d-122.0840572
    gmap_place: {
      matcher: /(maps\.)?google\.([a-z]{2,3}(\.[a-z]{2})?)\/(((maps\/(place\/(.*)\/)?\@(.*),(\d+.?\d+?)z))|(\?ll=))(.*)?/i,
      type: 'iframe',
      url: function url(rez) {
        return '//maps.google.' + rez[2] + '/?ll=' + (rez[9] ? rez[9] + '&z=' + Math.floor(rez[10]) + (rez[12] ? rez[12].replace(/^\//, '&') : '') : rez[12]) + '&output=' + (rez[12] && rez[12].indexOf('layer=c') > 0 ? 'svembed' : 'embed');
      }
    },
    // Examples:
    // https://www.google.com/maps/search/Empire+State+Building/
    // https://www.google.com/maps/search/?api=1&query=centurylink+field
    // https://www.google.com/maps/search/?api=1&query=47.5951518,-122.3316393
    gmap_search: {
      matcher: /(maps\.)?google\.([a-z]{2,3}(\.[a-z]{2})?)\/(maps\/search\/)(.*)/i,
      type: 'iframe',
      url: function url(rez) {
        return '//maps.google.' + rez[2] + '/maps?q=' + rez[5].replace('query=', 'q=').replace('api=1', '') + '&output=embed';
      }
    }
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
              o[p[0]] = decodeURIComponent(p[1].replace(/\+/g, ' '));
            }
          }
        }
        params = $.extend(true, {}, el.params, item.opts[n], o);
        url = $.type(el.url) === 'function' ? el.url.call(this, rez, params, item) : format(el.url, rez, params);
        thumb = $.type(el.thumb) === 'function' ? el.thumb.call(this, rez, params, item) : format(el.thumb, rez);
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
        if (!item.opts.thumb && !(item.opts.$thumb && item.opts.$thumb.length)) {
          item.opts.thumb = thumb;
        }
        if (type === 'iframe') {
          $.extend(true, item.opts, {
            iframe: {
              preload: false,
              provider: provider,
              attr: {
                scrolling: 'no'
              }
            }
          });
          item.contentProvider = provider;
          item.opts.slideClass += ' envirabox-slide--' + (provider == 'gmap_place' || provider == 'gmap_search' ? 'map' : 'video');
        }
      } else {
        // If no content type is found, then set it to `image` as fallback
        item.type = 'image';
      }
    });
  });
})(window.jQuery);

/***/ }),

/***/ 75:
/***/ (() => {

// ==========================================================================
//
// SlideShow
// Enables slideshow functionality
//
// Example of usage:
// $.envirabox.getInstance().SlideShow.start()
//
// ==========================================================================
(function (document, $) {
  'use strict';

  var SlideShow = function SlideShow(instance) {
    this.instance = instance;
    this.init();
  };
  $.extend(SlideShow.prototype, {
    timer: null,
    isActive: false,
    $button: null,
    speed: 3000,
    init: function init() {
      var self = this;
      self.$button = $('[data-envirabox-play]').on('click', function (e) {
        e.preventDefault();
        self.toggle();
      });
      if (self.instance.group.length < 2 || !self.instance.group[self.instance.currIndex].opts.slideShow) {
        self.$button.hide();
      }
    },
    set: function set() {
      var self = this;

      // Check if reached last element
      if (self.instance && self.instance.current && (self.instance.current.opts.loop || self.instance.currIndex < self.instance.group.length - 1)) {
        self.timer = setTimeout(function () {
          if (self.isActive == true) {
            self.instance.next();
          }
        }, self.instance.current.opts.slideShow.speed || self.speed);
      } else {
        self.stop();
        self.instance.idleSecondsCounter = 0;
        self.instance.showControls();
      }
    },
    clear: function clear() {
      var self = this;
      clearTimeout(self.timer);
      self.timer = null;
    },
    start: function start() {
      var self = this;
      var current = self.instance.current;
      if (self.instance && current && (current.opts.loop || current.index < self.instance.group.length - 1)) {
        self.isActive = true;
        self.$button.attr('title', current.opts.i18n[current.opts.lang].PLAY_STOP).addClass('envirabox-button--pause');
        self.$button.parent().addClass('envirabox-button--pause');
        if (current.isComplete) {
          self.set();
        }
      }
    },
    stop: function stop() {
      var self = this;
      var current = self.instance.current;
      self.clear();
      self.$button.attr('title', current.opts.i18n[current.opts.lang].PLAY_START).removeClass('envirabox-button--pause');
      self.$button.parent().removeClass('envirabox-button--pause');
      self.isActive = false;
    },
    toggle: function toggle() {
      var self = this;
      if (self.isActive) {
        self.stop();
      } else {
        self.start();
      }
    }
  });
  $(document).on({
    'onInit.eb': function onInitEb(e, instance) {
      if (instance && !instance.SlideShow) {
        instance.SlideShow = new SlideShow(instance);
      }
    },
    'beforeShow.eb': function beforeShowEb(e, instance, current, firstRun) {
      var SlideShow = instance && instance.SlideShow;
      if (firstRun) {
        if (SlideShow && current.opts.slideShow.autoStart) {
          SlideShow.start();
        }
      } else if (SlideShow && SlideShow.isActive) {
        SlideShow.clear();
      }
    },
    'afterShow.eb': function afterShowEb(e, instance, current) {
      var SlideShow = instance && instance.SlideShow;
      if (SlideShow && SlideShow.isActive) {
        SlideShow.set();
      }
    },
    'afterKeydown.eb': function afterKeydownEb(e, instance, current, keypress, keycode) {
      var SlideShow = instance && instance.SlideShow;

      // "P" or Spacebar
      if (SlideShow && current.opts.slideShow && (keycode === 80 || keycode === 32) && !$(document.activeElement).is('button,a,input')) {
        keypress.preventDefault();
        SlideShow.toggle();
      }
    },
    'beforeClose.eb onDeactivate.eb': function beforeCloseEbOnDeactivateEb(e, instance) {
      var SlideShow = instance && instance.SlideShow;
      if (SlideShow) {
        SlideShow.stop();
      }
    }
  });

  // Page Visibility API to pause slideshow when window is not active
  $(document).on('visibilitychange', function () {
    var instance = $.envirabox.getInstance();
    var SlideShow = instance && instance.SlideShow;
    if (SlideShow && SlideShow.isActive) {
      if (document.hidden) {
        SlideShow.clear();
      } else {
        SlideShow.set();
      }
    }
  });
})(document, window.jQuery);

/***/ }),

/***/ 655:
/***/ (() => {

// ==========================================================================
//
// Thumbs
// Displays thumbnails in a grid
//
// ==========================================================================
(function (document, $) {
  'use strict';

  var EnviraboxThumbs = function EnviraboxThumbs(instance) {
    this.instance = instance;
    this.init();
  };
  $.extend(EnviraboxThumbs.prototype, {
    $button: null,
    $grid: null,
    $list: null,
    isVisible: false,
    objects: null,
    init: function init() {
      var self = this,
        handled = false,
        first = self.instance.group[0],
        second = self.instance.group[1];
      self.$button = self.instance.$refs.toolbar.find('[data-envirabox-thumbs]');
      if (self.instance.group.length > 1 && self.instance.group[self.instance.currIndex].opts.thumbs && (first.type == 'image' || first.opts.thumb || first.opts.$thumb) && (second.type == 'image' || second.opts.thumb || second.opts.$thumb)) {
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
    create: function create() {
      var self = this,
        instance = self.instance,
        options = instance.opts.thumbs,
        list,
        src,
        $the_height,
        thumbnail_play_icon,
        $row;
      self.$grid = $('<div class="envirabox-thumbs envirabox-thumbs-' + options.position + '"></div>').appendTo(instance.$refs.container);
      var defaultHeight = instance.opts.lightbox_theme === 'classical_dark' || instance.opts.lightbox_theme === 'classical_light' ? 52 : 50;
      $row = options.rowHeight === 'auto' ? defaultHeight : options.rowHeight;
      if (false === $row) {
        list = '<ul>';
      } else {
        list = '<ul style="height:' + $row + 'px">';
      }
      $.each(instance.group, function (i, item) {
        src = item.opts.thumb || (item.opts.$thumb ? item.opts.$thumb.attr('src') : null);
        thumbnail_play_icon = item.opts.videoPlayIcon ? item.opts.videoPlayIcon : false;
        if (!src && item.type === 'image') {
          src = item.src;
        }
        if (src && src.length) {
          var contentProvider = item.contentProvider !== undefined ? item.contentProvider : 'none';
          list += '<li data-index="' + i + '"  tabindex="0" class="envirabox-thumbs-loading envirabox-thumb-content-provider-' + contentProvider + ' envirabox-thumb-type-' + item.type + '"><img class="envirabox-thumb-image-' + item.type + ' envirabox-thumb-content-provider-' + contentProvider + '" data-src="' + src + '" />';
          if (thumbnail_play_icon && (item.type == 'video' || item.type == 'genericDiv' || contentProvider == 'youtube' || contentProvider == 'vimeo' || contentProvider == 'instagram' || contentProvider == 'twitch' || contentProvider == 'dailymotion' || contentProvider == 'metacafe' || contentProvider == 'wistia' || contentProvider == 'videopress')) {
            list += '<div class="envira-video-play-icon">Play</div>';
          }
          list += '</li>';
        }
      });
      list += '</ul>';
      this.$list = $(list).appendTo(this.$grid).on('click touchstart', 'li', function () {
        instance.jumpTo($(this).data('index'));
      });
      this.$list.find('img').one('load', function () {
        var $parent = $(this).parent().removeClass('envirabox-thumbs-loading'),
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
        $(this).css({
          width: 'auto',
          height: Math.floor(height),
          'margin-top': Math.min(0, Math.floor(thumbHeight * 0.3 - height * 0.3)),
          'margin-left': Math.min(0, Math.floor(thumbWidth * 0.5 - width * 0.5))
        }).show();
      }).each(function () {
        this.src = $(this).data('src');
      });
    },
    focus: function focus() {
      if (this.instance.current) {
        this.$list.children().removeClass('envirabox-thumbs-active').filter('[data-index="' + this.instance.current.index + '"]').addClass('envirabox-thumbs-active').focus();
      }
    },
    close: function close() {
      this.$grid.hide();
    },
    update: function update() {
      this.instance.$refs.container.toggleClass('envirabox-show-thumbs', this.isVisible);
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
    hide: function hide() {
      this.isVisible = false;
      this.update();
    },
    show: function show() {
      this.isVisible = true;
      this.update();
    },
    toggle: function toggle() {
      this.isVisible = !this.isVisible;
      this.update();
    }
  });
  $(document).on({
    'onInit.eb': function onInitEb(e, instance) {
      if (instance && !instance.Thumbs) {
        instance.Thumbs = new EnviraboxThumbs(instance);
      }
    },
    'beforeShow.eb': function beforeShowEb(e, instance, item, firstRun) {
      var Thumbs = instance && instance.Thumbs;
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
    'afterKeydown.eb': function afterKeydownEb(e, instance, current, keypress, keycode) {
      var Thumbs = instance && instance.Thumbs;

      // "G"
      if (Thumbs && Thumbs.isActive && keycode === 71) {
        keypress.preventDefault();
        Thumbs.toggle();
      }
    },
    'beforeClose.eb': function beforeCloseEb(e, instance) {
      var Thumbs = instance && instance.Thumbs;
      if (Thumbs && Thumbs.isVisible && instance.opts.thumbs.hideOnClose !== false) {
        Thumbs.close();
      }
    }
  });
})(document, window.jQuery);

/***/ }),

/***/ 746:
/***/ ((__unused_webpack_module, __unused_webpack_exports, __webpack_require__) => {

/* provided dependency */ var jQuery = __webpack_require__(311);
(function (document, $) {
  'use strict';

  var prevTime = new Date().getTime();
  $(document).on({
    'onInit.eb': function onInitEb(e, instance, current) {
      instance.$refs.stage.on('mousewheel DOMMouseScroll wheel MozMousePixelScroll', function (e) {
        var current = instance.current,
          currTime = new Date().getTime();
        if (instance.group.length < 1 || current.opts.wheel === false || current.opts.wheel === 'auto' && current.type !== 'image') {
          return;
        }
        e.preventDefault();
        e.stopPropagation();
        if (current.$slide.hasClass('envirabox-animated')) {
          return;
        }
        e = e.originalEvent || e;
        if (currTime - prevTime < 250) {
          return;
        }
        prevTime = currTime;
        instance[(-e.deltaY || -e.deltaX || e.wheelDelta || -e.detail) < 0 ? 'next' : 'previous']();
      });
    }
  });
})(document, window.jQuery || jQuery);

/***/ }),

/***/ 962:
/***/ ((__unused_webpack_module, __unused_webpack_exports, __webpack_require__) => {

/* provided dependency */ var jQuery = __webpack_require__(311);
function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
(function (window, document, $, undefined) {
  'use strict';

  window.console = window.console || {
    info: function info(stuff) {}
  };

  // If there's no jQuery, envirabox can't work
  // =========================================
  if (!$) {
    return;
  }

  // Check if envirabox is already initialized
  // ========================================
  if ($.fn.envirabox) {
    console.info('EnviraBox already initialized');
    return;
  }

  // Private default settings
  // ========================
  var defaults = {
    // Enable infinite gallery navigation
    loop: false,
    // Space around image, ignored if zoomed-in or viewport width is smaller than 800px
    margin: [44, 0],
    // Horizontal space between slides
    gutter: 50,
    // Enable keyboard navigation
    keyboard: true,
    // Should display navigation arrows at the screen edges
    arrows: true,
    // Should display infobar (counter and arrows at the top)
    infobar: false,
    // Should display toolbar (buttons at the top)
    toolbar: true,
    // What buttons should appear in the top right corner.
    // Buttons will be created using templates from `btnTpl` option
    // and they will be placed into toolbar (class="envirabox-toolbar"` element)
    buttons: ['slideShow', 'fullScreen', 'thumbs', 'close',
    // "zoom",
    // "share",
    // "slideShow",
    // "fullScreen",
    // "download",
    'thumbs', 'close'],
    // Detect "idle" time in seconds
    idleTime: 3,
    // Should display buttons at top right corner of the content
    // If 'auto' - they will be created for content having type 'html', 'inline' or 'ajax'
    // Use template from `btnTpl.smallBtn` for customization
    smallBtn: true,
    // Disable right-click and use simple image protection for images
    protect: false,
    // Shortcut to make content "modal" - disable keyboard navigtion, hide buttons, etc
    modal: false,
    image: {
      // Wait for images to load before displaying
      // Requires predefined image dimensions
      // If 'auto' - will zoom in thumbnail if 'width' and 'height' attributes are found
      preload: 'auto'
    },
    ajax: {
      // Object containing settings for ajax request
      settings: {
        // This helps to indicate that request comes from the modal
        // Feel free to change naming
        data: {
          envirabox: true
        }
      }
    },
    iframe: {
      // Iframe template
      tpl: '<iframe id="envirabox-frame{rnd}" name="envirabox-frame{rnd}" class="envirabox-iframe {additionalClasses}" frameborder="0" vspace="0" hspace="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen allowtransparency="true" src=""></iframe>',
      // Preload iframe before displaying it
      // This allows to calculate iframe content width and height
      // (note: Due to "Same Origin Policy", you can't get cross domain data).
      preload: true,
      // Custom CSS styling for iframe wrapping element
      // You can use this to set custom iframe dimensions
      css: {},
      // Iframe tag attributes
      attr: {
        scrolling: 'auto'
      }
    },
    genericDiv: {
      // Iframe template
      tpl: '<div id="envirabox-generic-div{rnd}" name="envirabox-generic-div{rnd}" class="fb-video"></div>',
      // Preload iframe before displaying it
      // This allows to calculate iframe content width and height
      // (note: Due to "Same Origin Policy", you can't get cross domain data).
      preload: true,
      provider: 'facebook',
      // Custom CSS styling for iframe wrapping element
      // You can use this to set custom iframe dimensions
      css: {},
      // Iframe tag attributes
      attr: {
        scrolling: 'auto'
      }
    },
    // Open/close animation type
    // Possible values:
    // false            - disable
    // "zoom"           - zoom images from/to thumbnail
    // "fade"
    // "zoom-in-out"
    //
    animationEffect: 'zoom',
    // Duration in ms for open/close animation
    animationDuration: 366,
    // Should image change opacity while zooming
    // If opacity is 'auto', then opacity will be changed if image and thumbnail have different aspect ratios
    zoomOpacity: 'auto',
    // Transition effect between slides
    //
    // Possible values:
    // false            - disable
    // "fade'
    // "slide'
    // "circular'
    // "tube'
    // "zoom-in-out'
    // "rotate'
    //
    transitionEffect: 'fade',
    // Duration in ms for transition animation
    transitionDuration: 366,
    // Custom CSS class for slide element
    slideClass: 'enviraboxSlide',
    // Custom CSS class for layout
    baseClass: 'enviraboxLayout',
    // Base template for layout
    baseTpl: '<div class="envirabox-container" role="dialog">' + '<div class="envirabox-bg"></div>' + '<div class="envirabox-inner">' + '<div class="envirabox-infobar">' + '<button data-envirabox-prev title="{{PREV}}" class="envirabox-button envirabox-button--left"></button>' + '<div class="envirabox-infobar__body">' + '<span data-envirabox-index></span>&nbsp;/&nbsp;<span data-envirabox-count></span>' + '</div>' + '<button data-envirabox-next title="{{NEXT}}" class="envirabox-button envirabox-button--right"></button>' + '</div>' + '<div class="envirabox-toolbar">' + '{{BUTTONS}}' + '</div>' + '<div class="envirabox-navigation">' + '<button data-envirabox-prev title="{{PREV}}" class="envirabox-arrow envirabox-arrow--left" />' + '<button data-envirabox-next title="{{NEXT}}" class="envirabox-arrow envirabox-arrow--right" />' + '</div>' + '<div class="envirabox-stage"></div>' + '<div class="envirabox-caption-wrap">' + '<div class="envirabox-title"></div>' + '<div class="envirabox-caption"></div>' + '</div>' + '</div>' + '</div>',
    // Loading indicator template
    spinnerTpl: '<div class="envirabox-loading"></div>',
    // Error message template
    errorTpl: '<div class="envirabox-error"><p>{{ERROR}}<p></div>',
    btnTpl: {
      slideShow: '<button data-envirabox-play class="envirabox-button envirabox-button--play" title="{{PLAY_START}}"></button>',
      fullScreen: '<button data-envirabox-fullscreen class="envirabox-button envirabox-button--fullscreen" title="{{FULL_SCREEN}}"></button>',
      thumbs: '<button data-envirabox-thumbs class="envirabox-button envirabox-button--thumbs" title="{{THUMBS}}"></button>',
      close: '<button data-envirabox-close class="envirabox-button envirabox-button--close" title="{{CLOSE}}"></button>',
      download: '',
      exif: '',
      // This small close button will be appended to your html/inline/ajax content by default,
      // if "smallBtn" option is not set to false
      smallBtn: '<button data-envirabox-close class="envirabox-close-small" title="{{CLOSE}}"></button>',
      arrowLeft: '',
      arrowRight: ''
    },
    // Container is injected into this element
    parentEl: 'body',
    // Focus handling
    // ==============
    // Try to focus on the first focusable element after opening
    autoFocus: true,
    // Put focus back to active element after closing
    backFocus: true,
    // Do not let user to focus on element outside modal content
    trapFocus: true,
    // Module specific options
    // =======================
    fullScreen: {
      autoStart: false
    },
    // Set `touch: false` to disable dragging/swiping
    touch: {
      vertical: true,
      // Allow to drag content vertically
      momentum: true // Continue movement after releasing mouse/touch when panning
    },

    // Hash value when initializing manually,
    // set `false` to disable hash change
    hash: null,
    // Customize or add new media types
    // Example:
    /*
    media : {
    	youtube : {
    		params : {
    			autoplay : 0
    		}
    	}
    }
    */
    media: {},
    slideShow: {
      autoStart: false,
      speed: 4000
    },
    thumbs: {
      autoStart: false,
      // Display thumbnails on opening
      hideOnClose: true,
      // Hide thumbnail grid when closing animation starts
      parentEl: '.envirabox-container',
      axis: 'y',
      rowHeight: 50
    },
    wheel: 'auto',
    // Callbacks
    // ==========
    onInit: $.noop,
    // When instance has been initialized

    beforeLoad: $.noop,
    // Before the content of a slide is being loaded
    afterLoad: $.noop,
    // When the content of a slide is done loading

    beforeShow: $.noop,
    // Before open animation starts
    afterShow: $.noop,
    // When content is done loading and animating

    beforeClose: $.noop,
    // Before the instance attempts to close. Return false to cancel the close.
    afterClose: $.noop,
    // After instance has been closed

    onActivate: $.noop,
    // When instance is brought to front
    onDeactivate: $.noop,
    // When other instance has been activated

    // Interaction
    // ===========
    // Use options below to customize taken action when user clicks or double clicks on the envirabox area,
    // each option can be string or method that returns value.
    //
    // Possible values:
    // "close"           - close instance
    // "next"            - move to next gallery item
    // "nextOrClose"     - move to next gallery item or close if gallery has only one item
    // "toggleControls"  - show/hide controls
    // "zoom"            - zoom image (if loaded)
    // false             - do nothing
    // Clicked on the content
    clickContent: function clickContent(current, event) {
      return current.type === 'image' ? 'zoom' : false;
    },
    // Clicked on the slide
    clickSlide: 'close',
    // Clicked on the background (backdrop) element
    clickOutside: 'close',
    // Same as previous two, but for double click
    dblclickContent: false,
    dblclickSlide: false,
    dblclickOutside: false,
    // Custom options when mobile device is detected
    // =============================================
    mobile: {
      clickContent: function clickContent(current, event) {
        return current.type === 'image' ? 'toggleControls' : false;
      },
      clickSlide: function clickSlide(current, event) {
        return current.type === 'image' ? 'toggleControls' : 'close';
      },
      dblclickContent: function dblclickContent(current, event) {
        return current.type === 'image' ? 'zoom' : false;
      },
      dblclickSlide: function dblclickSlide(current, event) {
        return current.type === 'image' ? 'zoom' : false;
      }
    },
    // Internationalization
    // ============
    lang: 'en',
    i18n: {
      en: {
        CLOSE: 'Close',
        NEXT: 'Next',
        PREV: 'Previous',
        ERROR: 'The requested content cannot be loaded. <br/> Please try again later.',
        PLAY_START: 'Start slideshow',
        PLAY_STOP: 'Pause slideshow',
        FULL_SCREEN: 'Full screen',
        THUMBS: 'Thumbnails'
      },
      de: {
        CLOSE: 'Schliessen',
        NEXT: 'Weiter',
        PREV: 'Zurück',
        ERROR: 'Die angeforderten Daten konnten nicht geladen werden. <br/> Bitte versuchen Sie es später nochmal.',
        PLAY_START: 'Diaschau starten',
        PLAY_STOP: 'Diaschau beenden',
        FULL_SCREEN: 'Vollbild',
        THUMBS: 'Vorschaubilder'
      }
    }
  };

  // Few useful letiables and methods
  // ================================
  var $W = $(window),
    $D = $(document),
    called = 0;

  // Check if an object is a jQuery object and not a native JavaScript object
  // ========================================================================
  var isQuery = function isQuery(obj) {
    return obj && obj.hasOwnProperty && obj instanceof $;
  };

  // Handle multiple browsers for "requestAnimationFrame" and "cancelAnimationFrame"
  // ===============================================================================
  var requestAFrame = function () {
    return window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame || window.oRequestAnimationFrame ||
    // if all else fails, use setTimeout
    function (callback) {
      return window.setTimeout(callback, 1000 / 60);
    };
  }();

  // Detect the supported transition-end event property name
  // =======================================================
  var transitionEnd = function () {
    var t,
      el = document.createElement('fakeelement');
    var transitions = {
      transition: 'transitionend',
      OTransition: 'oTransitionEnd',
      MozTransition: 'transitionend',
      WebkitTransition: 'webkitTransitionEnd'
    };
    for (t in transitions) {
      if (el.style[t] !== undefined) {
        return transitions[t];
      }
    }
    return 'transitionend';
  }();

  // Force redraw on an element.
  // This helps in cases where the browser doesn't redraw an updated element properly.
  // =================================================================================
  var forceRedraw = function forceRedraw($el) {
    return $el && $el.length && $el[0].offsetHeight;
  };

  // Exclude array (`buttons`) options from deep merging
  // ===================================================
  var mergeOpts = function mergeOpts(opts1, opts2) {
    var rez = $.extend(true, {}, opts1, opts2);
    $.each(opts2, function (key, value) {
      if ($.isArray(value)) {
        rez[key] = value;
      }
    });
    return rez;
  };

  // Class definition
  // ================
  var EnviraBox = function EnviraBox(content, opts, index) {
    var self = this;
    self.opts = $.extend(true, {
      index: index
    }, defaults, opts || {});

    // Exclude buttons option from deep merging
    if (opts && $.isArray(opts.buttons)) {
      self.opts.buttons = opts.buttons;
    }
    self.id = self.opts.id || ++called;
    self.group = [];
    self.currIndex = parseInt(self.opts.index, 10) || 0;
    self.prevIndex = null;
    self.prevPos = null;
    self.currPos = 0;
    self.firstRun = null;

    // Create group elements from original item collection
    self.createGroup(content);
    if (!self.group.length) {
      return;
    }

    // Save last active element and current scroll position
    self.$lastFocus = $(document.activeElement).blur();

    // Collection of gallery objects
    self.slides = {};
    self.init(content);
  };
  $.urlParam = function (name) {
    var results = new RegExp('[?&]' + name + '=([^&#]*)').exec(window.location.href);
    if (results == null) {
      return null;
    } else {
      return decodeURI(results[1]) || 0;
    }
  };
  $.extend(EnviraBox.prototype, {
    // Create DOM structure
    // ====================
    init: function init() {
      var self = this,
        testWidth,
        $container,
        buttonStr,
        page = false;
      if ($.urlParam('envira_page')) {
        page = $.urlParam('envira_page');
      }
      var lastFocusElement = self.$lastFocus[0];
      if (self.group[self.currIndex] === undefined || self.group[self.currIndex].opts === undefined) {
        if (page !== false && page > 1) {
          // self.currIndex = self.currIndex - ( ( page - 1 ) * ( (self.group).length ) );
          self.currIndex = $('div#envira-gallery-wrap-' + lastFocusElement.dataset.envirabox + ' div#envira-gallery-item-' + lastFocusElement.dataset.enviraItemId).index();
        } else {
          // self.currIndex = self.currIndex - (self.group).length;
          self.currIndex = $('div#envira-gallery-wrap-' + lastFocusElement.dataset.envirabox + ' div#envira-gallery-item-' + lastFocusElement.dataset.enviraItemId).index();
        }
      }
      var firstItemOpts = self.group[self.currIndex] !== undefined && self.group[self.currIndex].opts !== undefined ? self.group[self.currIndex].opts : false;
      if (firstItemOpts === false) {
        var gallery_id = self.opts.galleryID,
          per_page = self.opts.perPage,
          numItems = $('a.envira-gallery-' + gallery_id).length;
        firstItemOpts = self.group[self.id - 1].opts;
        self.currIndex = self.id - 1;
      }
      if (false === firstItemOpts || firstItemOpts.baseTpl === undefined) {
        return;
      }
      self.scrollTop = $D.scrollTop();
      self.scrollLeft = $D.scrollLeft();

      // Hide scrollbars
      // ===============
      if (!$.envirabox.getInstance() && !$.envirabox.isMobile && $('body').css('overflow') !== 'hidden') {
        testWidth = $('body').width();
        $('html').addClass('envirabox-enabled');

        // Compare body width after applying "overflow: hidden"
        testWidth = $('body').width() - testWidth;

        // If width has changed - compensate missing scrollbars by adding right margin
        if (testWidth > 1) {
          $('head').append('<style id="envirabox-style-noscroll" type="text/css">.compensate-for-scrollbar, .envirabox-enabled body { margin-right: ' + testWidth + 'px; }</style>');
        }
      }

      // Build html markup and set references
      // ====================================
      // Build html code for buttons and insert into main template
      buttonStr = '';
      $.each(firstItemOpts.buttons, function (index, value) {
        buttonStr += firstItemOpts.btnTpl[value] || '';
      });

      // Create markup from base template, it will be initially hidden to
      // avoid unnecessary work like painting while initializing is not complete
      $container = $(self.translate(self, firstItemOpts.baseTpl.replace('{{BUTTONS}}', buttonStr))).addClass('envirabox-is-hidden').attr('id', 'envirabox-container-' + self.id).addClass(firstItemOpts.baseClass).data('EnviraBox', self).prependTo(firstItemOpts.parentEl);

      // Create object holding references to jQuery wrapped nodes
      self.$refs = {
        container: $container
      };
      ['bg', 'inner', 'infobar', 'toolbar', 'stage', 'caption', 'title'].forEach(function (item) {
        self.$refs[item] = $container.find('.envirabox-' + item);
      });

      // Check for redundant elements
      if (!firstItemOpts.arrows || self.group.length < 2) {
        $container.find('.envirabox-navigation').remove();
      }
      if (!firstItemOpts.infobar) {
        self.$refs.infobar.remove();
      }
      if (!firstItemOpts.toolbar) {
        self.$refs.toolbar.remove();
      }
      self.trigger('onInit');

      // Bring to front and enable events
      self.activate();

      // Build slides, load and reveal content
      self.jumpTo(self.currIndex);
    },
    // Simple i18n support - replaces object keys found in template
    // with corresponding values
    // ============================================================
    translate: function translate(obj, str) {
      var arr = obj.opts.i18n[obj.opts.lang];
      return str.replace(/\{\{(\w+)\}\}/g, function (match, n) {
        var value = arr[n];
        if (value === undefined) {
          return match;
        }
        return value;
      });
    },
    // Create array of gallery item objects
    // Check if each object has valid type and content
    // ===============================================
    createGroup: function createGroup(content) {
      var self = this,
        items = $.makeArray(content);
      $.each(items, function (i, item) {
        var obj = {},
          opts = {},
          data = [],
          $item,
          type,
          src,
          found,
          srcParts;

        // Step 1 - Make sure we have an object
        // ====================================
        if ($.isPlainObject(item)) {
          // We probably have manual usage here, something like
          // $.envirabox.open( [ { src : "image.jpg", type : "image" } ] )
          obj = item;
          opts = item.opts || item;
        } else if ($.type(item) === 'object' && $(item).length) {
          // Here we propbably have jQuery collection returned by some selector
          $item = $(item);
          data = $item.data();
          opts = 'options' in data ? data.options : {};
          opts = $.type(opts) === 'object' ? opts : {};
          obj.src = 'src' in data ? data.src : opts.src || $item.attr('href');

          // Make sure our Obj has all of our keys
          for (var key in data) {
            if (data.hasOwnProperty(key)) {
              obj[key] = data[key];
            }
          }
          ['width', 'height', 'thumb', 'type', 'filter'].forEach(function (item) {
            if (item in data) {
              opts[item] = data[item];
            }
          });
          if ('srcset' in data) {
            opts.image = {
              srcset: data.srcset
            };
          }
          opts.$orig = $item;
          if (!obj.type && !obj.src) {
            obj.type = 'inline';
            obj.src = item;
          }
        } else {
          // Assume we have a simple html code, for example:
          // $.envirabox.open( '<div><h1>Hi!</h1></div>' );
          obj = {
            type: 'html',
            src: item + ''
          };
        }

        // Each gallery object has full collection of options
        obj.opts = $.extend(true, {}, self.opts, opts);
        if ($.envirabox.isMobile) {
          obj.opts = $.extend(true, {}, obj.opts, obj.opts.mobile);
        }

        // Step 2 - Make sure we have content type, if not - try to guess
        // ==============================================================
        type = obj.type || obj.opts.type;
        src = obj.src || '';
        if (!type && src) {
          if (src.match(/(^data:image\/[a-z0-9+\/=]*,)|(\.(jp(e|g|eg)|gif|png|bmp|webp|svg|ico)((\?|#).*)?$)/i)) {
            type = 'image';
          } else if (src.match(/\.(pdf)((\?|#).*)?$/i)) {
            type = 'pdf';
          } else if (found = src.match(/\.(mp4|mov|ogv)((\?|#).*)?$/i)) {
            type = 'video';
            if (!obj.opts.videoFormat) {
              obj.opts.videoFormat = 'video/' + (found[1] === 'ogv' ? 'ogg' : found[1]);
            }
          } else if (src.charAt(0) === '#') {
            type = 'inline';
          }
        }
        obj.type = type;

        // Step 3 - Some adjustments
        // =========================
        obj.index = self.group.length;

        // Caption is a "special" option, it can be passed as a method
        if ($.type(obj.opts.caption) === 'function') {
          obj.opts.caption = obj.opts.caption.apply(item, [self, obj]);
        } else if ('caption' in data) {
          obj.opts.caption = data.caption;
        } else if (_typeof(obj.opts) === 'object' && typeof obj.opts.caption === 'string' && obj.opts.caption !== null && Object.keys(obj.opts).length > 0) {
          // used for Envira Links, extra checks for IE11
          obj.opts.caption = enviraEncodeHTMLEntities(obj.opts.caption);
        } else {
          obj.opts.caption = '';
        }

        // Check if $orig and $thumb objects exist
        if (obj.opts.$orig && !obj.opts.$orig.length) {
          delete obj.opts.$orig;
        }
        if (!obj.opts.$thumb && obj.opts.$orig) {
          obj.opts.$thumb = obj.opts.$orig.find('img:first');
        }
        if (obj.opts.$thumb && !obj.opts.$thumb.length) {
          delete obj.opts.$thumb;
        }

        // Caption is a "special" option, it can be passed as a method
        if ($.type(obj.opts.caption) === 'function') {
          obj.opts.caption = obj.opts.caption.apply(item, [self, obj]);
        } else if ('caption' in data) {
          obj.opts.caption = data.caption;
        } else if (_typeof(obj.opts) === 'object' && typeof obj.opts.caption === 'string' && obj.opts.caption !== null && Object.keys(obj.opts).length > 0) {
          // used for Envira Links, extra checks for IE11
          obj.opts.caption = obj.opts.caption;
        } else {
          obj.opts.caption = '';
        }

        // Make sure we have caption as a string
        obj.opts.caption = obj.opts.caption === undefined ? '' : obj.opts.caption + '';

        // Make sure caption isn't null
        if (obj.opts.caption == null) {
          obj.opts.caption = '';
        }
        obj.opts.caption = enviraEncodeHTMLEntities(obj.opts.caption);

        // Caption is a "special" option, it can be passed as a method
        if ($.type(obj.opts.title) === 'function') {
          obj.opts.title = obj.opts.title.apply(item, [self, obj]);
        } else if ('title' in data) {
          obj.opts.title = data.title;
        } else if (_typeof(obj.opts) === 'object' && typeof obj.opts.title === 'string' && obj.opts.title !== null && Object.keys(obj.opts).length > 0) {
          // used for Envira Links, extra checks for IE11
          obj.opts.title = obj.opts.title;
        } else {
          obj.opts.title = '';
        }

        // Make sure we have title as a string
        obj.opts.title = obj.opts.title === undefined ? '' : obj.opts.title + '';

        // Make sure caption isn't null
        if (obj.opts.title == null) {
          obj.opts.title = '';
        }
        obj.opts.title = enviraEncodeHTMLEntities(obj.opts.title);

        // Check if url contains "filter" used to filter the content
        // Example: "ajax.html #something"
        if (type === 'ajax') {
          srcParts = src.split(/\s+/, 2);
          if (srcParts.length > 1) {
            obj.src = srcParts.shift();
            obj.opts.filter = srcParts.shift();
          }
        }
        if (obj.opts.smallBtn !== undefined && obj.opts.smallBtn == true) {
          obj.opts.toolbar = false;
          obj.opts.smallBtn = true;
        }

        // If the type is "pdf", then simply load file into iframe
        if (type === 'pdf') {
          obj.type = 'iframe';
          obj.opts.iframe.preload = false;
        }

        // Hide all buttons and disable interactivity for modal items
        if (obj.opts.modal) {
          obj.opts = $.extend(true, obj.opts, {
            // Remove buttons
            infobar: 0,
            toolbar: 0,
            smallBtn: 0,
            // Disable keyboard navigation
            keyboard: 0,
            // Disable some modules
            slideShow: 0,
            fullScreen: 0,
            thumbs: 0,
            touch: 0,
            // Disable click event handlers
            clickContent: false,
            clickSlide: false,
            clickOutside: false,
            dblclickContent: false,
            dblclickSlide: false,
            dblclickOutside: false
          });
        }

        // Step 4 - Add processed object to group
        // ======================================
        self.group.push(obj);
      });
    },
    // Attach an event handler functions for:
    // - navigation buttons
    // - browser scrolling, resizing;
    // - focusing
    // - keyboard
    // - detect idle
    // ======================================
    addEvents: function addEvents() {
      var self = this;
      self.removeEvents();

      // Make navigation elements clickable
      self.$refs.container.on('click.eb-close', '[data-envirabox-close]', function (e) {
        e.stopPropagation();
        e.preventDefault();
        self.close(e);
      }).on('click.eb-prev touchend.eb-prev', '[data-envirabox-prev]', function (e) {
        e.stopPropagation();
        e.preventDefault();
        self.previous();
      }).on('click.eb-next touchend.eb-next', '[data-envirabox-next]', function (e) {
        e.stopPropagation();
        e.preventDefault();
        self.next();
      });

      // Handle page scrolling and browser resizing
      $W.on('orientationchange.eb resize.eb', function (e) {
        if (e && e.originalEvent && e.originalEvent.type === 'resize') {
          requestAFrame(function () {
            self.update();
          });
        } else {
          self.$refs.stage.hide();
          setTimeout(function () {
            self.$refs.stage.show();
            self.update();
          }, 500);
        }
      });

      // Trap keyboard focus inside of the modal, so the user does not accidentally tab outside of the modal
      // (a.k.a. "escaping the modal")
      $D.on('focusin.eb', function (e) {
        var instance = $.envirabox ? $.envirabox.getInstance() : null;
        if (instance.isClosing || !instance.current || !instance.current.opts.trapFocus || $(e.target).hasClass('envirabox-container') || $(e.target).is(document)) {
          return;
        }
        if (instance && $(e.target).css('position') !== 'fixed' && !instance.$refs.container.has(e.target).length) {
          e.stopPropagation();
          instance.focus();

          // Sometimes page gets scrolled, set it back
          $W.scrollTop(self.scrollTop).scrollLeft(self.scrollLeft);
        }
      });

      // Enable keyboard navigation
      $D.on('keydown.eb', function (e) {
        var current = self.current,
          keycode = e.keyCode || e.which;
        if (!current || !current.opts.keyboard) {
          return;
        }
        if ($(e.target).is('input') || $(e.target).is('textarea')) {
          return;
        }

        // Tab keys
        if (keycode === 9) {
          if (e.shiftKey) {
            self.jumpTo(self.currIndex - 1, 1);
            return;
          } else {
            self.jumpTo(self.currIndex + 1, 1);
            return;
          }
          e.preventDefault();
          return;
        }

        // Backspace and Esc keys
        if (keycode === 8 || keycode === 27) {
          e.preventDefault();
          self.close(e);
          return;
        }

        // Left arrow and Up arrow
        if (keycode === 37 || keycode === 38) {
          e.preventDefault();
          self.previous();
          return;
        }

        // Righ arrow and Down arrow
        if (keycode === 39 || keycode === 40) {
          e.preventDefault();
          self.next();
          return;
        }
        self.trigger('afterKeydown', e, keycode);
      });

      // Hide controls after some inactivity period
      if (self.group[self.currIndex].opts.idleTime) {
        self.idleSecondsCounter = 0;
        $D.on('mousemove.eb-idle mouseenter.eb-idle mouseleave.eb-idle mousedown.eb-idle touchstart.eb-idle touchmove.eb-idle scroll.eb-idle keydown.eb-idle', function () {
          self.idleSecondsCounter = 0;
          if (self.isIdle) {
            self.showControls();
          }
          self.isIdle = false;
        });
        self.idleInterval = window.setInterval(function () {
          self.idleSecondsCounter++;
          if (self.idleSecondsCounter >= self.group[self.currIndex].opts.idleTime) {
            self.isIdle = true;
            self.idleSecondsCounter = 0;
            self.hideControls();
          }
        }, 1000);
      }
    },
    // Remove events added by the core
    // ===============================
    removeEvents: function removeEvents() {
      var self = this;
      $W.off('orientationchange.eb resize.eb');
      $D.off('focusin.eb keydown.eb .eb-idle');
      this.$refs.container.off('.eb-close .eb-prev .eb-next');
      if (self.idleInterval) {
        window.clearInterval(self.idleInterval);
        self.idleInterval = null;
      }
    },
    // Change to previous gallery item
    // ===============================
    previous: function previous(duration) {
      return this.jumpTo(this.currPos - 1, duration);
    },
    // Change to next gallery item
    // ===========================
    next: function next(duration) {
      return this.jumpTo(this.currPos + 1, duration);
    },
    // Switch to selected gallery item
    // ===============================
    jumpTo: function jumpTo(pos, duration, slide) {
      var self = this,
        firstRun,
        loop,
        current,
        previous,
        canvasWidth,
        currentPos,
        transitionProps;
      var groupLen = self.group.length;
      if (self.isSliding || self.isClosing || self.isAnimating && self.firstRun) {
        return;
      }
      pos = parseInt(pos, 10);
      loop = self.current ? self.current.opts.loop : self.opts.loop;
      if (!loop && (pos < 0 || pos >= groupLen)) {
        return false;
      }
      firstRun = self.firstRun = self.firstRun === null;
      if (groupLen < 2 && !firstRun && !!self.isSliding) {
        return;
      }
      previous = self.current;
      self.prevIndex = self.currIndex;
      self.prevPos = self.currPos;

      // Create slides
      current = self.createSlide(pos);
      if (groupLen > 1) {
        if (loop || current.index > 0) {
          self.createSlide(pos - 1);
        }
        if (loop || current.index < groupLen - 1) {
          self.createSlide(pos + 1);
        }
      }
      self.current = current;
      self.currIndex = current.index;
      self.currPos = current.pos;
      self.trigger('beforeShow', firstRun);
      self.updateControls();
      currentPos = $.envirabox.getTranslate(current.$slide);
      current.isMoved = (currentPos.left !== 0 || currentPos.top !== 0) && !current.$slide.hasClass('envirabox-animated');
      current.forcedDuration = undefined;
      if ($.isNumeric(duration)) {
        current.forcedDuration = duration;
      } else {
        duration = current.opts[firstRun ? 'animationDuration' : 'transitionDuration'];
      }
      duration = parseInt(duration, 10);

      // Fresh start - reveal container, current slide and start loading content
      if (firstRun) {
        if (current.opts.animationEffect && duration) {
          self.$refs.container.css('transition-duration', duration + 'ms');
        }
        self.$refs.container.removeClass('envirabox-is-hidden');
        forceRedraw(self.$refs.container);
        self.$refs.container.addClass('envirabox-is-open');

        // Make first slide visible (to display loading icon, if needed)
        current.$slide.addClass('envirabox-slide--current');
        self.loadSlide(current);
        self.preload();
        return;
      }

      // Clean up
      $.each(self.slides, function (index, slide) {
        $.envirabox.stop(slide.$slide);
      });

      // Make current that slide is visible even if content is still loading
      current.$slide.removeClass('envirabox-slide--next envirabox-slide--previous').addClass('envirabox-slide--current');

      // If slides have been dragged, animate them to correct position
      if (current.isMoved) {
        canvasWidth = Math.round(current.$slide.width());
        $.each(self.slides, function (index, slide) {
          var pos = slide.pos - current.pos;
          $.envirabox.animate(slide.$slide, {
            top: 0,
            left: pos * canvasWidth + pos * slide.opts.gutter
          }, duration, function () {
            slide.$slide.removeAttr('style').removeClass('envirabox-slide--next envirabox-slide--previous');
            if (slide.pos === self.currPos) {
              current.isMoved = false;
              self.complete();
            }
          });
        });
      } else {
        self.$refs.stage.children().removeAttr('style');
      }

      // Start transition that reveals current content
      // or wait when it will be loaded
      if (current.isLoaded) {
        self.revealContent(current);
      } else {
        self.loadSlide(current);
      }
      self.preload();
      if (previous.pos === current.pos) {
        return;
      }

      // Handle previous slide
      // =====================
      transitionProps = 'envirabox-slide--' + (previous.pos > current.pos ? 'next' : 'previous');
      previous.$slide.removeClass('envirabox-slide--complete envirabox-slide--current envirabox-slide--next envirabox-slide--previous');
      previous.isComplete = false;
      if (!duration || !current.isMoved && !current.opts.transitionEffect) {
        return;
      }
      if (current.isMoved) {
        previous.$slide.addClass(transitionProps);
      } else {
        transitionProps = 'envirabox-animated ' + transitionProps + ' envirabox-fx-' + current.opts.transitionEffect;
        $.envirabox.animate(previous.$slide, transitionProps, duration, function () {
          previous.$slide.removeClass(transitionProps).removeAttr('style');
        });
      }

      // self.trigger('afterShow');
    },

    // Create new "slide" element
    // These are gallery items  that are actually added to DOM
    // =======================================================
    createSlide: function createSlide(pos) {
      var self = this;
      var $slide;
      var index;
      index = pos % self.group.length;
      index = index < 0 ? self.group.length + index : index;
      if (!self.slides[pos] && self.group[index]) {
        $slide = $('<div class="envirabox-slide"></div>').appendTo(self.$refs.stage);
        self.slides[pos] = $.extend(true, {}, self.group[index], {
          pos: pos,
          $slide: $slide,
          isLoaded: false
        });
        self.updateSlide(self.slides[pos]);
      }
      return self.slides[pos];
    },
    // Scale image to the actual size of the image
    // ===========================================
    scaleToActual: function scaleToActual(x, y, duration) {
      var self = this,
        current = self.current,
        $what = current.$content,
        imgPos,
        posX,
        posY,
        scaleX,
        scaleY,
        canvasWidth = parseInt(current.$slide.width(), 10),
        canvasHeight = parseInt(current.$slide.height(), 10),
        newImgWidth = current.width,
        newImgHeight = current.height;
      if (!(current.type == 'image' && !current.hasError) || !$what || self.isAnimating) {
        return;
      }
      $.envirabox.stop($what);
      self.isAnimating = true;
      x = x === undefined ? canvasWidth * 0.5 : x;
      y = y === undefined ? canvasHeight * 0.5 : y;
      imgPos = $.envirabox.getTranslate($what);
      scaleX = newImgWidth / imgPos.width;
      scaleY = newImgHeight / imgPos.height;

      // Get center position for original image
      posX = canvasWidth * 0.5 - newImgWidth * 0.5;
      posY = canvasHeight * 0.5 - newImgHeight * 0.5;

      // Make sure image does not move away from edges
      if (newImgWidth > canvasWidth) {
        posX = imgPos.left * scaleX - (x * scaleX - x);
        if (posX > 0) {
          posX = 0;
        }
        if (posX < canvasWidth - newImgWidth) {
          posX = canvasWidth - newImgWidth;
        }
      }
      if (newImgHeight > canvasHeight) {
        posY = imgPos.top * scaleY - (y * scaleY - y);
        if (posY > 0) {
          posY = 0;
        }
        if (posY < canvasHeight - newImgHeight) {
          posY = canvasHeight - newImgHeight;
        }
      }
      self.updateCursor(newImgWidth, newImgHeight);
      $.envirabox.animate($what, {
        top: posY,
        left: posX,
        scaleX: scaleX,
        scaleY: scaleY
      }, duration || 330, function () {
        self.isAnimating = false;
      });

      // Stop slideshow
      if (self.SlideShow && self.SlideShow.isActive) {
        self.SlideShow.stop();
      }
    },
    // Scale image to fit inside parent element
    // ========================================
    scaleToFit: function scaleToFit(duration) {
      var self = this,
        current = self.current,
        $what = current.$content,
        end;
      if (!(current.type == 'image' && !current.hasError) || !$what || self.isAnimating) {
        return;
      }
      $.envirabox.stop($what);
      self.isAnimating = true;
      end = self.getFitPos(current);
      self.updateCursor(end.width, end.height);
      $.envirabox.animate($what, {
        top: end.top,
        left: end.left,
        scaleX: end.width / $what.width(),
        scaleY: end.height / $what.height()
      }, duration || 330, function () {
        self.isAnimating = false;
      });
    },
    // Calculate image size to fit inside viewport
    // ===========================================
    getFitPos: function getFitPos(slide) {
      var self = this,
        $what = slide.$content,
        imgWidth = slide.width,
        imgHeight = slide.height,
        query = '(-webkit-min-device-pixel-ratio: 2), (min-device-pixel-ratio: 2), (min-resolution: 192dpi)',
        isChrome = /Chrome/.test(navigator.userAgent) && /Google Inc/.test(navigator.vendor);

      // only do this if the screen is retina.
      if (isChrome && matchMedia(query).matches || window.devicePixelRatio === 2) {
        imgWidth = imgWidth * 2;
        imgHeight = imgHeight * 2;
      }
      var margin = slide.opts.margin,
        canvasWidth,
        canvasHeight,
        minRatio,
        width,
        height;
      if (!$what || !$what.length || !imgWidth && !imgHeight) {
        return false;
      }

      // Convert "margin to CSS style: [ top, right, bottom, left ]
      if ($.type(margin) === 'number') {
        margin = [margin, margin];
      }
      if (margin.length === 2) {
        margin = [margin[0], margin[1], margin[0], margin[1]];
      }
      if ($W.width() < 800) {
        margin = [0, 30, 0, 30];
      }
      if ($W.height() < 800) {
        margin = [12, 0, 12, 0];
      }

      // We can not use $slide width here, because it can have different diemensions while in transiton
      canvasWidth = Math.abs(parseInt(self.$refs.stage.width(), 10) - (margin[1] + margin[3]));
      canvasHeight = Math.abs(parseInt(self.$refs.stage.height(), 10) - (margin[0] + margin[2]));
      minRatio = Math.min(1, canvasWidth / imgWidth, canvasHeight / imgHeight);
      width = Math.floor(minRatio * imgWidth);
      height = Math.floor(minRatio * imgHeight);

      // Use floor rounding to make sure it really fits
      return {
        top: Math.floor((canvasHeight - height) * 0.5) + margin[0],
        left: Math.floor((canvasWidth - width) * 0.5) + margin[3],
        width: width,
        height: height
      };
    },
    // Update position and content of all slides
    // =========================================
    update: function update() {
      var self = this;
      $.each(self.slides, function (key, slide) {
        self.updateSlide(slide);
      });
    },
    // Update slide position and scale content to fit
    // ==============================================
    updateSlide: function updateSlide(slide) {
      var self = this,
        $what = slide.$content;
      if ($what && (slide.width || slide.height)) {
        $.envirabox.stop($what);
        $.envirabox.setTranslate($what, self.getFitPos(slide));
        if (slide.pos === self.currPos) {
          self.updateCursor();
        }
      }
      slide.$slide.trigger('refresh');
      self.trigger('onUpdate', slide);
    },
    // Update cursor style depending if content can be zoomed
    // ======================================================
    updateCursor: function updateCursor(nextWidth, nextHeight) {
      var self = this,
        isScaledDown,
        $container = self.$refs.container.removeClass('envirabox-is-zoomable envirabox-can-zoomIn envirabox-can-drag envirabox-can-zoomOut');
      if (!self.current || self.isClosing) {
        return;
      }
      if (self.isZoomable()) {
        $container.addClass('envirabox-is-zoomable');
        if (nextWidth !== undefined && nextHeight !== undefined) {
          isScaledDown = nextWidth < self.current.width && nextHeight < self.current.height;
        } else {
          isScaledDown = self.isScaledDown();
        }
        if (isScaledDown) {
          // If image is scaled down, then, obviously, it can be zoomed to full size
          $container.addClass('envirabox-can-zoomIn');
        } else {
          if (self.current.opts.touch) {
            // If image size ir largen than available available and touch module is not disable,
            // then user can do panning
            $container.addClass('envirabox-can-drag');
          } else {
            $container.addClass('envirabox-can-zoomOut');
          }
        }
      } else if (self.current.opts.touch) {
        $container.addClass('envirabox-can-drag');
      }
    },
    // Check if current slide is zoomable
    // ==================================
    isZoomable: function isZoomable() {
      var self = this,
        current = self.current,
        fitPos;
      if (!current || self.isClosing) {
        return;
      }

      // Assume that slide is zoomable if
      // - image is loaded successfuly
      // - click action is "zoom"
      // - actual size of the image is smaller than available area
      if (current.type === 'image' && current.isLoaded && !current.hasError && (current.opts.clickContent === 'zoom' || $.isFunction(current.opts.clickContent) && current.opts.clickContent(current) === 'zoom')) {
        fitPos = self.getFitPos(current);
        if (current.width > fitPos.width || current.height > fitPos.height) {
          return true;
        }
      }
      return false;
    },
    // Check if current image dimensions are smaller than actual
    // =========================================================
    isScaledDown: function isScaledDown() {
      var self = this,
        current = self.current,
        $what = current.$content,
        rez = false;
      if ($what) {
        rez = $.envirabox.getTranslate($what);
        rez = rez.width < current.width || rez.height < current.height;
      }
      return rez;
    },
    // Check if image dimensions exceed parent element
    // ===============================================
    canPan: function canPan() {
      var self = this,
        current = self.current,
        $what = current.$content,
        rez = false;
      if ($what) {
        rez = self.getFitPos(current);
        rez = Math.abs($what.width() - rez.width) > 1 || Math.abs($what.height() - rez.height) > 1;
      }
      return rez;
    },
    // Load content into the slide
    // ===========================
    loadSlide: function loadSlide(slide) {
      var self = this,
        type,
        $slide,
        ajaxLoad;
      if (slide.isLoading) {
        return;
      }
      if (slide.isLoaded) {
        return;
      }
      slide.isLoading = true;
      self.trigger('beforeLoad', slide);
      type = slide.type;
      $slide = slide.$slide;
      $slide.off('refresh').trigger('onReset').addClass('envirabox-slide--' + (type || 'unknown')).addClass(slide.opts.slideClass);

      // Create content depending on the type
      switch (type) {
        case 'image':
          self.setImage(slide);
          break;
        case 'video':
          self.setVideo(slide);
          break;
        case 'iframe':
          self.setIframe(slide);
          break;
        case 'genericDiv':
          self.setGenericDiv(slide);
          break;
        case 'html':
          self.setContent(slide, slide.src || slide.content);
          break;
        case 'inline':
          if ($(slide.src).length) {
            self.setContent(slide, $(slide.src));
          } else {
            self.setError(slide);
          }
          break;
        case 'ajax':
          self.showLoading(slide);
          ajaxLoad = $.ajax($.extend({}, slide.opts.ajax.settings, {
            url: slide.src,
            success: function success(data, textStatus) {
              if (textStatus === 'success') {
                self.setContent(slide, data);
              }
            },
            error: function error(jqXHR, textStatus) {
              if (jqXHR && textStatus !== 'abort') {
                self.setError(slide);
              }
            }
          }));
          $slide.one('onReset', function () {
            ajaxLoad.abort();
          });
          break;
        default:
          self.setError(slide);
          break;
      }
      return true;
    },
    setVideo: function setVideo(slide) {
      var self = this;
      if (self.isClosing) {
        return;
      }
      self.hideLoading(slide);
      slide.$slide.empty();
      var style_width = false,
        style_height = false,
        videoWidthHeight = '',
        videoControls = 'controls' /* controls by default */,
        videoControlsList = '',
        videoClasses = '';

      // Setup specific CSS classes that might help with customizing video, especially for self hosted
      if (slide.opts.videoPlayPause !== false) {
        videoClasses = videoClasses + 'videos_play_pause ';
      }
      if (slide.opts.videoProgressBar !== false) {
        videoClasses = videoClasses + 'videos_progress ';
      }
      if (slide.opts.videoPlaybackTime !== false) {
        videoClasses = videoClasses + 'videos_playback_time ';
      }
      if (slide.opts.videoVideoLength !== false) {
        videoClasses = videoClasses + 'videos_video_length ';
      }
      if (slide.opts.videoVolumeControls !== false) {
        videoClasses = videoClasses + 'videos_volume_controls ';
      }
      if (slide.opts.videoControlBar !== false) {
        videoClasses = videoClasses + 'videos_controls ';
      } else {
        /* if controls are off, we might need to display the video inline if there is an autoplay, and likely there would be if controls are off - see GH #3682 */
        if (slide.opts.videoAutoPlay !== undefined && slide.opts.videoAutoPlay !== false) {
          videoControls = 'autoplay playinline';
        } else {
          videoControls = '';
        }
        videoControlsList = videoControlsList + 'nodownload nofullscreen noremoteplayback ';
      }
      if (slide.opts.videoFullscreen !== false) {
        videoClasses = videoClasses + 'videos_fullscreen ';
      }
      if (slide.opts.videoDownload !== false) {
        videoClasses = videoClasses + 'videos_download ';
      } else {
        videoControlsList = videoControlsList + 'nodownload ';
      }
      if (slide.videoWidth > 0) {
        style_width = 'max-width:' + slide.videoWidth + 'px;';
      }
      if (slide.videoHeight > 0) {
        style_height = 'max-height:' + slide.videoHeight + 'px;';
      }
      if (style_width || style_height) {
        videoWidthHeight = 'style="' + style_width + style_height + '"';
      }
      if (slide.opts.arrows !== 0 && slide.opts.arrow_position == 'inside') {
        // This will be wrapper containing the video
        slide.$content = $('<div class="envirabox-content video ' + videoClasses + '" ' + videoWidthHeight + '"><div class="envirabox-navigation-inside"><a data-envirabox-prev title="prev" class="envirabox-arrow envirabox-arrow--left envirabox-nav envirabox-prev" href="#"><span></span></a><a data-envirabox-next title="next" class="envirabox-arrow envirabox-arrow--right envirabox-nav envirabox-next" href="#"><span></span></a></div>').appendTo(slide.$slide);
      } else {
        // This will be wrapper containing the video
        slide.$content = $('<div class="envirabox-content ' + videoClasses + '" ' + videoWidthHeight + '></div>').appendTo(slide.$slide);
      }
      if (slide.opts.smallBtn === true) {
        slide.$content.prepend(self.translate(slide, slide.opts.btnTpl.smallBtn));
      }
      if (slide.opts.insideCap === true) {
        var caption = slide.caption !== undefined ? slide.caption : '',
          title = slide.title !== undefined ? slide.title : '',
          position = slide.opts.capPosition ? slide.opts.capPosition : '',
          capTitleShow = slide.opts.capTitleShow && slide.opts.capTitleShow !== '0' ? slide.opts.capTitleShow : false,
          itemID = slide.enviraItemId ? slide.enviraItemId : '',
          output = capTitleShow == 'caption' ? '<div class="envirabox-caption ' + 'envirabox-caption-item-id-' + itemID + '">' + caption + '</div>' : false;
        output = capTitleShow == 'title_caption' ? '<div class="envirabox-caption ' + 'envirabox-caption-item-id-' + itemID + '">' + title + ' ' + caption + '</div>' : output;
        output = capTitleShow == 'title' ? '<div class="envirabox-title ' + 'envirabox-title-item-id-' + itemID + '">' + title + '</div>' : output;
        if (output !== false && output !== undefined && output.length > 0) {
          slide.$content.prepend('<div class="envirabox-caption-wrap ' + position + '">' + output + '</div>');
        }
      }
      if (videoControls != '') {
        var posterImage = slide.videoPlaceholder ? slide.videoPlaceholder : slide.thumb,
          // use default image if nothing available?
          playsInline = slide.videoPlaysInline !== undefined ? slide.videoPlaysInline : 'playsinline';
        videoControls = videoControls + ' poster="' + posterImage + '" ' + playsInline + ' controlsList="' + videoControlsList + '"';
      }
      var video_source = slide.src !== undefined ? slide.src : slide.link,
        video = $('<div class="envirabox-video-container"><video class="envirabox-video-player" ' + videoControls + '>' + '<source src="' + video_source + '" type="video/mp4">' + "Your broswser doesn't support HTML5 video" + '</video></div>').appendTo(slide.$content);
      this.afterLoad(slide);
    },
    // Use thumbnail image, if possible
    // ================================
    setImage: function setImage(slide) {
      var self = this,
        srcset = slide.opts.image.srcset,
        found,
        temp,
        pxRatio,
        windowWidth;

      // If we have "srcset", then we need to find matching "src" value.
      // This is necessary, because when you set an src attribute, the browser will preload the image
      // before any javascript or even CSS is applied.
      if (srcset) {
        pxRatio = window.devicePixelRatio || 1;
        windowWidth = window.innerWidth * pxRatio;
        temp = srcset.split(',').map(function (el) {
          var ret = {};
          el.trim().split(/\s+/).forEach(function (el, i) {
            var value = parseInt(el.substring(0, el.length - 1), 10);
            if (i === 0) {
              return ret.url = el;
            }
            if (value) {
              ret.value = value;
              ret.postfix = el[el.length - 1];
            }
          });
          return ret;
        });

        // Sort by value
        temp.sort(function (a, b) {
          return a.value - b.value;
        });

        // Ok, now we have an array of all srcset values
        for (var j = 0; j < temp.length; j++) {
          var el = temp[j];
          if (el.postfix === 'w' && el.value >= windowWidth || el.postfix === 'x' && el.value >= pxRatio) {
            found = el;
            break;
          }
        }

        // If not found, take the last one
        if (!found && temp.length) {
          found = temp[temp.length - 1];
        }
        if (found) {
          slide.src = found.url;

          // If we have default width/height values, we can calculate height for matching source
          if (slide.width && slide.height && found.postfix == 'w') {
            slide.height = slide.width / slide.height * found.value;
            slide.width = found.value;
          }
        }
      }
      if (slide.opts.arrows !== 0 && slide.opts.arrow_position == 'inside') {
        // This will be wrapper containing both ghost and actual image
        slide.$content = $('<div class="envirabox-image-wrap"><div class="envirabox-navigation-inside"><a data-envirabox-prev title="prev" class="envirabox-arrow envirabox-arrow--left envirabox-nav envirabox-prev" href="#"><span></span></a><a data-envirabox-next title="next" class="envirabox-arrow envirabox-arrow--right envirabox-nav envirabox-next" href="#"><span></span></a></div>').addClass('envirabox-is-hidden').appendTo(slide.$slide);
      } else {
        // This will be wrapper containing both ghost and actual image
        slide.$content = $('<div class="envirabox-image-wrap"></div>').addClass('envirabox-is-hidden').appendTo(slide.$slide);
      }
      if (slide.opts.smallBtn === true) {
        slide.$content.prepend(self.translate(slide, slide.opts.btnTpl.smallBtn));
      }
      if (slide.opts.insideCap === true) {
        var caption = slide.caption !== undefined ? slide.caption : '',
          title = slide.title !== undefined ? slide.title : '',
          position = slide.opts.capPosition ? slide.opts.capPosition : '',
          capTitleShow = slide.opts.capTitleShow && slide.opts.capTitleShow !== '0' && slide.opts.capTitleShow !== false && slide.opts.capTitleShow !== 'false' ? slide.opts.capTitleShow : false,
          itemID = slide.enviraItemId ? slide.enviraItemId : '',
          output = capTitleShow == 'caption' ? '<div class="envirabox-caption ' + 'envirabox-caption-item-id-' + itemID + '">' + caption + '</div>' : false;
        output = capTitleShow == 'title_caption' ? '<div class="envirabox-caption ' + 'envirabox-caption-item-id-' + itemID + '">' + title + ' ' + caption + '</div>' : output;
        output = capTitleShow == 'title' ? '<div class="envirabox-title ' + 'envirabox-title-item-id-' + itemID + '">' + title + '</div>' : output;
        if (output !== false && output !== undefined && output.length > 0) {
          slide.$content.prepend('<div class="envirabox-caption-wrap ' + position + '">' + output + '</div>');
        }
      }
      // If we have a thumbnail, we can display it while actual image is loading
      // Users will not stare at black screen and actual image will appear gradually
      if (slide.opts.preload !== false && slide.opts.width && slide.opts.height && (slide.opts.thumb || slide.opts.$thumb)) {
        slide.width = slide.opts.width;
        slide.height = slide.opts.height;
        slide.$ghost = $('<img />').one('error', function () {
          $(this).remove();
          slide.$ghost = null;
          self.setBigImage(slide);
        }).one('load', function () {
          self.afterLoad(slide);
          self.setBigImage(slide);
        }).addClass('envirabox-image').appendTo(slide.$content).attr('src', slide.opts.thumb || slide.opts.$thumb.attr('src'));
      } else {
        self.setBigImage(slide);
      }
    },
    // Create full-size image
    // ======================
    setBigImage: function setBigImage(slide) {
      var self = this,
        $img = $('<img />');
      slide.$image = $img.one('error', function () {
        self.setError(slide);
      }).one('load', function () {
        // Clear timeout that checks if loading icon needs to be displayed
        clearTimeout(slide.timouts);
        slide.timouts = null;
        if (self.isClosing) {
          return;
        }
        slide.width = this.naturalWidth;
        slide.height = this.naturalHeight;
        if (slide.opts.image.srcset) {
          $img.attr('sizes', '100vw').attr('srcset', slide.opts.image.srcset);
        }
        self.hideLoading(slide);
        if (slide.$ghost) {
          slide.timouts = setTimeout(function () {
            slide.timouts = null;
            slide.$ghost.hide();
          }, Math.min(300, Math.max(1000, slide.height / 1600)));
        } else {
          self.afterLoad(slide);
        }
      }).addClass('envirabox-image').attr('src', slide.src).appendTo(slide.$content);
      var query = '(-webkit-min-device-pixel-ratio: 2), (min-device-pixel-ratio: 2), (min-resolution: 192dpi)';

      // only add 2x if the screen is retina.
      if (matchMedia(query).matches && slide.enviraRetina !== undefined && slide.enviraRetina !== false && slide.enviraRetina !== '') {
        slide.$image.attr('srcset', slide.src + ' 1x, ' + slide.enviraRetina + ' 2x');
        // slide.$image.attr('sizes', '(min-width: 1536px) 1030px, 100vw');
      } else if (slide.src !== undefined && slide.src !== false) {
        slide.$image.attr('srcset', slide.src + ' 1x');
      }
      if (($img[0].complete || $img[0].readyState == 'complete') && $img[0].naturalWidth && $img[0].naturalHeight) {
        $img.trigger('load');
      } else if ($img[0].error) {
        $img.trigger('error');
      } else {
        slide.timouts = setTimeout(function () {
          if (!$img[0].complete && !slide.hasError) {
            self.showLoading(slide);
          }
        }, 100);
      }
    },
    frameWidth: null,
    frameHeight: null,
    // Create iframe wrapper, iframe and bindings
    // ==========================================
    setIframe: function setIframe(slide) {
      var self = this,
        opts = slide.opts.iframe,
        $slide = slide.$slide,
        $iframe,
        videoWidthHeight = '',
        /* videoWidthHeight = 'style="width: 1351px; height: 759.938px;"', */
        css_width = false,
        css_height = false;
      if (slide.opts.arrows !== 0 && slide.opts.arrow_position == 'inside') {
        // This will be wrapper containing both ghost and actual image
        slide.$content = $('<div class="envirabox-content' + (opts.preload ? ' envirabox-is-hidden' : ' ') + ' provider-' + opts.provider + '" ' + videoWidthHeight + '><div class="envirabox-navigation-inside"><a data-envirabox-prev title="prev" class="envirabox-arrow envirabox-arrow--left envirabox-nav envirabox-prev" href="#"><span></span></a><a data-envirabox-next title="next" class="envirabox-arrow envirabox-arrow--right envirabox-nav envirabox-next" href="#"><span></span></a></div>').addClass('envirabox-hidden').addClass('envirabox-iframe-hidden').css('width', '640px').css('height', '360px').appendTo(slide.$slide);
      } else if (opts.provider !== undefined) {
        // this should be a video
        slide.$content = $('<div class="envirabox-content' + (opts.preload ? ' envirabox-is-hidden' : ' ') + ' provider-' + opts.provider + '" ' + videoWidthHeight + '></div>').addClass('envirabox-hidden').addClass('envirabox-iframe-hidden').css('width', '640px').css('height', '360px').appendTo($slide);
      } else {
        // anything not defined, such as a pdf
        slide.$content = $('<div class="envirabox-content' + (opts.preload ? ' envirabox-is-hidden' : ' ') + ' provider-' + opts.provider + '" ' + videoWidthHeight + '></div>').css('width', '90%').css('height', '90%').appendTo($slide);
      }
      $iframe = $(opts.tpl.replace(/\{rnd\}/g, new Date().getTime()).replace(/\{additionalClasses\}/g, slide.contentProvider)).attr(opts.attr).appendTo(slide.$content).css('width', css_width).css('height', css_height);
      if (opts.preload) {
        self.showLoading(slide);

        // Unfortunately, it is not always possible to determine if iframe is successfully loaded
        // (due to browser security policy)
        $iframe.on('load.eb error.eb', function (e) {
          this.isReady = 1;
          slide.$slide.trigger('refresh');
          self.afterLoad(slide);
        });
        var $contents = false,
          $body = false;
        try {
          $contents = $iframe.contents();
          $body = $contents.find('body');
        } catch (ignore) {}

        // Calculate contnet dimensions if it is accessible
        if ($body && $body.length && $body.children().length) {
          $content.css({
            width: '',
            height: ''
          });
          if (self.frameWidth === null) {
            self.frameWidth = Math.ceil(Math.max($body[0].clientWidth, $body.outerWidth(true)));
          }
          if (self.frameWidth) {
            $content.width(self.frameWidth);
          }
          if (self.frameHeight === null) {
            self.frameHeight = Math.ceil(Math.max($body[0].clientHeight, $body.outerHeight(true)));
          }
          if (self.frameHeight) {
            $content.height(self.frameHeight);
          }
          $content.removeClass('envirabox-hidden');
        }
      } else {
        this.afterLoad(slide);
      }
      if (slide.opts.insideCap === true) {
        var caption = slide.caption !== undefined ? slide.caption : '',
          title = slide.title !== undefined ? slide.title : '',
          position = slide.opts.capPosition ? slide.opts.capPosition : '',
          capTitleShow = slide.opts.capTitleShow && slide.opts.capTitleShow !== '0' ? slide.opts.capTitleShow : false,
          itemID = slide.enviraItemId ? slide.enviraItemId : '',
          output = capTitleShow == 'caption' ? '<div class="envirabox-caption ' + 'envirabox-caption-item-id-' + itemID + '">' + caption + '</div>' : false;
        output = capTitleShow == 'title_caption' ? '<div class="envirabox-caption ' + 'envirabox-caption-item-id-' + itemID + '">' + title + ' ' + caption + '</div>' : output;
        output = capTitleShow == 'title' ? '<div class="envirabox-title ' + 'envirabox-title-item-id-' + itemID + '">' + title + '</div>' : output;
        if (output !== false && output !== undefined && output.length > 0) {
          slide.$content.prepend('<div class="envirabox-caption-wrap ' + position + '">' + output + '</div>');
        }
      }
      $iframe.attr('src', slide.src);
      if (slide.opts.smallBtn === true) {
        slide.$content.prepend(self.translate(slide, slide.opts.btnTpl.smallBtn));
      }

      // Remove iframe if closing or changing gallery item
      $slide.one('onReset', function () {
        // This helps IE not to throw errors when closing
        try {
          $(this).find('iframe').hide().attr('src', '//about:blank');
        } catch (ignore) {}
        $(this).empty();
        slide.isLoaded = false;
      });
    },
    setGenericDiv: function setGenericDiv(slide) {
      var self = this,
        opts = slide.opts.genericDiv,
        $slide = slide.$slide,
        $genericDiv,
        videoWidthHeight = '',
        /* videoWidthHeight = 'style="width: 1351px; height: 759.938px;"', */
        css_width = '640px',
        css_height = '360px';
      if (slide.opts.arrows !== 0 && slide.opts.arrow_position == 'inside') {
        // This will be wrapper containing both ghost and actual image
        slide.$content = $('<div class="envirabox-content' + (opts.preload ? ' envirabox-is-hidden' : ' ') + ' provider-' + opts.provider + '" ' + videoWidthHeight + '><div class="envirabox-navigation-inside"><a data-envirabox-prev title="prev" class="envirabox-arrow envirabox-arrow--left envirabox-nav envirabox-prev" href="#"><span></span></a><a data-envirabox-next title="next" class="envirabox-arrow envirabox-arrow--right envirabox-nav envirabox-next" href="#"><span></span></a></div>').addClass('envirabox-hidden').css('width', css_width).css('height', css_height).appendTo(slide.$slide);
      } else {
        if (opts.provider == 'facebook') {
          css_width = css_height = 'auto';
        }
        slide.$content = $('<div class="envirabox-content' + (opts.preload ? ' envirabox-is-hidden' : ' ') + ' provider-' + opts.provider + '" ' + videoWidthHeight + '></div>').addClass('envirabox-hidden').css('width', css_width).css('height', css_height).appendTo($slide);
      }
      $genericDiv = $(opts.tpl.replace(/\{rnd\}/g, new Date().getTime()).replace(/\{additionalClasses\}/g, slide.contentProvider)).attr(opts.attr).attr('data-href', slide.src).appendTo(slide.$content).css('width', css_width).css('height', css_height);
      this.afterLoad(slide);
      if (slide.opts.insideCap === true) {
        var caption = slide.caption !== undefined ? slide.caption : '',
          title = slide.title !== undefined ? slide.title : '',
          position = slide.opts.capPosition ? slide.opts.capPosition : '',
          capTitleShow = slide.opts.capTitleShow && slide.opts.capTitleShow !== '0' ? slide.opts.capTitleShow : false,
          itemID = slide.enviraItemId ? slide.enviraItemId : '',
          output = capTitleShow == 'caption' ? '<div class="envirabox-caption ' + 'envirabox-caption-item-id-' + itemID + '">' + caption + '</div>' : false;
        output = capTitleShow == 'title_caption' ? '<div class="envirabox-caption ' + 'envirabox-caption-item-id-' + itemID + '">' + title + ' ' + caption + '</div>' : output;
        output = capTitleShow == 'title' ? '<div class="envirabox-title ' + 'envirabox-title-item-id-' + itemID + '">' + title + '</div>' : output;
        if (output !== false && output !== undefined && output.length > 0) {
          slide.$content.prepend('<div class="envirabox-caption-wrap ' + position + '">' + output + '</div>');
        }
      }
      // $genericDiv.attr( 'src', slide.src );
      if (slide.opts.smallBtn === true) {
        slide.$content.prepend(self.translate(slide, slide.opts.btnTpl.smallBtn));
      }

      // Remove genericDiv if closing or changing gallery item
      $slide.one('onReset', function () {
        // This helps IE not to throw errors when closing
        try {
          $(this).find('genericDiv').hide().attr('src', '//about:blank');
        } catch (ignore) {}
        $(this).empty();
        slide.isLoaded = false;
      });
    },
    // Wrap and append content to the slide
    // ======================================
    setContent: function setContent(slide, content) {
      var self = this;
      if (self.isClosing) {
        return;
      }
      self.hideLoading(slide);
      slide.$slide.empty();
      if (isQuery(content) && content.parent().length) {
        // If content is a jQuery object, then it will be moved to the slide.
        // The placeholder is created so we will know where to put it back.
        // If user is navigating gallery fast, then the content might be already inside envirabox
        // =====================================================================================
        // Make sure content is not already moved to envirabox
        content.parent('.envirabox-slide--inline').trigger('onReset');

        // Create temporary element marking original place of the content
        slide.$placeholder = $('<div></div>').hide().insertAfter(content);

        // Make sure content is visible
        content.css('display', 'inline-block');
      } else if (!slide.hasError) {
        // If content is just a plain text, try to convert it to html
        if ($.type(content) === 'string') {
          content = $('<div>').append($.trim(content)).contents();

          // If we have text node, then add wrapping element to make vertical alignment work
          if (content[0].nodeType === 3) {
            content = $('<div>').html(content);
          }
        }

        // If "filter" option is provided, then filter content
        if (slide.opts.filter) {
          content = $('<div>').html(content).find(slide.opts.filter);
        }
      }
      slide.$slide.one('onReset', function () {
        // Put content back
        if (slide.$placeholder) {
          slide.$placeholder.after(content.hide()).remove();
          slide.$placeholder = null;
        }

        // Remove custom close button
        if (slide.$smallBtn) {
          slide.$smallBtn.remove();
          slide.$smallBtn = null;
        }

        // Remove content and mark slide as not loaded
        if (!slide.hasError) {
          $(this).empty();
          slide.isLoaded = false;
        }
      });
      slide.$content = $(content).appendTo(slide.$slide);
      if (slide.opts.smallBtn && !slide.$smallBtn) {
        slide.$smallBtn = $(self.translate(slide, slide.opts.btnTpl.smallBtn)).appendTo(slide.$content.filter('div').first());
      }
      this.afterLoad(slide);
    },
    // Display error message
    // =====================
    setError: function setError(slide) {
      slide.hasError = true;
      slide.$slide.removeClass('envirabox-slide--' + slide.type);
      this.setContent(slide, this.translate(slide, slide.opts.errorTpl));
    },
    // Show loading icon inside the slide
    // ==================================
    showLoading: function showLoading(slide) {
      var self = this;
      slide = slide || self.current;
      if (slide && !slide.$spinner) {
        slide.$spinner = $(self.opts.spinnerTpl).appendTo(slide.$slide);
      }
    },
    // Remove loading icon from the slide
    // ==================================
    hideLoading: function hideLoading(slide) {
      var self = this;
      slide = slide || self.current;
      if (slide && slide.$spinner) {
        slide.$spinner.remove();
        delete slide.$spinner;
      }
    },
    // Adjustments after slide content has been loaded
    // ===============================================
    afterLoad: function afterLoad(slide) {
      var self = this;
      if (self.isClosing) {
        return;
      }
      slide.isLoading = false;
      slide.isLoaded = true;
      self.trigger('afterLoad', slide);
      self.hideLoading(slide);
      if (slide.opts.protect && slide.$content && !slide.hasError) {
        // Disable right click
        slide.$content.on('contextmenu.eb', function (e) {
          if (e.button == 2) {
            e.preventDefault();
          }
          return true;
        });

        // Add fake element on top of the image
        // This makes a bit harder for user to select image
        if (slide.type === 'image') {
          $('<div class="envirabox-spaceball"></div>').appendTo(slide.$content);
        }
      }
      self.revealContent(slide);
    },
    // Make content visible
    // This method is called right after content has been loaded or
    // user navigates gallery and transition should start
    // ============================================================
    revealContent: function revealContent(slide) {
      var self = this;
      var $slide = slide.$slide;
      var effect,
        effectClassName,
        duration,
        opacity,
        end,
        start = false;
      effect = slide.opts[self.firstRun ? 'animationEffect' : 'transitionEffect'];
      duration = slide.opts[self.firstRun ? 'animationDuration' : 'transitionDuration'];
      duration = parseInt(slide.forcedDuration === undefined ? duration : slide.forcedDuration, 10);
      if (slide.isMoved || slide.pos !== self.currPos || !duration) {
        effect = false;
      }

      // Check if can zoom
      if (effect === 'zoom' && !(slide.pos === self.currPos && duration && slide.type === 'image' && !slide.hasError && (start = self.getThumbPos(slide)))) {
        effect = 'fade';
      }

      // Zoom animation
      // ==============
      if (effect === 'zoom') {
        end = self.getFitPos(slide);
        end.scaleX = end.width / start.width;
        end.scaleY = end.height / start.height;
        delete end.width;
        delete end.height;

        // Check if we need to animate opacity
        opacity = slide.opts.zoomOpacity;
        if (opacity == 'auto') {
          opacity = Math.abs(slide.width / slide.height - start.width / start.height) > 0.1;
        }
        if (opacity) {
          start.opacity = 0.1;
          end.opacity = 1;
        }

        // Draw image at start position
        $.envirabox.setTranslate(slide.$content.removeClass('envirabox-is-hidden'), start);
        forceRedraw(slide.$content);

        // Start animation
        $.envirabox.animate(slide.$content, end, duration, function () {
          self.complete();
        });
        return;
      }
      self.updateSlide(slide);

      // Simply show content
      // ===================
      if (!effect) {
        forceRedraw($slide);
        slide.$content.removeClass('envirabox-is-hidden');
        if (slide.pos === self.currPos) {
          self.complete();
        }
        return;
      }
      $.envirabox.stop($slide);
      effectClassName = 'envirabox-animated envirabox-slide--' + (slide.pos > self.prevPos ? 'next' : 'previous') + ' envirabox-fx-' + effect;
      $slide.removeAttr('style').removeClass('envirabox-slide--current envirabox-slide--next envirabox-slide--previous').addClass(effectClassName);
      slide.$content.removeClass('envirabox-is-hidden');

      // Force reflow for CSS3 transitions
      forceRedraw($slide);
      $.envirabox.animate($slide, 'envirabox-slide--current', duration, function (e) {
        $slide.removeClass(effectClassName).removeAttr('style');
        if (slide.pos === self.currPos) {
          self.complete();
        }
      }, true);
    },
    // Check if we can and have to zoom from thumbnail
    // ================================================
    getThumbPos: function getThumbPos(slide) {
      var self = this;
      var rez = false;

      // Check if element is inside the viewport by at least 1 pixel
      var isElementVisible = function isElementVisible($el) {
        var element = $el[0];
        var elementRect = element.getBoundingClientRect();
        var parentRects = [];
        var visibleInAllParents;
        while (element.parentElement !== null) {
          if ($(element.parentElement).css('overflow') === 'hidden' || $(element.parentElement).css('overflow') === 'auto') {
            parentRects.push(element.parentElement.getBoundingClientRect());
          }
          element = element.parentElement;
        }
        visibleInAllParents = parentRects.every(function (parentRect) {
          var visiblePixelX = Math.min(elementRect.right, parentRect.right) - Math.max(elementRect.left, parentRect.left);
          var visiblePixelY = Math.min(elementRect.bottom, parentRect.bottom) - Math.max(elementRect.top, parentRect.top);
          return visiblePixelX > 0 && visiblePixelY > 0;
        });
        return visibleInAllParents && elementRect.bottom > 0 && elementRect.right > 0 && elementRect.left < $(window).width() && elementRect.top < $(window).height();
      };
      var $thumb = slide.opts.$thumb;
      var thumbPos = $thumb ? $thumb.offset() : 0;
      var slidePos;
      if (thumbPos && $thumb[0].ownerDocument === document && isElementVisible($thumb)) {
        slidePos = self.$refs.stage.offset();
        rez = {
          top: thumbPos.top - slidePos.top + parseFloat($thumb.css('border-top-width') || 0),
          left: thumbPos.left - slidePos.left + parseFloat($thumb.css('border-left-width') || 0),
          width: $thumb.width(),
          height: $thumb.height(),
          scaleX: 1,
          scaleY: 1
        };
      }
      return rez;
    },
    // Final adjustments after current gallery item is moved to position
    // and it`s content is loaded
    // ==================================================================
    complete: function complete() {
      var self = this;
      var current = self.current;
      var slides = {};
      if (current.isMoved || !current.isLoaded || current.isComplete) {
        return;
      }
      current.isComplete = true;
      current.$slide.siblings().trigger('onReset');

      // Trigger any CSS3 transiton inside the slide
      forceRedraw(current.$slide);
      current.$slide.addClass('envirabox-slide--complete');

      // Remove unnecessary slides
      $.each(self.slides, function (key, slide) {
        if (slide.pos >= self.currPos - 1 && slide.pos <= self.currPos + 1) {
          slides[slide.pos] = slide;
        } else if (slide) {
          $.envirabox.stop(slide.$slide);
          slide.$slide.off().remove();
        }
      });
      self.slides = slides;
      self.updateCursor();
      self.trigger('afterShow');

      // Try to focus on the first focusable element
      if ($(document.activeElement).is('[disabled]') || current.opts.autoFocus && !(current.type == 'image' || current.type === 'iframe')) {
        self.focus();
      }
    },
    // Preload next and previous slides
    // ================================
    preload: function preload() {
      var self = this;
      var next, prev;
      if (self.group.length < 2) {
        return;
      }
      next = self.slides[self.currPos + 1];
      prev = self.slides[self.currPos - 1];
      if (next && next.type === 'image') {
        self.loadSlide(next);
      }
      if (prev && prev.type === 'image') {
        self.loadSlide(prev);
      }
    },
    // Try to find and focus on the first focusable element
    // ====================================================
    focus: function focus() {
      var current = this.current;
      var $el;
      if (this.isClosing) {
        return;
      }
      if (current && current.isComplete) {
        // Look for first input with autofocus attribute
        $el = current.$slide.find('input[autofocus]:enabled:visible:first');
        if (!$el.length) {
          $el = current.$slide.find('button,:input,[tabindex],a').filter(':enabled:visible:first');
        }
      }
      $el = $el && $el.length ? $el : this.$refs.container;
      $el.focus();
    },
    // Activates current instance - brings container to the front and enables keyboard,
    // notifies other instances about deactivating
    // =================================================================================
    activate: function activate() {
      var self = this;

      // Deactivate all instances
      $('.envirabox-container').each(function () {
        var instance = $(this).data('envirabox');

        // Skip self and closing instances
        if (instance && instance.uid !== self.uid && !instance.isClosing) {
          instance.trigger('onDeactivate');
        }
      });
      if (self.current) {
        if (self.$refs.container.index() > 0) {
          self.$refs.container.prependTo(document.body);
        }
        self.updateControls();
      }
      self.trigger('onActivate');
      self.addEvents();
    },
    // Start closing procedure
    // This will start "zoom-out" animation if needed and clean everything up afterwards
    // =================================================================================
    close: function close(e, d) {
      var self = this;
      var current = self.current;
      var effect, duration;
      var $what, opacity, start, end;
      var done = function done() {
        self.cleanUp(e);
      };
      if (self.isClosing) {
        return false;
      }
      self.isClosing = true;

      // If beforeClose callback prevents closing, make sure content is centered
      if (self.trigger('beforeClose', e) === false) {
        self.isClosing = false;
        requestAFrame(function () {
          self.update();
        });
        return false;
      }

      // Remove all events
      // If there are multiple instances, they will be set again by "activate" method
      self.removeEvents();
      if (current.timouts) {
        clearTimeout(current.timouts);
      }
      $what = current.$content;
      effect = current.opts.animationEffect;
      duration = $.isNumeric(d) ? d : effect ? current.opts.animationDuration : 0;

      // Remove other slides
      current.$slide.off(transitionEnd).removeClass('envirabox-slide--complete envirabox-slide--next envirabox-slide--previous envirabox-animated');
      current.$slide.siblings().trigger('onReset').remove();

      // Trigger animations
      if (duration) {
        self.$refs.container.removeClass('envirabox-is-open').addClass('envirabox-is-closing');
      }

      // Clean up
      self.hideLoading(current);
      self.hideControls();
      self.updateCursor();

      // Check if possible to zoom-out
      if (effect === 'zoom' && !(e !== true && $what && duration && current.type === 'image' && !current.hasError && (end = self.getThumbPos(current)))) {
        effect = 'fade';
      }
      if (effect === 'zoom') {
        $.envirabox.stop($what);
        start = $.envirabox.getTranslate($what);
        start.width = start.width * start.scaleX;
        start.height = start.height * start.scaleY;

        // Check if we need to animate opacity
        opacity = current.opts.zoomOpacity;
        if (opacity == 'auto') {
          opacity = Math.abs(current.width / current.height - end.width / end.height) > 0.1;
        }
        if (opacity) {
          end.opacity = 0;
        }
        start.scaleX = start.width / end.width;
        start.scaleY = start.height / end.height;
        start.width = end.width;
        start.height = end.height;
        $.envirabox.setTranslate(current.$content, start);
        $.envirabox.animate(current.$content, end, duration, done);
        return true;
      }
      if (effect && duration) {
        // If skip animation
        if (e === true) {
          setTimeout(done, duration);
        } else {
          $.envirabox.animate(current.$slide.removeClass('envirabox-slide--current'), 'envirabox-animated envirabox-slide--previous envirabox-fx-' + effect, duration, done);
        }
      } else {
        done();
      }
      return true;
    },
    // Final adjustments after removing the instance
    // =============================================
    cleanUp: function cleanUp(e) {
      var self = this,
        instance;
      self.current.$slide.trigger('onReset');
      self.$refs.container.empty().remove();
      self.trigger('afterClose', e);

      // Place back focus
      if (self.$lastFocus && !!self.current.opts.backFocus) {
        self.$lastFocus.focus();
      }
      self.current = null;

      // Check if there are other instances
      instance = $.envirabox.getInstance();
      if (instance) {
        instance.activate();
      } else {
        $W.scrollTop(self.scrollTop).scrollLeft(self.scrollLeft);
        $('html').removeClass('envirabox-enabled');
        $('#envirabox-style-noscroll').remove();
      }
    },
    // Call callback and trigger an event
    // ==================================
    trigger: function trigger(name, slide) {
      var args = Array.prototype.slice.call(arguments, 1),
        self = this,
        obj = slide && slide.opts ? slide : self.current,
        rez;
      if (obj) {
        args.unshift(obj);
      } else {
        obj = self;
      }
      args.unshift(self);
      if ($.isFunction(obj.opts[name])) {
        rez = obj.opts[name].apply(obj, args);
      }
      if (rez === false) {
        return rez;
      }
      if (name === 'afterClose') {
        $D.trigger(name + '.eb', args);
      } else {
        self.$refs.container.trigger(name + '.eb', args);
      }
    },
    // Update infobar values, navigation button states and reveal caption
    // ==================================================================
    updateControls: function updateControls(force) {
      var self = this,
        current = self.current,
        index = current.index,
        opts = current.opts,
        caption = opts.caption,
        title = opts.title,
        $caption = self.$refs.caption,
        $title = self.$refs.title;

      // Recalculate content dimensions
      current.$slide.trigger('refresh');

      // function htmlDecode(input){
      //   let e = document.createElement('div');
      //   e.innerHTML = input;
      //   if ( e.childNodes[0] !== undefined ) {
      //        return e.childNodes[0].nodeValue;
      //   } else {
      //        return false;
      //   }

      // }
      // let html_decoded_caption = htmlDecode(caption);
      // if ( html_decoded_caption !== false ) {
      //   caption = html_decoded_caption;
      // }

      self.$caption = caption && caption.length ? $caption.html(caption) : null;
      self.$title = title && title.length ? $title.html(title) : null;
      if (!self.isHiddenControls) {
        self.showControls();
      }

      // Update info and navigation elements
      $('[data-envirabox-count]').html(self.group.length);
      $('[data-envirabox-index]').html(index + 1);
      $('[data-envirabox-prev]').prop('disabled', !opts.loop && index <= 0);
      $('[data-envirabox-next]').prop('disabled', !opts.loop && index >= self.group.length - 1);
    },
    // Hide toolbar and caption
    // ========================
    hideControls: function hideControls() {
      this.isHiddenControls = true;
      this.$refs.container.removeClass('envirabox-show-infobar envirabox-show-toolbar envirabox-show-caption envirabox-show-title envirabox-show-nav envirabox-show-exif');
      this.$refs.container.addClass('envirabox-hide-exif');
    },
    showControls: function showControls() {
      var self = this,
        opts = self.current ? self.current.opts : self.opts,
        $container = self.$refs.container;
      self.isHiddenControls = false;
      self.idleSecondsCounter = 0;
      $container.toggleClass('envirabox-show-toolbar', !!(opts.toolbar && opts.buttons)).toggleClass('envirabox-show-infobar', !!(opts.infobar && self.group.length > 1)).toggleClass('envirabox-show-nav', !!(opts.arrows && self.group.length > 1)).toggleClass('envirabox-is-modal', !!opts.modal);
      if (self.$caption) {
        $container.addClass('envirabox-show-caption ');
      } else {
        $container.removeClass('envirabox-show-caption');
      }
      if (self.$title) {
        $container.addClass('envirabox-show-title ');
      } else {
        $container.removeClass('envirabox-show-title');
      }
      $container.addClass('envirabox-show-exif');
      $container.removeClass('envirabox-hide-exif');
    },
    // Toggle toolbar and caption
    // ==========================
    toggleControls: function toggleControls() {
      if (this.isHiddenControls) {
        this.showControls();
      } else {
        this.hideControls();
      }
    }
  });
  $.envirabox = {
    version: '{envirabox-version}',
    defaults: defaults,
    // Get current instance and execute a command.
    //
    // Examples of usage:
    //
    // $instance = $.envirabox.getInstance();
    // $.envirabox.getInstance().jumpTo( 1 );
    // $.envirabox.getInstance( 'jumpTo', 1 );
    // $.envirabox.getInstance( function() {
    // console.info( this.currIndex );
    // });
    // ======================================================
    getInstance: function getInstance(command) {
      var selector = '.envirabox-container:not(".envirabox-is-closing"):first';
      var instance = $(selector).data('envirabox');
      var args = Array.prototype.slice.call(arguments, 1);
      if (instance instanceof EnviraBox) {
        if ($.type(command) === 'string') {
          instance[command].apply(instance, args);
        } else if ($.type(command) === 'function') {
          command.apply(instance, args);
        }
        return instance;
      }
      return false;
    },
    // Create new instance
    // ===================
    open: function open(items, opts, index) {
      var instance = this.getInstance();
      if (instance) {
        return;
      }
      return new EnviraBox(items, opts, index);
    },
    // Close current or all instances
    // ==============================
    close: function close(all) {
      var instance = this.getInstance();
      if (instance) {
        instance.close();

        // Try to find and close next instance
        if (all === true) {
          this.close();
        }
      }
    },
    // Close instances and unbind all events
    // ==============================
    destroy: function destroy() {
      this.close(true);
      $D.off('click.eb-start');
    },
    // Try to detect mobile devices
    // ============================
    isMobile: document.createTouch !== undefined && /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent),
    // Detect if 'translate3d' support is available
    // ============================================
    use3d: function () {
      var div = document.createElement('div');
      return window.getComputedStyle && window.getComputedStyle(div).getPropertyValue('transform') && !(document.documentMode && document.documentMode < 11);
    }(),
    // Helper function to get current visual state of an element
    // returns array[ top, left, horizontal-scale, vertical-scale, opacity ]
    // =====================================================================
    getTranslate: function getTranslate($el) {
      var matrix;
      if (!$el || !$el.length) {
        return false;
      }
      matrix = $el.eq(0).css('transform');
      if (matrix && matrix.indexOf('matrix') !== -1) {
        matrix = matrix.split('(')[1];
        matrix = matrix.split(')')[0];
        matrix = matrix.split(',');
      } else {
        matrix = [];
      }
      if (matrix.length) {
        // If IE
        if (matrix.length > 10) {
          matrix = [matrix[13], matrix[12], matrix[0], matrix[5]];
        } else {
          matrix = [matrix[5], matrix[4], matrix[0], matrix[3]];
        }
        matrix = matrix.map(parseFloat);
      } else {
        matrix = [0, 0, 1, 1];
        var transRegex = /\.*translate\((.*)px,(.*)px\)/i;
        var transRez = transRegex.exec($el.eq(0).attr('style'));
        if (transRez) {
          matrix[0] = parseFloat(transRez[2]);
          matrix[1] = parseFloat(transRez[1]);
        }
      }
      return {
        top: matrix[0],
        left: matrix[1],
        scaleX: matrix[2],
        scaleY: matrix[3],
        opacity: parseFloat($el.css('opacity')),
        width: $el.width(),
        height: $el.height()
      };
    },
    // Shortcut for setting "translate3d" properties for element
    // Can set be used to set opacity, too
    // ========================================================
    setTranslate: function setTranslate($el, props) {
      var str = '';
      var css = {};
      if (!$el || !props) {
        return;
      }
      if (props.left !== undefined || props.top !== undefined) {
        str = (props.left === undefined ? $el.position().left : props.left) + 'px, ' + (props.top === undefined ? $el.position().top : props.top) + 'px';
        if (this.use3d) {
          str = 'translate3d(' + str + ', 0px)';
        } else {
          str = 'translate(' + str + ')';
        }
      }
      if (props.scaleX !== undefined && props.scaleY !== undefined) {
        str = (str.length ? str + ' ' : '') + 'scale(' + props.scaleX + ', ' + props.scaleY + ')';
      }
      if (str.length) {
        css.transform = str;
      }
      if (props.opacity !== undefined) {
        css.opacity = props.opacity;
      }
      if (props.width !== undefined) {
        css.width = props.width;
      }
      if (props.height !== undefined) {
        css.height = props.height;
      }
      return $el.css(css);
    },
    // Simple CSS transition handler
    // =============================
    animate: function animate($el, to, duration, callback, leaveAnimationName) {
      var event = transitionEnd || 'transitionend';
      if ($.isFunction(duration)) {
        callback = duration;
        duration = null;
      }
      if (!$.isPlainObject(to)) {
        $el.removeAttr('style');
      }
      $el.on(event, function (e) {
        // Skip events from child elements and z-index change
        if (e && e.originalEvent && (!$el.is(e.originalEvent.target) || e.originalEvent.propertyName == 'z-index')) {
          return;
        }
        $el.off(event);
        if ($.isPlainObject(to)) {
          if (to.scaleX !== undefined && to.scaleY !== undefined) {
            $el.css('transition-duration', '0ms');
            to.width = Math.round($el.width() * to.scaleX);
            to.height = Math.round($el.height() * to.scaleY);
            to.scaleX = 1;
            to.scaleY = 1;
            $.envirabox.setTranslate($el, to);
          }
        } else if (leaveAnimationName !== true) {
          $el.removeClass(to);
        }
        if ($.isFunction(callback)) {
          callback(e);
        }
      });
      if ($.isNumeric(duration)) {
        $el.css('transition-duration', duration + 'ms');
      }
      if ($.isPlainObject(to)) {
        $.envirabox.setTranslate($el, to);
      } else {
        $el.addClass(to);
      }
      $el.data('timer', setTimeout(function () {
        $el.trigger('transitionend');
      }, duration + 16));
    },
    stop: function stop($el) {
      clearTimeout($el.data('timer'));
      $el.off(transitionEnd);
    }
  };
  function enviraEncodeHTMLEntities(text) {
    text = text.replace('&£', '&#');
    text = $('<textarea/>').html(text).text();
    text = text.replace('&£', '&#'); // done twice on purpose
    return text;
  }

  // Default click handler for "enviraboxed" links
  // ============================================
  function _run(e) {
    var target = e.currentTarget,
      opts = e.data ? e.data.options : {},
      items = opts.selector ? $(opts.selector) : e.data ? e.data.items : [],
      value = $(target).attr('data-envirabox') || '',
      index = 0,
      active = $.envirabox.getInstance();
    e.preventDefault();

    // Avoid opening multiple times
    if (active && active.current.opts.$orig.is(target)) {
      return;
    }

    // Get all related items and find index for clicked one
    if (value) {
      items = items.length ? items.filter('[data-envirabox="' + value + '"]') : $('[data-envirabox="' + value + '"]');
      index = items.index(target);

      // Sometimes current item can not be found
      // (for example, when slider clones items)
      if (index < 0) {
        index = 0;
      }
    } else {
      items = [target];
    }
    $.envirabox.open(items, opts, index);
  }

  // Create a jQuery plugin
  // ======================
  $.fn.envirabox = function (options) {
    var selector;
    options = options || {};
    selector = options.selector || false;
    if (selector) {
      $('body').off('click.eb-start', selector).on('click.eb-start', selector, {
        options: options
      }, _run);
    } else {
      this.off('click.eb-start').on('click.eb-start', {
        items: this,
        options: options
      }, _run);
    }
    return this;
  };
})(window, document, window.jQuery || jQuery);

/***/ }),

/***/ 165:
/***/ ((module, exports, __webpack_require__) => {

var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
/**
 * Isotope Item
 **/

(function (window, factory) {
  // universal module definition
  /* jshint strict: false */ /*globals define, module, require */
  if (true) {
    // AMD
    !(__WEBPACK_AMD_DEFINE_ARRAY__ = [__webpack_require__(794)], __WEBPACK_AMD_DEFINE_FACTORY__ = (factory),
		__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
		(__WEBPACK_AMD_DEFINE_FACTORY__.apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__)) : __WEBPACK_AMD_DEFINE_FACTORY__),
		__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
  } else {}
})(window, function factory(Outlayer) {
  'use strict';

  // -------------------------- Item -------------------------- //
  // sub-class Outlayer Item
  function Item() {
    Outlayer.Item.apply(this, arguments);
  }
  var proto = Item.prototype = Object.create(Outlayer.Item.prototype);
  var _create = proto._create;
  proto._create = function () {
    // assign id, used for original-order sorting
    this.id = this.layout.itemGUID++;
    _create.call(this);
    this.sortData = {};
  };
  proto.updateSortData = function () {
    if (this.isIgnored) {
      return;
    }
    // default sorters
    this.sortData.id = this.id;
    // for backward compatibility
    this.sortData['original-order'] = this.id;
    this.sortData.random = Math.random();
    // go thru getSortData obj and apply the sorters
    var getSortData = this.layout.options.getSortData;
    var sorters = this.layout._sorters;
    for (var key in getSortData) {
      var sorter = sorters[key];
      this.sortData[key] = sorter(this.element, this);
    }
  };
  var _destroy = proto.destroy;
  proto.destroy = function () {
    // call super
    _destroy.apply(this, arguments);
    // reset display, #741
    this.css({
      display: ''
    });
  };
  return Item;
});

/***/ }),

/***/ 204:
/***/ (function(module, exports, __webpack_require__) {

var __WEBPACK_LOCAL_MODULE_0__, __WEBPACK_LOCAL_MODULE_0__factory, __WEBPACK_LOCAL_MODULE_0__module;var __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
/**
 * enviraImagesLoaded PACKAGED v4.1.0
 * JavaScript is all like "You images are done yet or what?"
 * MIT License
 */

/**
 * EvEmitter v1.0.1
 * Lil' event emitter
 * MIT License
 */

/* jshint unused: true, undef: true, strict: true */

(function (global, factory) {
  // universal module definition
  /* jshint strict: false */ /* globals define, module */
  if (true) {
    // AMD - RequireJS
    !(__WEBPACK_LOCAL_MODULE_0__factory = (factory), (typeof __WEBPACK_LOCAL_MODULE_0__factory === 'function' ? ((__WEBPACK_LOCAL_MODULE_0__module = { id: "ev-emitter/ev-emitter", exports: {}, loaded: false }), (__WEBPACK_LOCAL_MODULE_0__ = __WEBPACK_LOCAL_MODULE_0__factory.call(__WEBPACK_LOCAL_MODULE_0__module.exports, __webpack_require__, __WEBPACK_LOCAL_MODULE_0__module.exports, __WEBPACK_LOCAL_MODULE_0__module)), (__WEBPACK_LOCAL_MODULE_0__module.loaded = true), __WEBPACK_LOCAL_MODULE_0__ === undefined && (__WEBPACK_LOCAL_MODULE_0__ = __WEBPACK_LOCAL_MODULE_0__module.exports)) : __WEBPACK_LOCAL_MODULE_0__ = __WEBPACK_LOCAL_MODULE_0__factory));
  } else {}
})(this, function () {
  function EvEmitter() {}
  var proto = EvEmitter.prototype;
  proto.on = function (eventName, listener) {
    if (!eventName || !listener) {
      return;
    }
    // set events hash
    var events = this._events = this._events || {};
    // set listeners array
    var listeners = events[eventName] = events[eventName] || [];
    // only add once
    if (-1 === listeners.indexOf(listener)) {
      listeners.push(listener);
    }
    return this;
  };
  proto.once = function (eventName, listener) {
    if (!eventName || !listener) {
      return;
    }
    // add event
    this.on(eventName, listener);
    // set once flag
    // set onceEvents hash
    var onceEvents = this._onceEvents = this._onceEvents || {};
    // set onceListeners array
    var onceListeners = onceEvents[eventName] = onceEvents[eventName] || [];
    // set flag
    onceListeners[listener] = true;
    return this;
  };
  proto.off = function (eventName, listener) {
    var listeners = this._events && this._events[eventName];
    if (!listeners || !listeners.length) {
      return;
    }
    var index = listeners.indexOf(listener);
    if (-1 !== index) {
      listeners.splice(index, 1);
    }
    return this;
  };
  proto.emitEvent = function (eventName, args) {
    var listeners = this._events && this._events[eventName];
    if (!listeners || !listeners.length) {
      return;
    }
    var i = 0;
    var listener = listeners[i];
    args = args || [];
    // once stuff
    var onceListeners = this._onceEvents && this._onceEvents[eventName];
    while (listener) {
      var isOnce = onceListeners && onceListeners[listener];
      if (isOnce) {
        // remove listener
        // remove before trigger to prevent recursion
        this.off(eventName, listener);
        // unset once flag
        delete onceListeners[listener];
      }
      // trigger listener
      listener.apply(this, args);
      // get next listener
      i += isOnce ? 0 : 1;
      listener = listeners[i];
    }
    return this;
  };
  return EvEmitter;
});

/*!
 * enviraImagesLoaded v4.1.0
 * JavaScript is all like "You images are done yet or what?"
 * MIT License
 */

(function (window, factory) {
  'use strict';

  // universal module definition

  /*global define: false, module: false, require: false */
  if (true) {
    // AMD
    !(__WEBPACK_AMD_DEFINE_ARRAY__ = [__WEBPACK_LOCAL_MODULE_0__], __WEBPACK_AMD_DEFINE_RESULT__ = (function (EvEmitter) {
      return factory(window, EvEmitter);
    }).apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__),
		__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
  } else {}
})(window,
// --------------------------  factory -------------------------- //

function factory(window, EvEmitter) {
  var $ = window.jQuery;
  var console = window.console;

  // -------------------------- helpers -------------------------- //

  // extend objects
  function extend(a, b) {
    for (var prop in b) {
      a[prop] = b[prop];
    }
    return a;
  }

  // turn element or nodeList into an array
  function makeArray(obj) {
    var ary = [];
    if (Array.isArray(obj)) {
      // use object if already an array
      ary = obj;
    } else if (typeof obj.length == 'number') {
      // convert nodeList to array
      for (var i = 0; i < obj.length; i++) {
        ary.push(obj[i]);
      }
    } else {
      // array of single index
      ary.push(obj);
    }
    return ary;
  }

  // -------------------------- enviraImagesLoaded -------------------------- //

  /**
   * @param {Array, Element, NodeList, String} elem
   * @param {Object} options - if function, use as callback
   * @param {Function} onAlways - callback function
   */
  function EnviraImagesLoaded(elem, options, onAlways) {
    // coerce EnviraImagesLoaded() without new, to be new EnviraImagesLoaded()
    if (!(this instanceof EnviraImagesLoaded)) {
      return new EnviraImagesLoaded(elem, options, onAlways);
    }
    // use elem as selector string
    if (typeof elem == 'string') {
      elem = document.querySelectorAll(elem);
    }
    this.elements = makeArray(elem);
    this.options = extend({}, this.options);
    if (typeof options == 'function') {
      onAlways = options;
    } else {
      extend(this.options, options);
    }
    if (onAlways) {
      this.on('always', onAlways);
    }
    this.getImages();
    if ($) {
      // add jQuery Deferred object
      this.jqDeferred = new $.Deferred();
    }

    // HACK check async to allow time to bind listeners
    setTimeout(function () {
      this.check();
    }.bind(this));
  }
  EnviraImagesLoaded.prototype = Object.create(EvEmitter.prototype);
  EnviraImagesLoaded.prototype.options = {};
  EnviraImagesLoaded.prototype.getImages = function () {
    this.images = [];

    // filter & find items if we have an item selector
    this.elements.forEach(this.addElementImages, this);
  };

  /**
   * @param {Node} elem
   */
  EnviraImagesLoaded.prototype.addElementImages = function (elem) {
    // filter siblings
    if (elem.nodeName === 'IMG') {
      this.addImage(elem);
    }
    // get background image on element
    if (this.options.background === true) {
      this.addElementBackgroundImages(elem);
    }

    // find children
    // no non-element nodes, #143
    var nodeType = elem.nodeType;
    if (!nodeType || !elementNodeTypes[nodeType]) {
      return;
    }
    var childImgs = elem.querySelectorAll('img');
    // concat childElems to filterFound array
    for (var i = 0; i < childImgs.length; i++) {
      var img = childImgs[i];
      this.addImage(img);
    }

    // get child background images
    if (typeof this.options.background == 'string') {
      var children = elem.querySelectorAll(this.options.background);
      for (i = 0; i < children.length; i++) {
        var child = children[i];
        this.addElementBackgroundImages(child);
      }
    }
  };
  var elementNodeTypes = {
    1: true,
    9: true,
    11: true
  };
  EnviraImagesLoaded.prototype.addElementBackgroundImages = function (elem) {
    var style = getComputedStyle(elem);
    if (!style) {
      // Firefox returns null if in a hidden iframe https://bugzil.la/548397
      return;
    }
    // get url inside url("...")
    var reURL = /url\((['"])?(.*?)\1\)/gi;
    var matches = reURL.exec(style.backgroundImage);
    while (matches !== null) {
      var url = matches && matches[2];
      if (url) {
        this.addBackground(url, elem);
      }
      matches = reURL.exec(style.backgroundImage);
    }
  };

  /**
   * @param {Image} img
   */
  EnviraImagesLoaded.prototype.addImage = function (img) {
    var loadingImage = new LoadingImage(img);
    this.images.push(loadingImage);
  };
  EnviraImagesLoaded.prototype.addBackground = function (url, elem) {
    var background = new Background(url, elem);
    this.images.push(background);
  };
  EnviraImagesLoaded.prototype.check = function () {
    var _this = this;
    this.progressedCount = 0;
    this.hasAnyBroken = false;
    // complete if no images
    if (!this.images.length) {
      this.complete();
      return;
    }
    function onProgress(image, elem, message) {
      // HACK - Chrome triggers event before object properties have changed. #83
      setTimeout(function () {
        _this.progress(image, elem, message);
      });
    }
    this.images.forEach(function (loadingImage) {
      loadingImage.once('progress', onProgress);
      loadingImage.check();
    });
  };
  EnviraImagesLoaded.prototype.progress = function (image, elem, message) {
    this.progressedCount++;
    this.hasAnyBroken = this.hasAnyBroken || !image.isLoaded;
    // progress event
    this.emitEvent('progress', [this, image, elem]);
    if (this.jqDeferred && this.jqDeferred.notify) {
      this.jqDeferred.notify(this, image);
    }
    // check if completed
    if (this.progressedCount === this.images.length) {
      this.complete();
    }
    if (this.options.debug && console) {
      console.log('progress: ' + message, image, elem);
    }
  };
  EnviraImagesLoaded.prototype.complete = function () {
    var eventName = this.hasAnyBroken ? 'fail' : 'done';
    this.isComplete = true;
    this.emitEvent(eventName, [this]);
    this.emitEvent('always', [this]);
    if (this.jqDeferred) {
      var jqMethod = this.hasAnyBroken ? 'reject' : 'resolve';
      this.jqDeferred[jqMethod](this);
    }
  };

  // --------------------------  -------------------------- //

  function LoadingImage(img) {
    this.img = img;
  }
  LoadingImage.prototype = Object.create(EvEmitter.prototype);
  LoadingImage.prototype.check = function () {
    // If complete is true and browser supports natural sizes,
    // try to check for image status manually.
    var isComplete = this.getIsImageComplete();
    if (isComplete) {
      // report based on naturalWidth
      this.confirm(this.img.naturalWidth !== 0, 'naturalWidth');
      return;
    }

    // If none of the checks above matched, simulate loading on detached element.
    this.proxyImage = new Image();
    this.proxyImage.addEventListener('load', this);
    this.proxyImage.addEventListener('error', this);
    // bind to image as well for Firefox. #191
    this.img.addEventListener('load', this);
    this.img.addEventListener('error', this);
    this.proxyImage.src = this.img.pendingSrc || this.img.currentSrc || this.img.src;
  };
  LoadingImage.prototype.getIsImageComplete = function () {
    return this.img.complete && this.img.naturalWidth !== undefined;
  };
  LoadingImage.prototype.confirm = function (isLoaded, message) {
    this.isLoaded = isLoaded;
    this.emitEvent('progress', [this, this.img, message]);
  };

  // ----- events ----- //

  // trigger specified handler for event type
  LoadingImage.prototype.handleEvent = function (event) {
    var method = 'on' + event.type;
    if (this[method]) {
      this[method](event);
    }
  };
  LoadingImage.prototype.onload = function () {
    this.confirm(true, 'onload');
    this.unbindEvents();
  };
  LoadingImage.prototype.onerror = function () {
    this.confirm(false, 'onerror');
    this.unbindEvents();
  };
  LoadingImage.prototype.unbindEvents = function () {
    this.proxyImage.removeEventListener('load', this);
    this.proxyImage.removeEventListener('error', this);
    this.img.removeEventListener('load', this);
    this.img.removeEventListener('error', this);
  };

  // -------------------------- Background -------------------------- //

  function Background(url, element) {
    this.url = url;
    this.element = element;
    this.img = new Image();
  }

  // inherit LoadingImage prototype
  Background.prototype = Object.create(LoadingImage.prototype);
  Background.prototype.check = function () {
    this.img.addEventListener('load', this);
    this.img.addEventListener('error', this);
    this.img.src = this.url;
    // check if image is already complete
    var isComplete = this.getIsImageComplete();
    if (isComplete) {
      this.confirm(this.img.naturalWidth !== 0, 'naturalWidth');
      this.unbindEvents();
    }
  };
  Background.prototype.unbindEvents = function () {
    this.img.removeEventListener('load', this);
    this.img.removeEventListener('error', this);
  };
  Background.prototype.confirm = function (isLoaded, message) {
    this.isLoaded = isLoaded;
    this.emitEvent('progress', [this, this.element, message]);
  };

  // -------------------------- jQuery -------------------------- //

  EnviraImagesLoaded.makeJQueryPlugin = function (jQuery) {
    jQuery = jQuery || window.jQuery;
    if (!jQuery) {
      return;
    }
    // set local variable
    $ = jQuery;
    // $().enviraImagesLoaded()
    $.fn.enviraImagesLoaded = function (options, callback) {
      var instance = new EnviraImagesLoaded(this, options, callback);
      return instance.jqDeferred.promise($(this));
    };
  };
  // try making plugin
  EnviraImagesLoaded.makeJQueryPlugin();

  // --------------------------  -------------------------- //

  return EnviraImagesLoaded;
});

/***/ }),

/***/ 968:
/***/ (function(module, exports, __webpack_require__) {

var __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;var __WEBPACK_LOCAL_MODULE_1__, __WEBPACK_LOCAL_MODULE_1__factory, __WEBPACK_LOCAL_MODULE_1__module;var __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_LOCAL_MODULE_2__, __WEBPACK_LOCAL_MODULE_2__exports;var __WEBPACK_LOCAL_MODULE_3__, __WEBPACK_LOCAL_MODULE_3__factory, __WEBPACK_LOCAL_MODULE_3__module;var __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_LOCAL_MODULE_4__, __WEBPACK_LOCAL_MODULE_4__exports;var __WEBPACK_LOCAL_MODULE_5__array, __WEBPACK_LOCAL_MODULE_5__factory, __WEBPACK_LOCAL_MODULE_5__exports, __WEBPACK_LOCAL_MODULE_5__;var __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_LOCAL_MODULE_6__, __WEBPACK_LOCAL_MODULE_6__exports;var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;var __WEBPACK_LOCAL_MODULE_8__array, __WEBPACK_LOCAL_MODULE_8__factory, __WEBPACK_LOCAL_MODULE_8__exports, __WEBPACK_LOCAL_MODULE_8__;var __WEBPACK_LOCAL_MODULE_9__array, __WEBPACK_LOCAL_MODULE_9__factory, __WEBPACK_LOCAL_MODULE_9__exports, __WEBPACK_LOCAL_MODULE_9__;var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;var __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
/*!
 * Enviratope PACKAGED v3.0.0
 *
 * Licensed GPLv3 for open source use
 * or Enviratope Commercial License for commercial use
 *
 * http://enviratope.metafizzy.co
 * Copyright 2016 Metafizzy
 */

/**
 * Bridget makes jQuery widgets
 * v2.0.0
 * MIT license
 */

/* jshint browser: true, strict: true, undef: true, unused: true */

(function (window, factory) {
  'use strict';

  /* globals define: false, module: false, require: false */
  if (true) {
    // AMD
    !(__WEBPACK_AMD_DEFINE_ARRAY__ = [__webpack_require__(311)], __WEBPACK_AMD_DEFINE_RESULT__ = (function (jQuery) {
      factory(window, jQuery);
    }).apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__),
		__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
  } else {}
})(window, function factory(window, jQuery) {
  'use strict';

  // ----- utils ----- //
  var arraySlice = Array.prototype.slice;

  // helper function for logging errors
  // $.error breaks jQuery chaining
  var console = window.console;
  var logError = typeof console == 'undefined' ? function () {} : function (message) {
    console.error(message);
  };

  // ----- jQueryBridget ----- //

  function jQueryBridget(namespace, PluginClass, $) {
    $ = $ || jQuery || window.jQuery;
    if (!$) {
      return;
    }

    // add option method -> $().plugin('option', {...})
    if (!PluginClass.prototype.option) {
      // option setter
      PluginClass.prototype.option = function (opts) {
        // bail out if not an object
        if (!$.isPlainObject(opts)) {
          return;
        }
        this.options = $.extend(true, this.options, opts);
      };
    }

    // make jQuery plugin
    $.fn[namespace] = function (arg0 /*, arg1 */) {
      if (typeof arg0 == 'string') {
        // method call $().plugin( 'methodName', { options } )
        // shift arguments by 1
        var args = arraySlice.call(arguments, 1);
        return methodCall(this, arg0, args);
      }
      // just $().plugin({ options })
      plainCall(this, arg0);
      return this;
    };

    // $().plugin('methodName')
    function methodCall($elems, methodName, args) {
      var returnValue;
      var pluginMethodStr = '$().' + namespace + '("' + methodName + '")';
      $elems.each(function (i, elem) {
        // get instance
        var instance = $.data(elem, namespace);
        if (!instance) {
          logError(namespace + ' not initialized. Cannot call methods, i.e. ' + pluginMethodStr);
          return;
        }
        var method = instance[methodName];
        if (!method || methodName.charAt(0) == '_') {
          logError(pluginMethodStr + ' is not a valid method');
          return;
        }

        // apply method, get return value
        var value = method.apply(instance, args);
        // set return value if value is returned, use only first value
        returnValue = returnValue === undefined ? value : returnValue;
      });
      return returnValue !== undefined ? returnValue : $elems;
    }
    function plainCall($elems, options) {
      $elems.each(function (i, elem) {
        var instance = $.data(elem, namespace);
        if (instance) {
          // set options & init
          instance.option(options);
          instance._init();
        } else {
          // initialize new instance
          instance = new PluginClass(elem, options);
          $.data(elem, namespace, instance);
        }
      });
    }
    updateJQuery($);
  }

  // ----- updateJQuery ----- //

  // set $.bridget for v1 backwards compatibility
  function updateJQuery($) {
    if (!$ || $ && $.bridget) {
      return;
    }
    $.bridget = jQueryBridget;
  }
  updateJQuery(jQuery || window.jQuery);

  // -----  ----- //

  return jQueryBridget;
});

/**
 * EvEmitter v1.0.2
 * Lil' event emitter
 * MIT License
 */

/* jshint unused: true, undef: true, strict: true */

(function (global, factory) {
  // universal module definition
  /* jshint strict: false */ /* globals define, module */
  if (true) {
    // AMD - RequireJS
    !(__WEBPACK_LOCAL_MODULE_1__factory = (factory), (typeof __WEBPACK_LOCAL_MODULE_1__factory === 'function' ? ((__WEBPACK_LOCAL_MODULE_1__module = { id: "ev-emitter/ev-emitter", exports: {}, loaded: false }), (__WEBPACK_LOCAL_MODULE_1__ = __WEBPACK_LOCAL_MODULE_1__factory.call(__WEBPACK_LOCAL_MODULE_1__module.exports, __webpack_require__, __WEBPACK_LOCAL_MODULE_1__module.exports, __WEBPACK_LOCAL_MODULE_1__module)), (__WEBPACK_LOCAL_MODULE_1__module.loaded = true), __WEBPACK_LOCAL_MODULE_1__ === undefined && (__WEBPACK_LOCAL_MODULE_1__ = __WEBPACK_LOCAL_MODULE_1__module.exports)) : __WEBPACK_LOCAL_MODULE_1__ = __WEBPACK_LOCAL_MODULE_1__factory));
  } else {}
})(this, function () {
  function EvEmitter() {}
  var proto = EvEmitter.prototype;
  proto.on = function (eventName, listener) {
    if (!eventName || !listener) {
      return;
    }
    // set events hash
    var events = this._events = this._events || {};
    // set listeners array
    var listeners = events[eventName] = events[eventName] || [];
    // only add once
    if (listeners.indexOf(listener) == -1) {
      listeners.push(listener);
    }
    return this;
  };
  proto.once = function (eventName, listener) {
    if (!eventName || !listener) {
      return;
    }
    // add event
    this.on(eventName, listener);
    // set once flag
    // set onceEvents hash
    var onceEvents = this._onceEvents = this._onceEvents || {};
    // set onceListeners object
    var onceListeners = onceEvents[eventName] = onceEvents[eventName] || {};
    // set flag
    onceListeners[listener] = true;
    return this;
  };
  proto.off = function (eventName, listener) {
    var listeners = this._events && this._events[eventName];
    if (!listeners || !listeners.length) {
      return;
    }
    var index = listeners.indexOf(listener);
    if (index != -1) {
      listeners.splice(index, 1);
    }
    return this;
  };
  proto.emitEvent = function (eventName, args) {
    var listeners = this._events && this._events[eventName];
    if (!listeners || !listeners.length) {
      return;
    }
    var i = 0;
    var listener = listeners[i];
    args = args || [];
    // once stuff
    var onceListeners = this._onceEvents && this._onceEvents[eventName];
    while (listener) {
      var isOnce = onceListeners && onceListeners[listener];
      if (isOnce) {
        // remove listener
        // remove before trigger to prevent recursion
        this.off(eventName, listener);
        // unset once flag
        delete onceListeners[listener];
      }
      // trigger listener
      listener.apply(this, args);
      // get next listener
      i += isOnce ? 0 : 1;
      listener = listeners[i];
    }
    return this;
  };
  return EvEmitter;
});

/*!
 * getSize v2.0.2
 * measure size of elements
 * MIT license
 */

/*jshint browser: true, strict: true, undef: true, unused: true */
/*global define: false, module: false, console: false */

(function (window, factory) {
  'use strict';

  if (true) {
    // AMD
    !(__WEBPACK_AMD_DEFINE_ARRAY__ = [], __WEBPACK_LOCAL_MODULE_2__ = (function () {
      return factory();
    }).apply(__WEBPACK_LOCAL_MODULE_2__exports = {}, __WEBPACK_AMD_DEFINE_ARRAY__), __WEBPACK_LOCAL_MODULE_2__ === undefined && (__WEBPACK_LOCAL_MODULE_2__ = __WEBPACK_LOCAL_MODULE_2__exports));
  } else {}
})(window, function factory() {
  'use strict';

  // -------------------------- helpers -------------------------- //

  // get a number from a string, not a percentage
  function getStyleSize(value) {
    var num = parseFloat(value);
    // not a percent like '100%', and a number
    var isValid = value.indexOf('%') == -1 && !isNaN(num);
    return isValid && num;
  }
  function noop() {}
  var logError = typeof console == 'undefined' ? noop : function (message) {
    console.error(message);
  };

  // -------------------------- measurements -------------------------- //

  var measurements = ['paddingLeft', 'paddingRight', 'paddingTop', 'paddingBottom', 'marginLeft', 'marginRight', 'marginTop', 'marginBottom', 'borderLeftWidth', 'borderRightWidth', 'borderTopWidth', 'borderBottomWidth'];
  var measurementsLength = measurements.length;
  function getZeroSize() {
    var size = {
      width: 0,
      height: 0,
      innerWidth: 0,
      innerHeight: 0,
      outerWidth: 0,
      outerHeight: 0
    };
    for (var i = 0; i < measurementsLength; i++) {
      var measurement = measurements[i];
      size[measurement] = 0;
    }
    return size;
  }

  // -------------------------- getStyle -------------------------- //

  /**
   * getStyle, get style of element, check for Firefox bug
   * https://bugzilla.mozilla.org/show_bug.cgi?id=548397
   */
  function getStyle(elem) {
    var style = getComputedStyle(elem);
    if (!style) {
      logError('Style returned ' + style + '. Are you running this code in a hidden iframe on Firefox? ' + 'See http://bit.ly/getsizebug1');
    }
    return style;
  }

  // -------------------------- setup -------------------------- //

  var isSetup = false;
  var isBoxSizeOuter;

  /**
   * setup
   * check isBoxSizerOuter
   * do on first getSize() rather than on page load for Firefox bug
   */
  function setup() {
    // setup once
    if (isSetup) {
      return;
    }
    isSetup = true;

    // -------------------------- box sizing -------------------------- //

    /**
     * WebKit measures the outer-width on style.width on border-box elems
     * IE & Firefox<29 measures the inner-width
     */
    var div = document.createElement('div');
    div.style.width = '200px';
    div.style.padding = '1px 2px 3px 4px';
    div.style.borderStyle = 'solid';
    div.style.borderWidth = '1px 2px 3px 4px';
    div.style.boxSizing = 'border-box';
    var body = document.body || document.documentElement;
    body.appendChild(div);
    var style = getStyle(div);
    getSize.isBoxSizeOuter = isBoxSizeOuter = getStyleSize(style.width) == 200;
    body.removeChild(div);
  }

  // -------------------------- getSize -------------------------- //

  function getSize(elem) {
    setup();

    // use querySeletor if elem is string
    if (typeof elem == 'string') {
      elem = document.querySelector(elem);
    }

    // do not proceed on non-objects
    if (!elem || _typeof(elem) != 'object' || !elem.nodeType) {
      return;
    }
    var style = getStyle(elem);

    // if hidden, everything is 0
    if (style.display == 'none') {
      return getZeroSize();
    }
    var size = {};
    size.width = elem.offsetWidth;
    size.height = elem.offsetHeight;
    var isBorderBox = size.isBorderBox = style.boxSizing == 'border-box';

    // get all measurements
    for (var i = 0; i < measurementsLength; i++) {
      var measurement = measurements[i];
      var value = style[measurement];
      var num = parseFloat(value);
      // any 'auto', 'medium' value will be 0
      size[measurement] = !isNaN(num) ? num : 0;
    }
    var paddingWidth = size.paddingLeft + size.paddingRight;
    var paddingHeight = size.paddingTop + size.paddingBottom;
    var marginWidth = size.marginLeft + size.marginRight;
    var marginHeight = size.marginTop + size.marginBottom;
    var borderWidth = size.borderLeftWidth + size.borderRightWidth;
    var borderHeight = size.borderTopWidth + size.borderBottomWidth;
    var isBorderBoxSizeOuter = isBorderBox && isBoxSizeOuter;

    // overwrite width and height if we can get it from style
    var styleWidth = getStyleSize(style.width);
    if (styleWidth !== false) {
      size.width = styleWidth + (
      // add padding and border unless it's already including it
      isBorderBoxSizeOuter ? 0 : paddingWidth + borderWidth);
    }
    var styleHeight = getStyleSize(style.height);
    if (styleHeight !== false) {
      size.height = styleHeight + (
      // add padding and border unless it's already including it
      isBorderBoxSizeOuter ? 0 : paddingHeight + borderHeight);
    }
    size.innerWidth = size.width - (paddingWidth + borderWidth);
    size.innerHeight = size.height - (paddingHeight + borderHeight);
    size.outerWidth = size.width + marginWidth;
    size.outerHeight = size.height + marginHeight;
    return size;
  }
  return getSize;
});

/**
 * matchesSelector v2.0.1
 * matchesSelector( element, '.selector' )
 * MIT license
 */

/*jshint browser: true, strict: true, undef: true, unused: true */

(function (window, factory) {
  /*global define: false, module: false */
  'use strict';

  // universal module definition
  if (true) {
    // AMD
    !(__WEBPACK_LOCAL_MODULE_3__factory = (factory), (typeof __WEBPACK_LOCAL_MODULE_3__factory === 'function' ? ((__WEBPACK_LOCAL_MODULE_3__module = { id: "desandro-matches-selector/matches-selector", exports: {}, loaded: false }), (__WEBPACK_LOCAL_MODULE_3__ = __WEBPACK_LOCAL_MODULE_3__factory.call(__WEBPACK_LOCAL_MODULE_3__module.exports, __webpack_require__, __WEBPACK_LOCAL_MODULE_3__module.exports, __WEBPACK_LOCAL_MODULE_3__module)), (__WEBPACK_LOCAL_MODULE_3__module.loaded = true), __WEBPACK_LOCAL_MODULE_3__ === undefined && (__WEBPACK_LOCAL_MODULE_3__ = __WEBPACK_LOCAL_MODULE_3__module.exports)) : __WEBPACK_LOCAL_MODULE_3__ = __WEBPACK_LOCAL_MODULE_3__factory));
  } else {}
})(window, function factory() {
  'use strict';

  var matchesMethod = function () {
    var ElemProto = Element.prototype;
    // check for the standard method name first
    if (ElemProto.matches) {
      return 'matches';
    }
    // check un-prefixed
    if (ElemProto.matchesSelector) {
      return 'matchesSelector';
    }
    // check vendor prefixes
    var prefixes = ['webkit', 'moz', 'ms', 'o'];
    for (var i = 0; i < prefixes.length; i++) {
      var prefix = prefixes[i];
      var method = prefix + 'MatchesSelector';
      if (ElemProto[method]) {
        return method;
      }
    }
  }();
  return function matchesSelector(elem, selector) {
    return elem[matchesMethod](selector);
  };
});

/**
 * Fizzy UI utils v2.0.1
 * MIT license
 */

/*jshint browser: true, undef: true, unused: true, strict: true */

(function (window, factory) {
  // universal module definition
  /*jshint strict: false */ /*globals define, module, require */

  if (true) {
    // AMD
    !(__WEBPACK_AMD_DEFINE_ARRAY__ = [__WEBPACK_LOCAL_MODULE_3__], __WEBPACK_LOCAL_MODULE_4__ = (function (matchesSelector) {
      return factory(window, matchesSelector);
    }).apply(__WEBPACK_LOCAL_MODULE_4__exports = {}, __WEBPACK_AMD_DEFINE_ARRAY__), __WEBPACK_LOCAL_MODULE_4__ === undefined && (__WEBPACK_LOCAL_MODULE_4__ = __WEBPACK_LOCAL_MODULE_4__exports));
  } else {}
})(window, function factory(window, matchesSelector) {
  var utils = {};

  // ----- extend ----- //

  // extends objects
  utils.extend = function (a, b) {
    for (var prop in b) {
      a[prop] = b[prop];
    }
    return a;
  };

  // ----- modulo ----- //

  utils.modulo = function (num, div) {
    return (num % div + div) % div;
  };

  // ----- makeArray ----- //

  // turn element or nodeList into an array
  utils.makeArray = function (obj) {
    var ary = [];
    if (Array.isArray(obj)) {
      // use object if already an array
      ary = obj;
    } else if (obj && typeof obj.length == 'number') {
      // convert nodeList to array
      for (var i = 0; i < obj.length; i++) {
        ary.push(obj[i]);
      }
    } else {
      // array of single index
      ary.push(obj);
    }
    return ary;
  };

  // ----- removeFrom ----- //

  utils.removeFrom = function (ary, obj) {
    var index = ary.indexOf(obj);
    if (index != -1) {
      ary.splice(index, 1);
    }
  };

  // ----- getParent ----- //

  utils.getParent = function (elem, selector) {
    while (elem != document.body) {
      elem = elem.parentNode;
      if (matchesSelector(elem, selector)) {
        return elem;
      }
    }
  };

  // ----- getQueryElement ----- //

  // use element as selector string
  utils.getQueryElement = function (elem) {
    if (typeof elem == 'string') {
      return document.querySelector(elem);
    }
    return elem;
  };

  // ----- handleEvent ----- //

  // enable .ontype to trigger from .addEventListener( elem, 'type' )
  utils.handleEvent = function (event) {
    var method = 'on' + event.type;
    if (this[method]) {
      this[method](event);
    }
  };

  // ----- filterFindElements ----- //

  utils.filterFindElements = function (elems, selector) {
    // make array of elems
    elems = utils.makeArray(elems);
    var ffElems = [];
    elems.forEach(function (elem) {
      // check that elem is an actual element
      if (!(elem instanceof HTMLElement)) {
        return;
      }
      // add elem if no selector
      if (!selector) {
        ffElems.push(elem);
        return;
      }
      // filter & find items if we have a selector
      // filter
      if (matchesSelector(elem, selector)) {
        ffElems.push(elem);
      }
      // find children
      var childElems = elem.querySelectorAll(selector);
      // concat childElems to filterFound array
      for (var i = 0; i < childElems.length; i++) {
        ffElems.push(childElems[i]);
      }
    });
    return ffElems;
  };

  // ----- debounceMethod ----- //

  utils.debounceMethod = function (_class, methodName, threshold) {
    // original method
    var method = _class.prototype[methodName];
    var timeoutName = methodName + 'Timeout';
    _class.prototype[methodName] = function () {
      var timeout = this[timeoutName];
      if (timeout) {
        clearTimeout(timeout);
      }
      var args = arguments;
      var _this = this;
      this[timeoutName] = setTimeout(function () {
        method.apply(_this, args);
        delete _this[timeoutName];
      }, threshold || 100);
    };
  };

  // ----- docReady ----- //

  utils.docReady = function (callback) {
    if (document.readyState == 'complete') {
      callback();
    } else {
      document.addEventListener('DOMContentLoaded', callback);
    }
  };

  // ----- htmlInit ----- //

  // http://jamesroberts.name/blog/2010/02/22/string-functions-for-javascript-trim-to-camel-case-to-dashed-and-to-underscore/
  utils.toDashed = function (str) {
    return str.replace(/(.)([A-Z])/g, function (match, $1, $2) {
      return $1 + '-' + $2;
    }).toLowerCase();
  };
  var console = window.console;
  /**
   * allow user to initialize classes via [data-namespace] or .js-namespace class
   * htmlInit( Widget, 'widgetName' )
   * options are parsed from data-namespace-options
   */
  utils.htmlInit = function (WidgetClass, namespace) {
    utils.docReady(function () {
      var dashedNamespace = utils.toDashed(namespace);
      var dataAttr = 'data-' + dashedNamespace;
      var dataAttrElems = document.querySelectorAll('[' + dataAttr + ']');
      var jsDashElems = document.querySelectorAll('.js-' + dashedNamespace);
      var elems = utils.makeArray(dataAttrElems).concat(utils.makeArray(jsDashElems));
      var dataOptionsAttr = dataAttr + '-options';
      var jQuery = window.jQuery;
      elems.forEach(function (elem) {
        var attr = elem.getAttribute(dataAttr) || elem.getAttribute(dataOptionsAttr);
        var options;
        try {
          options = attr && JSON.parse(attr);
        } catch (error) {
          // log error, do not initialize
          if (console) {
            console.error('Error parsing ' + dataAttr + ' on ' + elem.className + ': ' + error);
          }
          return;
        }
        // initialize
        var instance = new WidgetClass(elem, options);
        // make available via $().data('layoutname')
        if (jQuery) {
          jQuery.data(elem, namespace, instance);
        }
      });
    });
  };

  // -----  ----- //

  return utils;
});

/**
 * Outlayer Item
 */

(function (window, factory) {
  // universal module definition
  /* jshint strict: false */ /* globals define, module, require */
  if (true) {
    // AMD - RequireJS
    !(__WEBPACK_LOCAL_MODULE_5__array = [__WEBPACK_LOCAL_MODULE_1__, __WEBPACK_LOCAL_MODULE_2__], __WEBPACK_LOCAL_MODULE_5__factory = (factory),
		(typeof __WEBPACK_LOCAL_MODULE_5__factory === 'function' ?
			((__WEBPACK_LOCAL_MODULE_5__ = __WEBPACK_LOCAL_MODULE_5__factory.apply(__WEBPACK_LOCAL_MODULE_5__exports = {}, __WEBPACK_LOCAL_MODULE_5__array)), __WEBPACK_LOCAL_MODULE_5__ === undefined && (__WEBPACK_LOCAL_MODULE_5__ = __WEBPACK_LOCAL_MODULE_5__exports)) :
			(__WEBPACK_LOCAL_MODULE_5__ = __WEBPACK_LOCAL_MODULE_5__factory)
		));
  } else {}
})(window, function factory(EvEmitter, getSize) {
  'use strict';

  // ----- helpers ----- //
  function isEmptyObj(obj) {
    for (var prop in obj) {
      return false;
    }
    prop = null;
    return true;
  }

  // -------------------------- CSS3 support -------------------------- //

  var docElemStyle = document.documentElement.style;
  var transitionProperty = typeof docElemStyle.transition == 'string' ? 'transition' : 'WebkitTransition';
  var transformProperty = typeof docElemStyle.transform == 'string' ? 'transform' : 'WebkitTransform';
  var transitionEndEvent = {
    WebkitTransition: 'webkitTransitionEnd',
    transition: 'transitionend'
  }[transitionProperty];

  // cache all vendor properties that could have vendor prefix
  var vendorProperties = {
    transform: transformProperty,
    transition: transitionProperty,
    transitionDuration: transitionProperty + 'Duration',
    transitionProperty: transitionProperty + 'Property',
    transitionDelay: transitionProperty + 'Delay'
  };

  // -------------------------- Item -------------------------- //

  function Item(element, layout) {
    if (!element) {
      return;
    }
    this.element = element;
    // parent layout class, i.e. Masonry, Enviratope, or Packery
    this.layout = layout;
    this.position = {
      x: 0,
      y: 0
    };
    this._create();
  }

  // inherit EvEmitter
  var proto = Item.prototype = Object.create(EvEmitter.prototype);
  proto.constructor = Item;
  proto._create = function () {
    // transition objects
    this._transn = {
      ingProperties: {},
      clean: {},
      onEnd: {}
    };
    this.css({
      position: 'absolute'
    });
  };

  // trigger specified handler for event type
  proto.handleEvent = function (event) {
    var method = 'on' + event.type;
    if (this[method]) {
      this[method](event);
    }
  };
  proto.getSize = function () {
    this.size = getSize(this.element);
  };

  /**
   * apply CSS styles to element
   * @param {Object} style
   */
  proto.css = function (style) {
    var elemStyle = this.element.style;
    for (var prop in style) {
      // use vendor property if available
      var supportedProp = vendorProperties[prop] || prop;
      elemStyle[supportedProp] = style[prop];
    }
  };

  // measure position, and sets it
  proto.getPosition = function () {
    var style = getComputedStyle(this.element);
    var isOriginLeft = this.layout._getOption('originLeft');
    var isOriginTop = this.layout._getOption('originTop');
    var xValue = style[isOriginLeft ? 'left' : 'right'];
    var yValue = style[isOriginTop ? 'top' : 'bottom'];
    // convert percent to pixels
    var layoutSize = this.layout.size;
    var x = xValue.indexOf('%') != -1 ? parseFloat(xValue) / 100 * layoutSize.width : parseInt(xValue, 10);
    var y = yValue.indexOf('%') != -1 ? parseFloat(yValue) / 100 * layoutSize.height : parseInt(yValue, 10);

    // clean up 'auto' or other non-integer values
    x = isNaN(x) ? 0 : x;
    y = isNaN(y) ? 0 : y;
    // remove padding from measurement
    x -= isOriginLeft ? layoutSize.paddingLeft : layoutSize.paddingRight;
    y -= isOriginTop ? layoutSize.paddingTop : layoutSize.paddingBottom;
    this.position.x = x;
    this.position.y = y;
  };

  // set settled position, apply padding
  proto.layoutPosition = function () {
    var layoutSize = this.layout.size;
    var style = {};
    var isOriginLeft = this.layout._getOption('originLeft');
    var isOriginTop = this.layout._getOption('originTop');

    // x
    var xPadding = isOriginLeft ? 'paddingLeft' : 'paddingRight';
    var xProperty = isOriginLeft ? 'left' : 'right';
    var xResetProperty = isOriginLeft ? 'right' : 'left';
    var x = this.position.x + layoutSize[xPadding];
    // set in percentage or pixels
    style[xProperty] = this.getXValue(x);
    // reset other property
    style[xResetProperty] = '';

    // y
    var yPadding = isOriginTop ? 'paddingTop' : 'paddingBottom';
    var yProperty = isOriginTop ? 'top' : 'bottom';
    var yResetProperty = isOriginTop ? 'bottom' : 'top';
    var y = this.position.y + layoutSize[yPadding];
    // set in percentage or pixels
    style[yProperty] = this.getYValue(y);
    // reset other property
    style[yResetProperty] = '';
    this.css(style);
    this.emitEvent('layout', [this]);
  };
  proto.getXValue = function (x) {
    var isHorizontal = this.layout._getOption('horizontal');
    return this.layout.options.percentPosition && !isHorizontal ? x / this.layout.size.width * 100 + '%' : x + 'px';
  };
  proto.getYValue = function (y) {
    var isHorizontal = this.layout._getOption('horizontal');
    return this.layout.options.percentPosition && isHorizontal ? y / this.layout.size.height * 100 + '%' : y + 'px';
  };
  proto._transitionTo = function (x, y) {
    this.getPosition();
    // get current x & y from top/left
    var curX = this.position.x;
    var curY = this.position.y;
    var compareX = parseInt(x, 10);
    var compareY = parseInt(y, 10);
    var didNotMove = compareX === this.position.x && compareY === this.position.y;

    // save end position
    this.setPosition(x, y);

    // if did not move and not transitioning, just go to layout
    if (didNotMove && !this.isTransitioning) {
      this.layoutPosition();
      return;
    }
    var transX = x - curX;
    var transY = y - curY;
    var transitionStyle = {};
    transitionStyle.transform = this.getTranslate(transX, transY);
    this.transition({
      to: transitionStyle,
      onTransitionEnd: {
        transform: this.layoutPosition
      },
      isCleaning: true
    });
  };
  proto.getTranslate = function (x, y) {
    // flip cooridinates if origin on right or bottom
    var isOriginLeft = this.layout._getOption('originLeft');
    var isOriginTop = this.layout._getOption('originTop');
    x = isOriginLeft ? x : -x;
    y = isOriginTop ? y : -y;
    return 'translate3d(' + x + 'px, ' + y + 'px, 0)';
  };

  // non transition + transform support
  proto.goTo = function (x, y) {
    this.setPosition(x, y);
    this.layoutPosition();
  };
  proto.moveTo = proto._transitionTo;
  proto.setPosition = function (x, y) {
    this.position.x = parseInt(x, 10);
    this.position.y = parseInt(y, 10);
  };

  // ----- transition ----- //

  /**
   * @param {Object} style - CSS
   * @param {Function} onTransitionEnd
   */

  // non transition, just trigger callback
  proto._nonTransition = function (args) {
    this.css(args.to);
    if (args.isCleaning) {
      this._removeStyles(args.to);
    }
    for (var prop in args.onTransitionEnd) {
      args.onTransitionEnd[prop].call(this);
    }
  };

  /**
   * proper transition
   * @param {Object} args - arguments
   *   @param {Object} to - style to transition to
   *   @param {Object} from - style to start transition from
   *   @param {Boolean} isCleaning - removes transition styles after transition
   *   @param {Function} onTransitionEnd - callback
   */
  proto.transition = function (args) {
    // redirect to nonTransition if no transition duration
    if (!parseFloat(this.layout.options.transitionDuration)) {
      this._nonTransition(args);
      return;
    }
    var _transition = this._transn;
    // keep track of onTransitionEnd callback by css property
    for (var prop in args.onTransitionEnd) {
      _transition.onEnd[prop] = args.onTransitionEnd[prop];
    }
    // keep track of properties that are transitioning
    for (prop in args.to) {
      _transition.ingProperties[prop] = true;
      // keep track of properties to clean up when transition is done
      if (args.isCleaning) {
        _transition.clean[prop] = true;
      }
    }

    // set from styles
    if (args.from) {
      this.css(args.from);
      // force redraw. http://blog.alexmaccaw.com/css-transitions
      var h = this.element.offsetHeight;
      // hack for JSHint to hush about unused var
      h = null;
    }
    // enable transition
    this.enableTransition(args.to);
    // set styles that are transitioning
    this.css(args.to);
    this.isTransitioning = true;
  };

  // dash before all cap letters, including first for
  // WebkitTransform => -webkit-transform
  function toDashedAll(str) {
    return str.replace(/([A-Z])/g, function ($1) {
      return '-' + $1.toLowerCase();
    });
  }
  var transitionProps = 'opacity,' + toDashedAll(transformProperty);
  proto.enableTransition = function /* style */
  () {
    // HACK changing transitionProperty during a transition
    // will cause transition to jump
    if (this.isTransitioning) {
      return;
    }

    // make `transition: foo, bar, baz` from style object
    // HACK un-comment this when enableTransition can work
    // while a transition is happening
    // var transitionValues = [];
    // for ( var prop in style ) {
    //   // dash-ify camelCased properties like WebkitTransition
    //   prop = vendorProperties[ prop ] || prop;
    //   transitionValues.push( toDashedAll( prop ) );
    // }
    // munge number to millisecond, to match stagger
    var duration = this.layout.options.transitionDuration;
    duration = typeof duration == 'number' ? duration + 'ms' : duration;
    // enable transition styles
    this.css({
      transitionProperty: transitionProps,
      transitionDuration: duration,
      transitionDelay: this.staggerDelay || 0
    });
    // listen for transition end event
    this.element.addEventListener(transitionEndEvent, this, false);
  };

  // ----- events ----- //

  proto.onwebkitTransitionEnd = function (event) {
    this.ontransitionend(event);
  };
  proto.onotransitionend = function (event) {
    this.ontransitionend(event);
  };

  // properties that I munge to make my life easier
  var dashedVendorProperties = {
    '-webkit-transform': 'transform'
  };
  proto.ontransitionend = function (event) {
    // disregard bubbled events from children
    if (event.target !== this.element) {
      return;
    }
    var _transition = this._transn;
    // get property name of transitioned property, convert to prefix-free
    var propertyName = dashedVendorProperties[event.propertyName] || event.propertyName;

    // remove property that has completed transitioning
    delete _transition.ingProperties[propertyName];
    // check if any properties are still transitioning
    if (isEmptyObj(_transition.ingProperties)) {
      // all properties have completed transitioning
      this.disableTransition();
    }
    // clean style
    if (propertyName in _transition.clean) {
      // clean up style
      this.element.style[event.propertyName] = '';
      delete _transition.clean[propertyName];
    }
    // trigger onTransitionEnd callback
    if (propertyName in _transition.onEnd) {
      var onTransitionEnd = _transition.onEnd[propertyName];
      onTransitionEnd.call(this);
      delete _transition.onEnd[propertyName];
    }
    this.emitEvent('transitionEnd', [this]);
  };
  proto.disableTransition = function () {
    this.removeTransitionStyles();
    this.element.removeEventListener(transitionEndEvent, this, false);
    this.isTransitioning = false;
  };

  /**
   * removes style property from element
   * @param {Object} style
  **/
  proto._removeStyles = function (style) {
    // clean up transition styles
    var cleanStyle = {};
    for (var prop in style) {
      cleanStyle[prop] = '';
    }
    this.css(cleanStyle);
  };
  var cleanTransitionStyle = {
    transitionProperty: '',
    transitionDuration: '',
    transitionDelay: ''
  };
  proto.removeTransitionStyles = function () {
    // remove transition
    this.css(cleanTransitionStyle);
  };

  // ----- stagger ----- //

  proto.stagger = function (delay) {
    delay = isNaN(delay) ? 0 : delay;
    this.staggerDelay = delay + 'ms';
  };

  // ----- show/hide/remove ----- //

  // remove element from DOM
  proto.removeElem = function () {
    this.element.parentNode.removeChild(this.element);
    // remove display: none
    this.css({
      display: ''
    });
    this.emitEvent('remove', [this]);
  };
  proto.remove = function () {
    // just remove element if no transition support or no transition
    if (!transitionProperty || !parseFloat(this.layout.options.transitionDuration)) {
      this.removeElem();
      return;
    }

    // start transition
    this.once('transitionEnd', function () {
      this.removeElem();
    });
    this.hide();
  };
  proto.reveal = function () {
    delete this.isHidden;
    // remove display: none
    this.css({
      display: ''
    });
    var options = this.layout.options;
    var onTransitionEnd = {};
    var transitionEndProperty = this.getHideRevealTransitionEndProperty('visibleStyle');
    onTransitionEnd[transitionEndProperty] = this.onRevealTransitionEnd;
    this.transition({
      from: options.hiddenStyle,
      to: options.visibleStyle,
      isCleaning: true,
      onTransitionEnd: onTransitionEnd
    });
  };
  proto.onRevealTransitionEnd = function () {
    // check if still visible
    // during transition, item may have been hidden
    if (!this.isHidden) {
      this.emitEvent('reveal');
    }
  };

  /**
   * get style property use for hide/reveal transition end
   * @param {String} styleProperty - hiddenStyle/visibleStyle
   * @returns {String}
   */
  proto.getHideRevealTransitionEndProperty = function (styleProperty) {
    var optionStyle = this.layout.options[styleProperty];
    // use opacity
    if (optionStyle.opacity) {
      return 'opacity';
    }
    // get first property
    for (var prop in optionStyle) {
      return prop;
    }
  };
  proto.hide = function () {
    // set flag
    this.isHidden = true;
    // remove display: none
    this.css({
      display: ''
    });
    var options = this.layout.options;
    var onTransitionEnd = {};
    var transitionEndProperty = this.getHideRevealTransitionEndProperty('hiddenStyle');
    onTransitionEnd[transitionEndProperty] = this.onHideTransitionEnd;
    this.transition({
      from: options.visibleStyle,
      to: options.hiddenStyle,
      // keep hidden stuff hidden
      isCleaning: true,
      onTransitionEnd: onTransitionEnd
    });
  };
  proto.onHideTransitionEnd = function () {
    // check if still hidden
    // during transition, item may have been un-hidden
    if (this.isHidden) {
      this.css({
        display: 'none'
      });
      this.emitEvent('hide');
    }
  };
  proto.destroy = function () {
    this.css({
      position: '',
      left: '',
      right: '',
      top: '',
      bottom: '',
      transition: '',
      transform: ''
    });
  };
  return Item;
});

/*!
 * Outlayer v2.1.0
 * the brains and guts of a layout library
 * MIT license
 */

(function (window, factory) {
  'use strict';

  // universal module definition
  /* jshint strict: false */ /* globals define, module, require */
  if (true) {
    // AMD - RequireJS
    !(__WEBPACK_AMD_DEFINE_ARRAY__ = [__WEBPACK_LOCAL_MODULE_1__, __WEBPACK_LOCAL_MODULE_2__, __WEBPACK_LOCAL_MODULE_4__, __WEBPACK_LOCAL_MODULE_5__], __WEBPACK_LOCAL_MODULE_6__ = (function (EvEmitter, getSize, utils, Item) {
      return factory(window, EvEmitter, getSize, utils, Item);
    }).apply(__WEBPACK_LOCAL_MODULE_6__exports = {}, __WEBPACK_AMD_DEFINE_ARRAY__), __WEBPACK_LOCAL_MODULE_6__ === undefined && (__WEBPACK_LOCAL_MODULE_6__ = __WEBPACK_LOCAL_MODULE_6__exports));
  } else {}
})(window, function factory(window, EvEmitter, getSize, utils, Item) {
  'use strict';

  // ----- vars ----- //
  var console = window.console;
  var jQuery = window.jQuery;
  var noop = function noop() {};

  // -------------------------- Outlayer -------------------------- //

  // globally unique identifiers
  var GUID = 0;
  // internal store of all Outlayer intances
  var instances = {};

  /**
   * @param {Element, String} element
   * @param {Object} options
   * @constructor
   */
  function Outlayer(element, options) {
    var queryElement = utils.getQueryElement(element);
    if (!queryElement) {
      if (console) {
        console.error('Bad element for ' + this.constructor.namespace + ': ' + (queryElement || element));
      }
      return;
    }
    this.element = queryElement;
    // add jQuery
    if (jQuery) {
      this.$element = jQuery(this.element);
    }

    // options
    this.options = utils.extend({}, this.constructor.defaults);
    this.option(options);

    // add id for Outlayer.getFromElement
    var id = ++GUID;
    this.element.outlayerGUID = id; // expando
    instances[id] = this; // associate via id

    // kick it off
    this._create();
    var isInitLayout = this._getOption('initLayout');
    if (isInitLayout) {
      this.layout();
    }
  }

  // settings are for internal use only
  Outlayer.namespace = 'outlayer';
  Outlayer.Item = Item;

  // default options
  Outlayer.defaults = {
    containerStyle: {
      position: 'relative'
    },
    initLayout: true,
    originLeft: true,
    originTop: true,
    resize: true,
    resizeContainer: true,
    // item options
    transitionDuration: '0.4s',
    hiddenStyle: {
      opacity: 0,
      transform: 'scale(0.001)'
    },
    visibleStyle: {
      opacity: 1,
      transform: 'scale(1)'
    }
  };
  var proto = Outlayer.prototype;
  // inherit EvEmitter
  utils.extend(proto, EvEmitter.prototype);

  /**
   * set options
   * @param {Object} opts
   */
  proto.option = function (opts) {
    utils.extend(this.options, opts);
  };

  /**
   * get backwards compatible option value, check old name
   */
  proto._getOption = function (option) {
    var oldOption = this.constructor.compatOptions[option];
    return oldOption && this.options[oldOption] !== undefined ? this.options[oldOption] : this.options[option];
  };
  Outlayer.compatOptions = {
    // currentName: oldName
    initLayout: 'isInitLayout',
    horizontal: 'isHorizontal',
    layoutInstant: 'isLayoutInstant',
    originLeft: 'isOriginLeft',
    originTop: 'isOriginTop',
    resize: 'isResizeBound',
    resizeContainer: 'isResizingContainer'
  };
  proto._create = function () {
    // get items from children
    this.reloadItems();
    // elements that affect layout, but are not laid out
    this.stamps = [];
    this.stamp(this.options.stamp);
    // set container style
    utils.extend(this.element.style, this.options.containerStyle);

    // bind resize method
    var canBindResize = this._getOption('resize');
    if (canBindResize) {
      this.bindResize();
    }
  };

  // goes through all children again and gets bricks in proper order
  proto.reloadItems = function () {
    // collection of item elements
    this.items = this._itemize(this.element.children);
  };

  /**
   * turn elements into Outlayer.Items to be used in layout
   * @param {Array or NodeList or HTMLElement} elems
   * @returns {Array} items - collection of new Outlayer Items
   */
  proto._itemize = function (elems) {
    var itemElems = this._filterFindItemElements(elems);
    var Item = this.constructor.Item;

    // create new Outlayer Items for collection
    var items = [];
    for (var i = 0; i < itemElems.length; i++) {
      var elem = itemElems[i];
      var item = new Item(elem, this);
      items.push(item);
    }
    return items;
  };

  /**
   * get item elements to be used in layout
   * @param {Array or NodeList or HTMLElement} elems
   * @returns {Array} items - item elements
   */
  proto._filterFindItemElements = function (elems) {
    return utils.filterFindElements(elems, this.options.itemSelector);
  };

  /**
   * getter method for getting item elements
   * @returns {Array} elems - collection of item elements
   */
  proto.getItemElements = function () {
    return this.items.map(function (item) {
      return item.element;
    });
  };

  // ----- init & layout ----- //

  /**
   * lays out all items
   */
  proto.layout = function () {
    this._resetLayout();
    this._manageStamps();

    // don't animate first layout
    var layoutInstant = this._getOption('layoutInstant');
    var isInstant = layoutInstant !== undefined ? layoutInstant : !this._isLayoutInited;
    this.layoutItems(this.items, isInstant);

    // flag for initalized
    this._isLayoutInited = true;
  };

  // _init is alias for layout
  proto._init = proto.layout;

  /**
   * logic before any new layout
   */
  proto._resetLayout = function () {
    this.getSize();
  };
  proto.getSize = function () {
    this.size = getSize(this.element);
  };

  /**
   * get measurement from option, for columnWidth, rowHeight, gutter
   * if option is String -> get element from selector string, & get size of element
   * if option is Element -> get size of element
   * else use option as a number
   *
   * @param {String} measurement
   * @param {String} size - width or height
   * @private
   */
  proto._getMeasurement = function (measurement, size) {
    var option = this.options[measurement];
    var elem;
    if (!option) {
      // default to 0
      this[measurement] = 0;
    } else {
      // use option as an element
      if (typeof option == 'string') {
        elem = this.element.querySelector(option);
      } else if (option instanceof HTMLElement) {
        elem = option;
      }
      // use size of element, if element
      this[measurement] = elem ? getSize(elem)[size] : option;
    }
  };

  /**
   * layout a collection of item elements
   * @api public
   */
  proto.layoutItems = function (items, isInstant) {
    items = this._getItemsForLayout(items);
    this._layoutItems(items, isInstant);
    this._postLayout();
  };

  /**
   * get the items to be laid out
   * you may want to skip over some items
   * @param {Array} items
   * @returns {Array} items
   */
  proto._getItemsForLayout = function (items) {
    return items.filter(function (item) {
      return !item.isIgnored;
    });
  };

  /**
   * layout items
   * @param {Array} items
   * @param {Boolean} isInstant
   */
  proto._layoutItems = function (items, isInstant) {
    this._emitCompleteOnItems('layout', items);
    if (!items || !items.length) {
      // no items, emit event with empty array
      return;
    }
    var queue = [];
    items.forEach(function (item) {
      // get x/y object from method
      var position = this._getItemLayoutPosition(item);
      // enqueue
      position.item = item;
      position.isInstant = isInstant || item.isLayoutInstant;
      queue.push(position);
    }, this);
    this._processLayoutQueue(queue);
  };

  /**
   * get item layout position
   * @param {Outlayer.Item} item
   * @returns {Object} x and y position
   */
  proto._getItemLayoutPosition = function /* item */
  () {
    return {
      x: 0,
      y: 0
    };
  };

  /**
   * iterate over array and position each item
   * Reason being - separating this logic prevents 'layout invalidation'
   * thx @paul_irish
   * @param {Array} queue
   */
  proto._processLayoutQueue = function (queue) {
    this.updateStagger();
    queue.forEach(function (obj, i) {
      this._positionItem(obj.item, obj.x, obj.y, obj.isInstant, i);
    }, this);
  };

  // set stagger from option in milliseconds number
  proto.updateStagger = function () {
    var stagger = this.options.stagger;
    if (stagger === null || stagger === undefined) {
      this.stagger = 0;
      return;
    }
    this.stagger = getMilliseconds(stagger);
    return this.stagger;
  };

  /**
   * Sets position of item in DOM
   * @param {Outlayer.Item} item
   * @param {Number} x - horizontal position
   * @param {Number} y - vertical position
   * @param {Boolean} isInstant - disables transitions
   */
  proto._positionItem = function (item, x, y, isInstant, i) {
    if (isInstant) {
      // if not transition, just set CSS
      item.goTo(x, y);
    } else {
      item.stagger(i * this.stagger);
      item.moveTo(x, y);
    }
  };

  /**
   * Any logic you want to do after each layout,
   * i.e. size the container
   */
  proto._postLayout = function () {
    this.resizeContainer();
  };
  proto.resizeContainer = function () {
    var isResizingContainer = this._getOption('resizeContainer');
    if (!isResizingContainer) {
      return;
    }
    var size = this._getContainerSize();
    if (size) {
      this._setContainerMeasure(size.width, true);
      this._setContainerMeasure(size.height, false);
    }
  };

  /**
   * Sets width or height of container if returned
   * @returns {Object} size
   *   @param {Number} width
   *   @param {Number} height
   */
  proto._getContainerSize = noop;

  /**
   * @param {Number} measure - size of width or height
   * @param {Boolean} isWidth
   */
  proto._setContainerMeasure = function (measure, isWidth) {
    if (measure === undefined) {
      return;
    }
    var elemSize = this.size;
    // add padding and border width if border box
    if (elemSize.isBorderBox) {
      measure += isWidth ? elemSize.paddingLeft + elemSize.paddingRight + elemSize.borderLeftWidth + elemSize.borderRightWidth : elemSize.paddingBottom + elemSize.paddingTop + elemSize.borderTopWidth + elemSize.borderBottomWidth;
    }
    measure = Math.max(measure, 0);
    this.element.style[isWidth ? 'width' : 'height'] = measure + 'px';
  };

  /**
   * emit eventComplete on a collection of items events
   * @param {String} eventName
   * @param {Array} items - Outlayer.Items
   */
  proto._emitCompleteOnItems = function (eventName, items) {
    var _this = this;
    function onComplete() {
      _this.dispatchEvent(eventName + 'Complete', null, [items]);
    }
    var count = items.length;
    if (!items || !count) {
      onComplete();
      return;
    }
    var doneCount = 0;
    function tick() {
      doneCount++;
      if (doneCount == count) {
        onComplete();
      }
    }

    // bind callback
    items.forEach(function (item) {
      item.once(eventName, tick);
    });
  };

  /**
   * emits events via EvEmitter and jQuery events
   * @param {String} type - name of event
   * @param {Event} event - original event
   * @param {Array} args - extra arguments
   */
  proto.dispatchEvent = function (type, event, args) {
    // add original event to arguments
    var emitArgs = event ? [event].concat(args) : args;
    this.emitEvent(type, emitArgs);
    if (jQuery) {
      // set this.$element
      this.$element = this.$element || jQuery(this.element);
      if (event) {
        // create jQuery event
        var $event = jQuery.Event(event);
        $event.type = type;
        this.$element.trigger($event, args);
      } else {
        // just trigger with type if no event available
        this.$element.trigger(type, args);
      }
    }
  };

  // -------------------------- ignore & stamps -------------------------- //

  /**
   * keep item in collection, but do not lay it out
   * ignored items do not get skipped in layout
   * @param {Element} elem
   */
  proto.ignore = function (elem) {
    var item = this.getItem(elem);
    if (item) {
      item.isIgnored = true;
    }
  };

  /**
   * return item to layout collection
   * @param {Element} elem
   */
  proto.unignore = function (elem) {
    var item = this.getItem(elem);
    if (item) {
      delete item.isIgnored;
    }
  };

  /**
   * adds elements to stamps
   * @param {NodeList, Array, Element, or String} elems
   */
  proto.stamp = function (elems) {
    elems = this._find(elems);
    if (!elems) {
      return;
    }
    this.stamps = this.stamps.concat(elems);
    // ignore
    elems.forEach(this.ignore, this);
  };

  /**
   * removes elements to stamps
   * @param {NodeList, Array, or Element} elems
   */
  proto.unstamp = function (elems) {
    elems = this._find(elems);
    if (!elems) {
      return;
    }
    elems.forEach(function (elem) {
      // filter out removed stamp elements
      utils.removeFrom(this.stamps, elem);
      this.unignore(elem);
    }, this);
  };

  /**
   * finds child elements
   * @param {NodeList, Array, Element, or String} elems
   * @returns {Array} elems
   */
  proto._find = function (elems) {
    if (!elems) {
      return;
    }
    // if string, use argument as selector string
    if (typeof elems == 'string') {
      elems = this.element.querySelectorAll(elems);
    }
    elems = utils.makeArray(elems);
    return elems;
  };
  proto._manageStamps = function () {
    if (!this.stamps || !this.stamps.length) {
      return;
    }
    this._getBoundingRect();
    this.stamps.forEach(this._manageStamp, this);
  };

  // update boundingLeft / Top
  proto._getBoundingRect = function () {
    // get bounding rect for container element
    var boundingRect = this.element.getBoundingClientRect();
    var size = this.size;
    this._boundingRect = {
      left: boundingRect.left + size.paddingLeft + size.borderLeftWidth,
      top: boundingRect.top + size.paddingTop + size.borderTopWidth,
      right: boundingRect.right - (size.paddingRight + size.borderRightWidth),
      bottom: boundingRect.bottom - (size.paddingBottom + size.borderBottomWidth)
    };
  };

  /**
   * @param {Element} stamp
  **/
  proto._manageStamp = noop;

  /**
   * get x/y position of element relative to container element
   * @param {Element} elem
   * @returns {Object} offset - has left, top, right, bottom
   */
  proto._getElementOffset = function (elem) {
    var boundingRect = elem.getBoundingClientRect();
    var thisRect = this._boundingRect;
    var size = getSize(elem);
    var offset = {
      left: boundingRect.left - thisRect.left - size.marginLeft,
      top: boundingRect.top - thisRect.top - size.marginTop,
      right: thisRect.right - boundingRect.right - size.marginRight,
      bottom: thisRect.bottom - boundingRect.bottom - size.marginBottom
    };
    return offset;
  };

  // -------------------------- resize -------------------------- //

  // enable event handlers for listeners
  // i.e. resize -> onresize
  proto.handleEvent = utils.handleEvent;

  /**
   * Bind layout to window resizing
   */
  proto.bindResize = function () {
    window.addEventListener('resize', this);
    this.isResizeBound = true;
  };

  /**
   * Unbind layout to window resizing
   */
  proto.unbindResize = function () {
    window.removeEventListener('resize', this);
    this.isResizeBound = false;
  };
  proto.onresize = function () {
    this.resize();
  };
  utils.debounceMethod(Outlayer, 'onresize', 100);
  proto.resize = function () {
    // don't trigger if size did not change
    // or if resize was unbound. See #9
    if (!this.isResizeBound || !this.needsResizeLayout()) {
      return;
    }
    this.layout();
  };

  /**
   * check if layout is needed post layout
   * @returns Boolean
   */
  proto.needsResizeLayout = function () {
    var size = getSize(this.element);
    // check that this.size and size are there
    // IE8 triggers resize on body size change, so they might not be
    var hasSizes = this.size && size;
    return hasSizes && size.innerWidth !== this.size.innerWidth;
  };

  // -------------------------- methods -------------------------- //

  /**
   * add items to Outlayer instance
   * @param {Array or NodeList or Element} elems
   * @returns {Array} items - Outlayer.Items
  **/
  proto.addItems = function (elems) {
    var items = this._itemize(elems);
    // add items to collection
    if (items.length) {
      this.items = this.items.concat(items);
    }
    return items;
  };

  /**
   * Layout newly-appended item elements
   * @param {Array or NodeList or Element} elems
   */
  proto.appended = function (elems) {
    var items = this.addItems(elems);
    if (!items.length) {
      return;
    }
    // layout and reveal just the new items
    this.layoutItems(items, true);
    this.reveal(items);
  };

  /**
   * Layout prepended elements
   * @param {Array or NodeList or Element} elems
   */
  proto.prepended = function (elems) {
    var items = this._itemize(elems);
    if (!items.length) {
      return;
    }
    // add items to beginning of collection
    var previousItems = this.items.slice(0);
    this.items = items.concat(previousItems);
    // start new layout
    this._resetLayout();
    this._manageStamps();
    // layout new stuff without transition
    this.layoutItems(items, true);
    this.reveal(items);
    // layout previous items
    this.layoutItems(previousItems);
  };

  /**
   * reveal a collection of items
   * @param {Array of Outlayer.Items} items
   */
  proto.reveal = function (items) {
    this._emitCompleteOnItems('reveal', items);
    if (!items || !items.length) {
      return;
    }
    var stagger = this.updateStagger();
    items.forEach(function (item, i) {
      item.stagger(i * stagger);
      item.reveal();
    });
  };

  /**
   * hide a collection of items
   * @param {Array of Outlayer.Items} items
   */
  proto.hide = function (items) {
    this._emitCompleteOnItems('hide', items);
    if (!items || !items.length) {
      return;
    }
    var stagger = this.updateStagger();
    items.forEach(function (item, i) {
      item.stagger(i * stagger);
      item.hide();
    });
  };

  /**
   * reveal item elements
   * @param {Array}, {Element}, {NodeList} items
   */
  proto.revealItemElements = function (elems) {
    var items = this.getItems(elems);
    this.reveal(items);
  };

  /**
   * hide item elements
   * @param {Array}, {Element}, {NodeList} items
   */
  proto.hideItemElements = function (elems) {
    var items = this.getItems(elems);
    this.hide(items);
  };

  /**
   * get Outlayer.Item, given an Element
   * @param {Element} elem
   * @param {Function} callback
   * @returns {Outlayer.Item} item
   */
  proto.getItem = function (elem) {
    // loop through items to get the one that matches
    for (var i = 0; i < this.items.length; i++) {
      var item = this.items[i];
      if (item.element == elem) {
        // return item
        return item;
      }
    }
  };

  /**
   * get collection of Outlayer.Items, given Elements
   * @param {Array} elems
   * @returns {Array} items - Outlayer.Items
   */
  proto.getItems = function (elems) {
    elems = utils.makeArray(elems);
    var items = [];
    elems.forEach(function (elem) {
      var item = this.getItem(elem);
      if (item) {
        items.push(item);
      }
    }, this);
    return items;
  };

  /**
   * remove element(s) from instance and DOM
   * @param {Array or NodeList or Element} elems
   */
  proto.remove = function (elems) {
    var removeItems = this.getItems(elems);
    this._emitCompleteOnItems('remove', removeItems);

    // bail if no items to remove
    if (!removeItems || !removeItems.length) {
      return;
    }
    removeItems.forEach(function (item) {
      item.remove();
      // remove item from collection
      utils.removeFrom(this.items, item);
    }, this);
  };

  // ----- destroy ----- //

  // remove and disable Outlayer instance
  proto.destroy = function () {
    // clean up dynamic styles
    var style = this.element.style;
    style.height = '';
    style.position = '';
    style.width = '';
    // destroy items
    this.items.forEach(function (item) {
      item.destroy();
    });
    this.unbindResize();
    var id = this.element.outlayerGUID;
    delete instances[id]; // remove reference to instance by id
    delete this.element.outlayerGUID;
    // remove data for jQuery
    if (jQuery) {
      jQuery.removeData(this.element, this.constructor.namespace);
    }
  };

  // -------------------------- data -------------------------- //

  /**
   * get Outlayer instance from element
   * @param {Element} elem
   * @returns {Outlayer}
   */
  Outlayer.data = function (elem) {
    elem = utils.getQueryElement(elem);
    var id = elem && elem.outlayerGUID;
    return id && instances[id];
  };

  // -------------------------- create Outlayer class -------------------------- //

  /**
   * create a layout class
   * @param {String} namespace
   */
  Outlayer.create = function (namespace, options) {
    // sub-class Outlayer
    var Layout = subclass(Outlayer);
    // apply new options and compatOptions
    Layout.defaults = utils.extend({}, Outlayer.defaults);
    utils.extend(Layout.defaults, options);
    Layout.compatOptions = utils.extend({}, Outlayer.compatOptions);
    Layout.namespace = namespace;
    Layout.data = Outlayer.data;

    // sub-class Item
    Layout.Item = subclass(Item);

    // -------------------------- declarative -------------------------- //

    utils.htmlInit(Layout, namespace);

    // -------------------------- jQuery bridge -------------------------- //

    // make into jQuery plugin
    if (jQuery && jQuery.bridget) {
      jQuery.bridget(namespace, Layout);
    }
    return Layout;
  };
  function subclass(Parent) {
    function SubClass() {
      Parent.apply(this, arguments);
    }
    SubClass.prototype = Object.create(Parent.prototype);
    SubClass.prototype.constructor = SubClass;
    return SubClass;
  }

  // ----- helpers ----- //

  // how many milliseconds are in each unit
  var msUnits = {
    ms: 1,
    s: 1000
  };

  // munge time-like parameter into millisecond number
  // '0.4s' -> 40
  function getMilliseconds(time) {
    if (typeof time == 'number') {
      return time;
    }
    var matches = time.match(/(^\d*\.?\d*)(\w*)/);
    var num = matches && matches[1];
    var unit = matches && matches[2];
    if (!num.length) {
      return 0;
    }
    num = parseFloat(num);
    var mult = msUnits[unit] || 1;
    return num * mult;
  }

  // ----- fin ----- //

  // back in global
  Outlayer.Item = Item;
  return Outlayer;
});

/**
 * Enviratope Item
**/

(function (window, factory) {
  // universal module definition
  /* jshint strict: false */ /*globals define, module, require */
  if (true) {
    // AMD
    !(__WEBPACK_AMD_DEFINE_ARRAY__ = [__WEBPACK_LOCAL_MODULE_6__], __WEBPACK_AMD_DEFINE_FACTORY__ = (factory),
		__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
		(__WEBPACK_AMD_DEFINE_FACTORY__.apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__)) : __WEBPACK_AMD_DEFINE_FACTORY__),
		__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
  } else {}
})(window, function factory(Outlayer) {
  'use strict';

  // -------------------------- Item -------------------------- //

  // sub-class Outlayer Item
  function Item() {
    Outlayer.Item.apply(this, arguments);
  }
  var proto = Item.prototype = Object.create(Outlayer.Item.prototype);
  var _create = proto._create;
  proto._create = function () {
    // assign id, used for original-order sorting
    this.id = this.layout.itemGUID++;
    _create.call(this);
    this.sortData = {};
  };
  proto.updateSortData = function () {
    if (this.isIgnored) {
      return;
    }
    // default sorters
    this.sortData.id = this.id;
    // for backward compatibility
    this.sortData['original-order'] = this.id;
    this.sortData.random = Math.random();
    // go thru getSortData obj and apply the sorters
    var getSortData = this.layout.options.getSortData;
    var sorters = this.layout._sorters;
    for (var key in getSortData) {
      var sorter = sorters[key];
      this.sortData[key] = sorter(this.element, this);
    }
  };
  var _destroy = proto.destroy;
  proto.destroy = function () {
    // call super
    _destroy.apply(this, arguments);
    // reset display, #741
    this.css({
      display: ''
    });
  };
  return Item;
});

/**
 * Enviratope LayoutMode
 */

(function (window, factory) {
  // universal module definition
  /* jshint strict: false */ /*globals define, module, require */
  if (true) {
    // AMD
    !(__WEBPACK_LOCAL_MODULE_8__array = [__WEBPACK_LOCAL_MODULE_2__, __WEBPACK_LOCAL_MODULE_6__], __WEBPACK_LOCAL_MODULE_8__factory = (factory),
		(typeof __WEBPACK_LOCAL_MODULE_8__factory === 'function' ?
			((__WEBPACK_LOCAL_MODULE_8__ = __WEBPACK_LOCAL_MODULE_8__factory.apply(__WEBPACK_LOCAL_MODULE_8__exports = {}, __WEBPACK_LOCAL_MODULE_8__array)), __WEBPACK_LOCAL_MODULE_8__ === undefined && (__WEBPACK_LOCAL_MODULE_8__ = __WEBPACK_LOCAL_MODULE_8__exports)) :
			(__WEBPACK_LOCAL_MODULE_8__ = __WEBPACK_LOCAL_MODULE_8__factory)
		));
  } else {}
})(window, function factory(getSize, Outlayer) {
  'use strict';

  // layout mode class
  function LayoutMode(enviratope) {
    this.enviratope = enviratope;
    // link properties
    if (enviratope) {
      this.options = enviratope.options[this.namespace];
      this.element = enviratope.element;
      this.items = enviratope.filteredItems;
      this.size = enviratope.size;
    }
  }
  var proto = LayoutMode.prototype;

  /**
   * some methods should just defer to default Outlayer method
   * and reference the Enviratope instance as `this`
  **/
  var facadeMethods = ['_resetLayout', '_getItemLayoutPosition', '_manageStamp', '_getContainerSize', '_getElementOffset', 'needsResizeLayout', '_getOption'];
  facadeMethods.forEach(function (methodName) {
    proto[methodName] = function () {
      return Outlayer.prototype[methodName].apply(this.enviratope, arguments);
    };
  });

  // -----  ----- //

  // for horizontal layout modes, check vertical size
  proto.needsVerticalResizeLayout = function () {
    // don't trigger if size did not change
    var size = getSize(this.enviratope.element);
    // check that this.size and size are there
    // IE8 triggers resize on body size change, so they might not be
    var hasSizes = this.enviratope.size && size;
    return hasSizes && size.innerHeight != this.enviratope.size.innerHeight;
  };

  // ----- measurements ----- //

  proto._getMeasurement = function () {
    this.enviratope._getMeasurement.apply(this, arguments);
  };
  proto.getColumnWidth = function () {
    this.getSegmentSize('column', 'Width');
  };
  proto.getRowHeight = function () {
    this.getSegmentSize('row', 'Height');
  };

  /**
   * get columnWidth or rowHeight
   * segment: 'column' or 'row'
   * size 'Width' or 'Height'
  **/
  proto.getSegmentSize = function (segment, size) {
    var segmentName = segment + size;
    var outerSize = 'outer' + size;
    // columnWidth / outerWidth // rowHeight / outerHeight
    this._getMeasurement(segmentName, outerSize);
    // got rowHeight or columnWidth, we can chill
    if (this[segmentName]) {
      return;
    }
    // fall back to item of first element
    var firstItemSize = this.getFirstItemSize();
    this[segmentName] = firstItemSize && firstItemSize[outerSize] ||
    // or size of container
    this.enviratope.size['inner' + size];
  };
  proto.getFirstItemSize = function () {
    var firstItem = this.enviratope.filteredItems[0];
    return firstItem && firstItem.element && getSize(firstItem.element);
  };

  // ----- methods that should reference enviratope ----- //

  proto.layout = function () {
    this.enviratope.layout.apply(this.enviratope, arguments);
  };
  proto.getSize = function () {
    this.enviratope.getSize();
    this.size = this.enviratope.size;
  };

  // -------------------------- create -------------------------- //

  LayoutMode.modes = {};
  LayoutMode.create = function (namespace, options) {
    function Mode() {
      LayoutMode.apply(this, arguments);
    }
    Mode.prototype = Object.create(proto);
    Mode.prototype.constructor = Mode;

    // default options
    if (options) {
      Mode.options = options;
    }
    Mode.prototype.namespace = namespace;
    // register in Enviratope
    LayoutMode.modes[namespace] = Mode;
    return Mode;
  };
  return LayoutMode;
});

/*!
 * Masonry v4.1.0
 * Cascading grid layout library
 * http://masonry.desandro.com
 * MIT License
 * by David DeSandro
 */

(function (window, factory) {
  // universal module definition
  /* jshint strict: false */ /*globals define, module, require */
  if (true) {
    // AMD
    !(__WEBPACK_LOCAL_MODULE_9__array = [__WEBPACK_LOCAL_MODULE_6__, __WEBPACK_LOCAL_MODULE_2__], __WEBPACK_LOCAL_MODULE_9__factory = (factory),
		(typeof __WEBPACK_LOCAL_MODULE_9__factory === 'function' ?
			((__WEBPACK_LOCAL_MODULE_9__ = __WEBPACK_LOCAL_MODULE_9__factory.apply(__WEBPACK_LOCAL_MODULE_9__exports = {}, __WEBPACK_LOCAL_MODULE_9__array)), __WEBPACK_LOCAL_MODULE_9__ === undefined && (__WEBPACK_LOCAL_MODULE_9__ = __WEBPACK_LOCAL_MODULE_9__exports)) :
			(__WEBPACK_LOCAL_MODULE_9__ = __WEBPACK_LOCAL_MODULE_9__factory)
		));
  } else {}
})(window, function factory(Outlayer, getSize) {
  // -------------------------- masonryDefinition -------------------------- //

  // create an Outlayer layout class
  var Masonry = Outlayer.create('masonry');
  // isFitWidth -> fitWidth
  Masonry.compatOptions.fitWidth = 'isFitWidth';
  Masonry.prototype._resetLayout = function () {
    this.getSize();
    this._getMeasurement('columnWidth', 'outerWidth');
    this._getMeasurement('gutter', 'outerWidth');
    this.measureColumns();

    // reset column Y
    this.colYs = [];
    for (var i = 0; i < this.cols; i++) {
      this.colYs.push(0);
    }
    this.maxY = 0;
  };
  Masonry.prototype.measureColumns = function () {
    this.getContainerWidth();
    // if columnWidth is 0, default to outerWidth of first item
    if (!this.columnWidth) {
      var firstItem = this.items[0];
      var firstItemElem = firstItem && firstItem.element;
      // columnWidth fall back to item of first element
      this.columnWidth = firstItemElem && getSize(firstItemElem).outerWidth ||
      // if first elem has no width, default to size of container
      this.containerWidth;
    }
    var columnWidth = this.columnWidth += this.gutter;

    // calculate columns
    var containerWidth = this.containerWidth + this.gutter;
    var cols = containerWidth / columnWidth;
    // fix rounding errors, typically with gutters
    var excess = columnWidth - containerWidth % columnWidth;
    // if overshoot is less than a pixel, round up, otherwise floor it
    var mathMethod = excess && excess < 1 ? 'round' : 'floor';
    cols = Math[mathMethod](cols);
    this.cols = Math.max(cols, 1);
  };
  Masonry.prototype.getContainerWidth = function () {
    // container is parent if fit width
    var isFitWidth = this._getOption('fitWidth');
    var container = isFitWidth ? this.element.parentNode : this.element;
    // check that this.size and size are there
    // IE8 triggers resize on body size change, so they might not be
    var size = getSize(container);
    this.containerWidth = size && size.innerWidth;
  };
  Masonry.prototype._getItemLayoutPosition = function (item) {
    item.getSize();
    // how many columns does this brick span
    var remainder = item.size.outerWidth % this.columnWidth;
    var mathMethod = remainder && remainder < 1 ? 'round' : 'ceil';
    // round if off by 1 pixel, otherwise use ceil
    var colSpan = Math[mathMethod](item.size.outerWidth / this.columnWidth);
    colSpan = Math.min(colSpan, this.cols);
    var colGroup = this._getColGroup(colSpan);
    // get the minimum Y value from the columns
    var minimumY = Math.min.apply(Math, colGroup);
    var shortColIndex = colGroup.indexOf(minimumY);

    // position the brick
    var position = {
      x: this.columnWidth * shortColIndex,
      y: minimumY
    };

    // apply setHeight to necessary columns
    var setHeight = minimumY + item.size.outerHeight;
    var setSpan = this.cols + 1 - colGroup.length;
    for (var i = 0; i < setSpan; i++) {
      this.colYs[shortColIndex + i] = setHeight;
    }
    return position;
  };

  /**
   * @param {Number} colSpan - number of columns the element spans
   * @returns {Array} colGroup
   */
  Masonry.prototype._getColGroup = function (colSpan) {
    if (colSpan < 2) {
      // if brick spans only one column, use all the column Ys
      return this.colYs;
    }
    var colGroup = [];
    // how many different places could this brick fit horizontally
    var groupCount = this.cols + 1 - colSpan;
    // for each group potential horizontal position
    for (var i = 0; i < groupCount; i++) {
      // make an array of colY values for that one group
      var groupColYs = this.colYs.slice(i, i + colSpan);
      // and get the max value of the array
      colGroup[i] = Math.max.apply(Math, groupColYs);
    }
    return colGroup;
  };
  Masonry.prototype._manageStamp = function (stamp) {
    var stampSize = getSize(stamp);
    var offset = this._getElementOffset(stamp);
    // get the columns that this stamp affects
    var isOriginLeft = this._getOption('originLeft');
    var firstX = isOriginLeft ? offset.left : offset.right;
    var lastX = firstX + stampSize.outerWidth;
    var firstCol = Math.floor(firstX / this.columnWidth);
    firstCol = Math.max(0, firstCol);
    var lastCol = Math.floor(lastX / this.columnWidth);
    // lastCol should not go over if multiple of columnWidth #425
    lastCol -= lastX % this.columnWidth ? 0 : 1;
    lastCol = Math.min(this.cols - 1, lastCol);
    // set colYs to bottom of the stamp

    var isOriginTop = this._getOption('originTop');
    var stampMaxY = (isOriginTop ? offset.top : offset.bottom) + stampSize.outerHeight;
    for (var i = firstCol; i <= lastCol; i++) {
      this.colYs[i] = Math.max(stampMaxY, this.colYs[i]);
    }
  };
  Masonry.prototype._getContainerSize = function () {
    this.maxY = Math.max.apply(Math, this.colYs);
    var size = {
      height: this.maxY
    };
    if (this._getOption('fitWidth')) {
      size.width = this._getContainerFitWidth();
    }
    return size;
  };
  Masonry.prototype._getContainerFitWidth = function () {
    var unusedCols = 0;
    // count unused columns
    var i = this.cols;
    while (--i) {
      if (this.colYs[i] !== 0) {
        break;
      }
      unusedCols++;
    }
    // fit container to columns that have been used
    return (this.cols - unusedCols) * this.columnWidth - this.gutter;
  };
  Masonry.prototype.needsResizeLayout = function () {
    var previousWidth = this.containerWidth;
    this.getContainerWidth();
    return previousWidth != this.containerWidth;
  };
  return Masonry;
});

/*!
 * Masonry layout mode
 * sub-classes Masonry
 * http://masonry.desandro.com
 */

(function (window, factory) {
  // universal module definition
  /* jshint strict: false */ /*globals define, module, require */
  if (true) {
    // AMD
    !(__WEBPACK_AMD_DEFINE_ARRAY__ = [__WEBPACK_LOCAL_MODULE_8__, __WEBPACK_LOCAL_MODULE_9__], __WEBPACK_AMD_DEFINE_FACTORY__ = (factory),
		__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
		(__WEBPACK_AMD_DEFINE_FACTORY__.apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__)) : __WEBPACK_AMD_DEFINE_FACTORY__),
		__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
  } else {}
})(window, function factory(LayoutMode, Masonry) {
  'use strict';

  // -------------------------- masonryDefinition -------------------------- //

  // create an Outlayer layout class
  var MasonryMode = LayoutMode.create('masonry');
  var proto = MasonryMode.prototype;
  var keepModeMethods = {
    _getElementOffset: true,
    layout: true,
    _getMeasurement: true
  };

  // inherit Masonry prototype
  for (var method in Masonry.prototype) {
    // do not inherit mode methods
    if (!keepModeMethods[method]) {
      proto[method] = Masonry.prototype[method];
    }
  }
  var measureColumns = proto.measureColumns;
  proto.measureColumns = function () {
    // set items, used if measuring first item
    this.items = this.enviratope.filteredItems;
    measureColumns.call(this);
  };

  // point to mode options for fitWidth
  var _getOption = proto._getOption;
  proto._getOption = function (option) {
    if (option == 'fitWidth') {
      return this.options.isFitWidth !== undefined ? this.options.isFitWidth : this.options.fitWidth;
    }
    return _getOption.apply(this.enviratope, arguments);
  };
  return MasonryMode;
});

/**
 * fitRows layout mode
 */

(function (window, factory) {
  // universal module definition
  /* jshint strict: false */ /*globals define, module, require */
  if (true) {
    // AMD
    !(__WEBPACK_AMD_DEFINE_ARRAY__ = [__WEBPACK_LOCAL_MODULE_8__], __WEBPACK_AMD_DEFINE_FACTORY__ = (factory),
		__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
		(__WEBPACK_AMD_DEFINE_FACTORY__.apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__)) : __WEBPACK_AMD_DEFINE_FACTORY__),
		__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
  } else {}
})(window, function factory(LayoutMode) {
  'use strict';

  var FitRows = LayoutMode.create('fitRows');
  var proto = FitRows.prototype;
  proto._resetLayout = function () {
    this.x = 0;
    this.y = 0;
    this.maxY = 0;
    this._getMeasurement('gutter', 'outerWidth');
  };
  proto._getItemLayoutPosition = function (item) {
    item.getSize();
    var itemWidth = item.size.outerWidth + this.gutter;
    // if this element cannot fit in the current row
    var containerWidth = this.enviratope.size.innerWidth + this.gutter;
    if (this.x !== 0 && itemWidth + this.x > containerWidth) {
      this.x = 0;
      this.y = this.maxY;
    }
    var position = {
      x: this.x,
      y: this.y
    };
    this.maxY = Math.max(this.maxY, this.y + item.size.outerHeight);
    this.x += itemWidth;
    return position;
  };
  proto._getContainerSize = function () {
    return {
      height: this.maxY
    };
  };
  return FitRows;
});

/**
 * vertical layout mode
 */

(function (window, factory) {
  // universal module definition
  /* jshint strict: false */ /*globals define, module, require */
  if (true) {
    // AMD
    !(__WEBPACK_AMD_DEFINE_ARRAY__ = [__WEBPACK_LOCAL_MODULE_8__], __WEBPACK_AMD_DEFINE_FACTORY__ = (factory),
		__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
		(__WEBPACK_AMD_DEFINE_FACTORY__.apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__)) : __WEBPACK_AMD_DEFINE_FACTORY__),
		__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
  } else {}
})(window, function factory(LayoutMode) {
  'use strict';

  var Vertical = LayoutMode.create('vertical', {
    horizontalAlignment: 0
  });
  var proto = Vertical.prototype;
  proto._resetLayout = function () {
    this.y = 0;
  };
  proto._getItemLayoutPosition = function (item) {
    item.getSize();
    var x = (this.enviratope.size.innerWidth - item.size.outerWidth) * this.options.horizontalAlignment;
    var y = this.y;
    this.y += item.size.outerHeight;
    return {
      x: x,
      y: y
    };
  };
  proto._getContainerSize = function () {
    return {
      height: this.y
    };
  };
  return Vertical;
});

/*!
 * Enviratope v3.0.0
 *
 * Licensed GPLv3 for open source use
 * or Enviratope Commercial License for commercial use
 *
 * http://enviratope.metafizzy.co
 * Copyright 2016 Metafizzy
 */

(function (window, factory) {
  // universal module definition
  /* jshint strict: false */ /*globals define, module, require */
  if (true) {
    // AMD
    !(__WEBPACK_AMD_DEFINE_ARRAY__ = [__WEBPACK_LOCAL_MODULE_6__, __WEBPACK_LOCAL_MODULE_2__, __WEBPACK_LOCAL_MODULE_3__, __WEBPACK_LOCAL_MODULE_4__, __webpack_require__(165), __webpack_require__(477),
    // include default layout modes
    __webpack_require__(245), __webpack_require__(620), __webpack_require__(175)], __WEBPACK_AMD_DEFINE_RESULT__ = (function (Outlayer, getSize, matchesSelector, utils, Item, LayoutMode) {
      return factory(window, Outlayer, getSize, matchesSelector, utils, Item, LayoutMode);
    }).apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__),
		__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
  } else {}
})(window, function factory(window, Outlayer, getSize, matchesSelector, utils, Item, LayoutMode) {
  // -------------------------- vars -------------------------- //

  var jQuery = window.jQuery;

  // -------------------------- helpers -------------------------- //

  var trim = String.prototype.trim ? function (str) {
    return str.trim();
  } : function (str) {
    return str.replace(/^\s+|\s+$/g, '');
  };

  // -------------------------- enviratopeDefinition -------------------------- //

  // create an Outlayer layout class
  var Enviratope = Outlayer.create('enviratope', {
    layoutMode: 'masonry',
    isJQueryFiltering: true,
    sortAscending: true
  });
  Enviratope.Item = Item;
  Enviratope.LayoutMode = LayoutMode;
  var proto = Enviratope.prototype;
  proto._create = function () {
    this.itemGUID = 0;
    // functions that sort items
    this._sorters = {};
    this._getSorters();
    // call super
    Outlayer.prototype._create.call(this);

    // create layout modes
    this.modes = {};
    // start filteredItems with all items
    this.filteredItems = this.items;
    // keep of track of sortBys
    this.sortHistory = ['original-order'];
    // create from registered layout modes
    for (var name in LayoutMode.modes) {
      this._initLayoutMode(name);
    }
  };
  proto.reloadItems = function () {
    // reset item ID counter
    this.itemGUID = 0;
    // call super
    Outlayer.prototype.reloadItems.call(this);
  };
  proto._itemize = function () {
    var items = Outlayer.prototype._itemize.apply(this, arguments);
    // assign ID for original-order
    for (var i = 0; i < items.length; i++) {
      var item = items[i];
      item.id = this.itemGUID++;
    }
    this._updateItemsSortData(items);
    return items;
  };

  // -------------------------- layout -------------------------- //

  proto._initLayoutMode = function (name) {
    var Mode = LayoutMode.modes[name];
    // set mode options
    // HACK extend initial options, back-fill in default options
    var initialOpts = this.options[name] || {};
    this.options[name] = Mode.options ? utils.extend(Mode.options, initialOpts) : initialOpts;
    // init layout mode instance
    this.modes[name] = new Mode(this);
  };
  proto.layout = function () {
    // if first time doing layout, do all magic
    if (!this._isLayoutInited && this._getOption('initLayout')) {
      this.arrange();
      return;
    }
    this._layout();
  };

  // private method to be used in layout() & magic()
  proto._layout = function () {
    // don't animate first layout
    var isInstant = this._getIsInstant();
    // layout flow
    this._resetLayout();
    this._manageStamps();
    this.layoutItems(this.filteredItems, isInstant);

    // flag for initalized
    this._isLayoutInited = true;
  };

  // filter + sort + layout
  proto.arrange = function (opts) {
    // set any options pass
    this.option(opts);
    this._getIsInstant();
    // filter, sort, and layout

    // filter
    var filtered = this._filter(this.items);
    this.filteredItems = filtered.matches;
    this._bindArrangeComplete();
    if (this._isInstant) {
      this._noTransition(this._hideReveal, [filtered]);
    } else {
      this._hideReveal(filtered);
    }
    this._sort();
    this._layout();
  };
  // alias to _init for main plugin method
  proto._init = proto.arrange;
  proto._hideReveal = function (filtered) {
    this.reveal(filtered.needReveal);
    this.hide(filtered.needHide);
  };

  // HACK
  // Don't animate/transition first layout
  // Or don't animate/transition other layouts
  proto._getIsInstant = function () {
    var isLayoutInstant = this._getOption('layoutInstant');
    var isInstant = isLayoutInstant !== undefined ? isLayoutInstant : !this._isLayoutInited;
    this._isInstant = isInstant;
    return isInstant;
  };

  // listen for layoutComplete, hideComplete and revealComplete
  // to trigger arrangeComplete
  proto._bindArrangeComplete = function () {
    // listen for 3 events to trigger arrangeComplete
    var isLayoutComplete, isHideComplete, isRevealComplete;
    var _this = this;
    function arrangeParallelCallback() {
      if (isLayoutComplete && isHideComplete && isRevealComplete) {
        _this.dispatchEvent('arrangeComplete', null, [_this.filteredItems]);
      }
    }
    this.once('layoutComplete', function () {
      isLayoutComplete = true;
      arrangeParallelCallback();
    });
    this.once('hideComplete', function () {
      isHideComplete = true;
      arrangeParallelCallback();
    });
    this.once('revealComplete', function () {
      isRevealComplete = true;
      arrangeParallelCallback();
    });
  };

  // -------------------------- filter -------------------------- //

  proto._filter = function (items) {
    var filter = this.options.filter;
    filter = filter || '*';
    var matches = [];
    var hiddenMatched = [];
    var visibleUnmatched = [];
    var test = this._getFilterTest(filter);

    // test each item
    for (var i = 0; i < items.length; i++) {
      var item = items[i];
      if (item.isIgnored) {
        continue;
      }
      // add item to either matched or unmatched group
      var isMatched = test(item);
      // item.isFilterMatched = isMatched;
      // add to matches if its a match
      if (isMatched) {
        matches.push(item);
      }
      // add to additional group if item needs to be hidden or revealed
      if (isMatched && item.isHidden) {
        hiddenMatched.push(item);
      } else if (!isMatched && !item.isHidden) {
        visibleUnmatched.push(item);
      }
    }

    // return collections of items to be manipulated
    return {
      matches: matches,
      needReveal: hiddenMatched,
      needHide: visibleUnmatched
    };
  };

  // get a jQuery, function, or a matchesSelector test given the filter
  proto._getFilterTest = function (filter) {
    if (jQuery && this.options.isJQueryFiltering) {
      // use jQuery
      return function (item) {
        return jQuery(item.element).is(filter);
      };
    }
    if (typeof filter == 'function') {
      // use filter as function
      return function (item) {
        return filter(item.element);
      };
    }
    // default, use filter as selector string
    return function (item) {
      return matchesSelector(item.element, filter);
    };
  };

  // -------------------------- sorting -------------------------- //

  /**
   * @params {Array} elems
   * @public
   */
  proto.updateSortData = function (elems) {
    // get items
    var items;
    if (elems) {
      elems = utils.makeArray(elems);
      items = this.getItems(elems);
    } else {
      // update all items if no elems provided
      items = this.items;
    }
    this._getSorters();
    this._updateItemsSortData(items);
  };
  proto._getSorters = function () {
    var getSortData = this.options.getSortData;
    for (var key in getSortData) {
      var sorter = getSortData[key];
      this._sorters[key] = mungeSorter(sorter);
    }
  };

  /**
   * @params {Array} items - of Enviratope.Items
   * @private
   */
  proto._updateItemsSortData = function (items) {
    // do not update if no items
    var len = items && items.length;
    for (var i = 0; len && i < len; i++) {
      var item = items[i];
      item.updateSortData();
    }
  };

  // ----- munge sorter ----- //

  // encapsulate this, as we just need mungeSorter
  // other functions in here are just for munging
  var mungeSorter = function () {
    // add a magic layer to sorters for convienent shorthands
    // `.foo-bar` will use the text of .foo-bar querySelector
    // `[foo-bar]` will use attribute
    // you can also add parser
    // `.foo-bar parseInt` will parse that as a number
    function mungeSorter(sorter) {
      // if not a string, return function or whatever it is
      if (typeof sorter != 'string') {
        return sorter;
      }
      // parse the sorter string
      var args = trim(sorter).split(' ');
      var query = args[0];
      // check if query looks like [an-attribute]
      var attrMatch = query.match(/^\[(.+)\]$/);
      var attr = attrMatch && attrMatch[1];
      var getValue = getValueGetter(attr, query);
      // use second argument as a parser
      var parser = Enviratope.sortDataParsers[args[1]];
      // parse the value, if there was a parser
      sorter = parser ? function (elem) {
        return elem && parser(getValue(elem));
      } :
      // otherwise just return value
      function (elem) {
        return elem && getValue(elem);
      };
      return sorter;
    }

    // get an attribute getter, or get text of the querySelector
    function getValueGetter(attr, query) {
      // if query looks like [foo-bar], get attribute
      if (attr) {
        return function getAttribute(elem) {
          return elem.getAttribute(attr);
        };
      }

      // otherwise, assume its a querySelector, and get its text
      return function getChildText(elem) {
        var child = elem.querySelector(query);
        return child && child.textContent;
      };
    }
    return mungeSorter;
  }();

  // parsers used in getSortData shortcut strings
  Enviratope.sortDataParsers = {
    'parseInt': function (_parseInt) {
      function parseInt(_x) {
        return _parseInt.apply(this, arguments);
      }
      parseInt.toString = function () {
        return _parseInt.toString();
      };
      return parseInt;
    }(function (val) {
      return parseInt(val, 10);
    }),
    'parseFloat': function (_parseFloat) {
      function parseFloat(_x2) {
        return _parseFloat.apply(this, arguments);
      }
      parseFloat.toString = function () {
        return _parseFloat.toString();
      };
      return parseFloat;
    }(function (val) {
      return parseFloat(val);
    })
  };

  // ----- sort method ----- //

  // sort filteredItem order
  proto._sort = function () {
    var sortByOpt = this.options.sortBy;
    if (!sortByOpt) {
      return;
    }
    // concat all sortBy and sortHistory
    var sortBys = [].concat.apply(sortByOpt, this.sortHistory);
    // sort magic
    var itemSorter = getItemSorter(sortBys, this.options.sortAscending);
    this.filteredItems.sort(itemSorter);
    // keep track of sortBy History
    if (sortByOpt != this.sortHistory[0]) {
      // add to front, oldest goes in last
      this.sortHistory.unshift(sortByOpt);
    }
  };

  // returns a function used for sorting
  function getItemSorter(sortBys, sortAsc) {
    return function sorter(itemA, itemB) {
      // cycle through all sortKeys
      for (var i = 0; i < sortBys.length; i++) {
        var sortBy = sortBys[i];
        var a = itemA.sortData[sortBy];
        var b = itemB.sortData[sortBy];
        if (a > b || a < b) {
          // if sortAsc is an object, use the value given the sortBy key
          var isAscending = sortAsc[sortBy] !== undefined ? sortAsc[sortBy] : sortAsc;
          var direction = isAscending ? 1 : -1;
          return (a > b ? 1 : -1) * direction;
        }
      }
      return 0;
    };
  }

  // -------------------------- methods -------------------------- //

  // get layout mode
  proto._mode = function () {
    var layoutMode = this.options.layoutMode;
    var mode = this.modes[layoutMode];
    if (!mode) {
      // TODO console.error
      throw new Error('No layout mode: ' + layoutMode);
    }
    // HACK sync mode's options
    // any options set after init for layout mode need to be synced
    mode.options = this.options[layoutMode];
    return mode;
  };
  proto._resetLayout = function () {
    // trigger original reset layout
    Outlayer.prototype._resetLayout.call(this);
    this._mode()._resetLayout();
  };
  proto._getItemLayoutPosition = function (item) {
    return this._mode()._getItemLayoutPosition(item);
  };
  proto._manageStamp = function (stamp) {
    this._mode()._manageStamp(stamp);
  };
  proto._getContainerSize = function () {
    return this._mode()._getContainerSize();
  };
  proto.needsResizeLayout = function () {
    return this._mode().needsResizeLayout();
  };

  // -------------------------- adding & removing -------------------------- //

  // HEADS UP overwrites default Outlayer appended
  proto.appended = function (elems) {
    var items = this.addItems(elems);
    if (!items.length) {
      return;
    }
    // filter, layout, reveal new items
    var filteredItems = this._filterRevealAdded(items);
    // add to filteredItems
    this.filteredItems = this.filteredItems.concat(filteredItems);
  };

  // HEADS UP overwrites default Outlayer prepended
  proto.prepended = function (elems) {
    var items = this._itemize(elems);
    if (!items.length) {
      return;
    }
    // start new layout
    this._resetLayout();
    this._manageStamps();
    // filter, layout, reveal new items
    var filteredItems = this._filterRevealAdded(items);
    // layout previous items
    this.layoutItems(this.filteredItems);
    // add to items and filteredItems
    this.filteredItems = filteredItems.concat(this.filteredItems);
    this.items = items.concat(this.items);
  };
  proto._filterRevealAdded = function (items) {
    var filtered = this._filter(items);
    this.hide(filtered.needHide);
    // reveal all new items
    this.reveal(filtered.matches);
    // layout new items, no transition
    this.layoutItems(filtered.matches, true);
    return filtered.matches;
  };

  /**
   * Filter, sort, and layout newly-appended item elements
   * @param {Array or NodeList or Element} elems
   */
  proto.insert = function (elems) {
    var items = this.addItems(elems);
    if (!items.length) {
      return;
    }
    // append item elements
    var i, item;
    var len = items.length;
    for (i = 0; i < len; i++) {
      item = items[i];
      this.element.appendChild(item.element);
    }
    // filter new stuff
    var filteredInsertItems = this._filter(items).matches;
    // set flag
    for (i = 0; i < len; i++) {
      items[i].isLayoutInstant = true;
    }
    this.arrange();
    // reset flag
    for (i = 0; i < len; i++) {
      delete items[i].isLayoutInstant;
    }
    this.reveal(filteredInsertItems);
  };
  var _remove = proto.remove;
  proto.remove = function (elems) {
    elems = utils.makeArray(elems);
    var removeItems = this.getItems(elems);
    // do regular thing
    _remove.call(this, elems);
    // bail if no items to remove
    var len = removeItems && removeItems.length;
    // remove elems from filteredItems
    for (var i = 0; len && i < len; i++) {
      var item = removeItems[i];
      // remove item from collection
      utils.removeFrom(this.filteredItems, item);
    }
  };
  proto.shuffle = function () {
    // update random sortData
    for (var i = 0; i < this.items.length; i++) {
      var item = this.items[i];
      item.sortData.random = Math.random();
    }
    this.options.sortBy = 'random';
    this._sort();
    this._layout();
  };

  /**
   * trigger fn without transition
   * kind of hacky to have this in the first place
   * @param {Function} fn
   * @param {Array} args
   * @returns ret
   * @private
   */
  proto._noTransition = function (fn, args) {
    // save transitionDuration before disabling
    var transitionDuration = this.options.transitionDuration;
    // disable transition
    this.options.transitionDuration = 0;
    // do it
    var returnValue = fn.apply(this, args);
    // re-enable transition for reveal
    this.options.transitionDuration = transitionDuration;
    return returnValue;
  };

  // ----- helper methods ----- //

  /**
   * getter method for getting filtered item elements
   * @returns {Array} elems - collection of item elements
   */
  proto.getFilteredItemElements = function () {
    return this.filteredItems.map(function (item) {
      return item.element;
    });
  };

  // -----  ----- //

  return Enviratope;
});

/***/ }),

/***/ 59:
/***/ ((__unused_webpack_module, __unused_webpack_exports, __webpack_require__) => {

/* provided dependency */ var jQuery = __webpack_require__(311);
/*!
 * Justified Gallery - v3.6.2
 * http://miromannino.github.io/Justified-Gallery/
 * Copyright (c) 2016 Miro Mannino
 * Licensed under the MIT license.
 */
(function ($) {
  function hasScrollBar() {
    return $("body").height() > $(window).height();
  }
  /**
   * Justified Gallery controller constructor
   *
   * @param $gallery the gallery to build
   * @param settings the settings (the defaults are in $.fn.justifiedGallery.defaults)
   * @constructor
   */
  var JustifiedGallery = function JustifiedGallery($gallery, settings) {
    this.settings = settings;
    this.checkSettings();
    this.imgAnalyzerTimeout = null;
    this.entries = null;
    this.buildingRow = {
      entriesBuff: [],
      width: 0,
      height: 0,
      aspectRatio: 0
    };
    this.lastAnalyzedIndex = -1;
    this["yield"] = {
      every: 2,
      // do a flush every n flushes (must be greater than 1)
      flushed: 0 // flushed rows without a yield
    };

    this.border = settings.border >= 0 ? settings.border : settings.margins;
    this.maxRowHeight = this.retrieveMaxRowHeight();
    this.suffixRanges = this.retrieveSuffixRanges();
    this.offY = this.border;
    this.rows = 0;
    this.spinner = {
      phase: 0,
      timeSlot: 150,
      $el: $('<div class="spinner"><span></span><span></span><span></span></div>'),
      intervalId: null
    };
    this.checkWidthIntervalId = null;
    this.galleryWidth = $gallery.width();
    this.$gallery = $gallery;
  };

  /** @returns {String} the best suffix given the width and the height */
  JustifiedGallery.prototype.getSuffix = function (width, height) {
    var longestSide, i;
    longestSide = width > height ? width : height;
    for (i = 0; i < this.suffixRanges.length; i++) {
      if (longestSide <= this.suffixRanges[i]) {
        return this.settings.sizeRangeSuffixes[this.suffixRanges[i]];
      }
    }
    return this.settings.sizeRangeSuffixes[this.suffixRanges[i - 1]];
  };

  /**
   * Remove the suffix from the string
   *
   * @returns {string} a new string without the suffix
   */
  JustifiedGallery.prototype.removeSuffix = function (str, suffix) {
    return str.substring(0, str.length - suffix.length);
  };

  /**
   * @returns {boolean} a boolean to say if the suffix is contained in the str or not
   */
  JustifiedGallery.prototype.endsWith = function (str, suffix) {
    return str.indexOf(suffix, str.length - suffix.length) !== -1;
  };

  /**
   * Get the used suffix of a particular url
   *
   * @param str
   * @returns {String} return the used suffix
   */
  JustifiedGallery.prototype.getUsedSuffix = function (str) {
    for (var si in this.settings.sizeRangeSuffixes) {
      if (this.settings.sizeRangeSuffixes.hasOwnProperty(si)) {
        if (this.settings.sizeRangeSuffixes[si].length === 0) {
          continue;
        }
        if (this.endsWith(str, this.settings.sizeRangeSuffixes[si])) {
          return this.settings.sizeRangeSuffixes[si];
        }
      }
    }
    return '';
  };

  /**
   * Given an image src, with the width and the height, returns the new image src with the
   * best suffix to show the best quality thumbnail.
   *
   * @returns {String} the suffix to use
   */
  JustifiedGallery.prototype.newSrc = function (imageSrc, imgWidth, imgHeight) {
    var newImageSrc;
    if (this.settings.thumbnailPath) {
      newImageSrc = this.settings.thumbnailPath(imageSrc, imgWidth, imgHeight);
    } else {
      var matchRes = imageSrc.match(this.settings.extension);
      var ext = matchRes !== null ? matchRes[0] : '';
      newImageSrc = imageSrc.replace(this.settings.extension, '');
      newImageSrc = this.removeSuffix(newImageSrc, this.getUsedSuffix(newImageSrc));
      newImageSrc += this.getSuffix(imgWidth, imgHeight) + ext;
    }
    return newImageSrc;
  };

  /**
   * Shows the images that is in the given entry
   *
   * @param $entry the entry
   * @param callback the callback that is called when the show animation is finished
   */
  JustifiedGallery.prototype.showImg = function ($entry, callback) {
    if (this.settings.cssAnimation) {
      $entry.addClass('entry-visible');
      if (callback) {
        callback();
      }
    } else {
      $entry.stop().fadeTo(this.settings.imagesAnimationDuration, 1.0, callback);
    }
  };

  /**
   * Extract the image src form the image, looking from the 'safe-src', and if it can't be found, from the
   * 'src' attribute. It saves in the image data the 'jg.originalSrc' field, with the extracted src.
   *
   * @param $image the image to analyze
   * @returns {String} the extracted src
   */
  JustifiedGallery.prototype.extractImgSrcFromImage = function ($image) {
    var imageSrc = typeof $image.data('safe-src') !== 'undefined' ? $image.data('safe-src') : $image.attr('src');
    $image.data('jg.originalSrc', imageSrc);
    return imageSrc;
  };

  /** @returns {jQuery} the image in the given entry */
  JustifiedGallery.prototype.imgFromEntry = function ($entry) {
    var $img = $entry.find('> img');
    if ($img.length === 0) {
      $img = $entry.find('> a > img');
    }
    return $img.length === 0 ? null : $img;
  };

  /** @returns {jQuery} the caption in the given entry */
  JustifiedGallery.prototype.captionFromEntry = function ($entry) {
    var $caption = $entry.find('.caption');
    return $caption.length === 0 ? null : $caption;
  };

  /**
   * Display the entry
   *
   * @param {jQuery} $entry the entry to display
   * @param {int} x the x position where the entry must be positioned
   * @param y the y position where the entry must be positioned
   * @param imgWidth the image width
   * @param imgHeight the image height
   * @param rowHeight the row height of the row that owns the entry
   */
  JustifiedGallery.prototype.displayEntry = function ($entry, x, y, imgWidth, imgHeight, rowHeight) {
    $entry.width(imgWidth);
    $entry.height(rowHeight);
    $entry.css('top', y);
    $entry.css('left', x);
    var $image = this.imgFromEntry($entry);
    if ($image !== null) {
      $image.css('width', imgWidth);
      $image.css('height', imgHeight);
      $image.css('margin-left', -imgWidth / 2);
      $image.css('margin-top', -imgHeight / 2);

      // Image reloading for an high quality of thumbnails
      var imageSrc = $image.attr('src');
      var newImageSrc = this.newSrc(imageSrc, imgWidth, imgHeight);
      $image.one('error', function () {
        $image.attr('src', $image.data('jg.originalSrc')); // revert to the original thumbnail, we got it.
      });

      var loadNewImage = function loadNewImage() {
        if (imageSrc !== newImageSrc) {
          // load the new image after the fadeIn
          $image.attr('src', newImageSrc);
        }
      };
      if ($entry.data('jg.loaded') === 'skipped') {
        this.onImageEvent(imageSrc, $.proxy(function () {
          this.showImg($entry, loadNewImage);
          $entry.data('jg.loaded', true);
        }, this));
      } else {
        this.showImg($entry, loadNewImage);
      }
    } else {
      this.showImg($entry);
    }
    this.displayEntryCaption($entry);
  };

  /**
   * Display the entry caption. If the caption element doesn't exists, it creates the caption using the 'alt'
   * or the 'title' attributes.
   *
   * @param {jQuery} $entry the entry to process
   */
  JustifiedGallery.prototype.displayEntryCaption = function ($entry) {
    var $image = this.imgFromEntry($entry);
    if ($image !== null && this.settings.captions) {
      var $imgCaption = this.captionFromEntry($entry);

      // Create it if it doesn't exists
      if ($imgCaption === null) {
        var caption = $image.attr('alt');
        if (!this.isValidCaption(caption)) {
          caption = $entry.attr('title');
        }
        if (this.isValidCaption(caption)) {
          // Create only we found something
          $imgCaption = $('<div class="caption">' + caption + '</div>');
          $entry.append($imgCaption);
          $entry.data('jg.createdCaption', true);
        }
      }

      // Create events (we check again the $imgCaption because it can be still inexistent)
      if ($imgCaption !== null) {
        if (!this.settings.cssAnimation) {
          $imgCaption.stop().fadeTo(0, this.settings.captionSettings.nonVisibleOpacity);
        }
        this.addCaptionEventsHandlers($entry);
      }
    } else {
      this.removeCaptionEventsHandlers($entry);
    }
  };

  /**
   * Validates the caption
   *
   * @param caption The caption that should be validated
   * @return {boolean} Validation result
   */
  JustifiedGallery.prototype.isValidCaption = function (caption) {
    return typeof caption !== 'undefined' && caption.length > 0;
  };

  /**
   * The callback for the event 'mouseenter'. It assumes that the event currentTarget is an entry.
   * It shows the caption using jQuery (or using CSS if it is configured so)
   *
   * @param {Event} eventObject the event object
   */
  JustifiedGallery.prototype.onEntryMouseEnterForCaption = function (eventObject) {
    var $caption = this.captionFromEntry($(eventObject.currentTarget));
    if (this.settings.cssAnimation) {
      if (typeof $caption !== 'undefined' && $caption != null) {
        $caption.addClass('caption-visible').removeClass('caption-hidden');
      }
    } else {
      if (typeof $caption !== 'undefined' && $caption != null) {
        $caption.stop().fadeTo(this.settings.captionSettings.animationDuration, this.settings.captionSettings.visibleOpacity);
      }
    }
  };

  /**
   * The callback for the event 'mouseleave'. It assumes that the event currentTarget is an entry.
   * It hides the caption using jQuery (or using CSS if it is configured so)
   *
   * @param {Event} eventObject the event object
   */
  JustifiedGallery.prototype.onEntryMouseLeaveForCaption = function (eventObject) {
    var $caption = this.captionFromEntry($(eventObject.currentTarget));
    if (this.settings.cssAnimation) {
      if (typeof $caption !== 'undefined' && $caption != null) {
        $caption.removeClass('caption-visible').removeClass('caption-hidden');
      }
    } else {
      if (typeof $caption !== 'undefined' && $caption != null) {
        $caption.stop().fadeTo(this.settings.captionSettings.animationDuration, this.settings.captionSettings.nonVisibleOpacity);
      }
    }
  };

  /**
   * Add the handlers of the entry for the caption
   *
   * @param $entry the entry to modify
   */
  JustifiedGallery.prototype.addCaptionEventsHandlers = function ($entry) {
    var captionMouseEvents = $entry.data('jg.captionMouseEvents');
    if (typeof captionMouseEvents === 'undefined') {
      captionMouseEvents = {
        mouseenter: $.proxy(this.onEntryMouseEnterForCaption, this),
        mouseleave: $.proxy(this.onEntryMouseLeaveForCaption, this)
      };
      $entry.on('mouseenter', undefined, undefined, captionMouseEvents.mouseenter);
      $entry.on('mouseleave', undefined, undefined, captionMouseEvents.mouseleave);
      $entry.data('jg.captionMouseEvents', captionMouseEvents);
    }
  };

  /**
   * Remove the handlers of the entry for the caption
   *
   * @param $entry the entry to modify
   */
  JustifiedGallery.prototype.removeCaptionEventsHandlers = function ($entry) {
    var captionMouseEvents = $entry.data('jg.captionMouseEvents');
    if (typeof captionMouseEvents !== 'undefined') {
      $entry.off('mouseenter', undefined, captionMouseEvents.mouseenter);
      $entry.off('mouseleave', undefined, captionMouseEvents.mouseleave);
      $entry.removeData('jg.captionMouseEvents');
    }
  };

  /**
   * Justify the building row, preparing it to
   *
   * @param isLastRow
   * @returns a boolean to know if the row has been justified or not
   */
  JustifiedGallery.prototype.prepareBuildingRow = function (isLastRow) {
    var i,
      $entry,
      imgAspectRatio,
      newImgW,
      newImgH,
      justify = true;
    var minHeight = 0;
    var availableWidth = this.galleryWidth - 2 * this.border - (this.buildingRow.entriesBuff.length - 1) * this.settings.margins;
    var rowHeight = availableWidth / this.buildingRow.aspectRatio;
    var defaultRowHeight = this.settings.rowHeight;
    var justifiable = this.buildingRow.width / availableWidth > this.settings.justifyThreshold;

    // Skip the last row if we can't justify it and the lastRow == 'hide'
    if (isLastRow && this.settings.lastRow === 'hide' && !justifiable) {
      for (i = 0; i < this.buildingRow.entriesBuff.length; i++) {
        $entry = this.buildingRow.entriesBuff[i];
        if (this.settings.cssAnimation) {
          $entry.removeClass('entry-visible');
        } else {
          $entry.stop().fadeTo(0, 0);
        }
      }
      return -1;
    }

    // With lastRow = nojustify, justify if is justificable (the images will not become too big)
    if (isLastRow && !justifiable && this.settings.lastRow !== 'justify' && this.settings.lastRow !== 'hide') {
      justify = false;
      if (this.rows > 0) {
        defaultRowHeight = (this.offY - this.border - this.settings.margins * this.rows) / this.rows;
        if (defaultRowHeight * this.buildingRow.aspectRatio / availableWidth > this.settings.justifyThreshold) {
          justify = true;
        } else {
          justify = false;
        }
      }
    }
    for (i = 0; i < this.buildingRow.entriesBuff.length; i++) {
      $entry = this.buildingRow.entriesBuff[i];
      imgAspectRatio = $entry.data('jg.width') / $entry.data('jg.height');
      if (justify) {
        newImgW = i === this.buildingRow.entriesBuff.length - 1 ? availableWidth : rowHeight * imgAspectRatio;
        newImgH = rowHeight;

        /* With fixedHeight the newImgH must be greater than rowHeight.
         In some cases here this is not satisfied (due to the justification).
         But we comment it, because is better to have a shorter but justified row instead
         to have a cropped image at the end. */
        /*if (this.settings.fixedHeight && newImgH < this.settings.rowHeight) {
         newImgW = this.settings.rowHeight * imgAspectRatio;
         newImgH = this.settings.rowHeight;
         }*/
      } else {
        newImgW = defaultRowHeight * imgAspectRatio;
        newImgH = defaultRowHeight;
      }
      availableWidth -= Math.round(newImgW);
      $entry.data('jg.jwidth', Math.round(newImgW));
      $entry.data('jg.jheight', Math.ceil(newImgH));
      if (i === 0 || minHeight > newImgH) {
        minHeight = newImgH;
      }
    }
    if (this.settings.fixedHeight && minHeight > this.settings.rowHeight) {
      minHeight = this.settings.rowHeight;
    }
    this.buildingRow.height = minHeight;
    return justify;
  };

  /**
   * Clear the building row data to be used for a new row
   */
  JustifiedGallery.prototype.clearBuildingRow = function () {
    this.buildingRow.entriesBuff = [];
    this.buildingRow.aspectRatio = 0;
    this.buildingRow.width = 0;
  };

  /**
   * Flush a row: justify it, modify the gallery height accordingly to the row height
   *
   * @param isLastRow
   */
  JustifiedGallery.prototype.flushRow = function (isLastRow) {
    var settings = this.settings;
    var $entry,
      buildingRowRes,
      offX = this.border,
      i;
    buildingRowRes = this.prepareBuildingRow(isLastRow);
    if (isLastRow && settings.lastRow === 'hide' && buildingRowRes === -1) {
      this.clearBuildingRow();
      return;
    }
    if (this.maxRowHeight.isPercentage) {
      if (this.maxRowHeight.value * settings.rowHeight < this.buildingRow.height) {
        this.buildingRow.height = this.maxRowHeight.value * settings.rowHeight;
      }
    } else {
      if (this.maxRowHeight.value > 0 && this.maxRowHeight.value < this.buildingRow.height) {
        this.buildingRow.height = this.maxRowHeight.value;
      }
    }

    // Align last (unjustified) row
    if (settings.lastRow === 'center' || settings.lastRow === 'right') {
      var availableWidth = this.galleryWidth - 2 * this.border - (this.buildingRow.entriesBuff.length - 1) * settings.margins;
      for (i = 0; i < this.buildingRow.entriesBuff.length; i++) {
        $entry = this.buildingRow.entriesBuff[i];
        availableWidth -= $entry.data('jg.jwidth');
      }
      if (settings.lastRow === 'center') {
        offX += availableWidth / 2;
      } else if (settings.lastRow === 'right') {
        offX += availableWidth;
      }
    }
    for (i = 0; i < this.buildingRow.entriesBuff.length; i++) {
      $entry = this.buildingRow.entriesBuff[i];
      this.displayEntry($entry, offX, this.offY, $entry.data('jg.jwidth'), $entry.data('jg.jheight'), this.buildingRow.height);
      offX += $entry.data('jg.jwidth') + settings.margins;
    }

    // Gallery Height
    this.galleryHeightToSet = this.offY + this.buildingRow.height + this.border;
    this.$gallery.height(this.galleryHeightToSet + this.getSpinnerHeight());
    if (!isLastRow || this.buildingRow.height <= settings.rowHeight && buildingRowRes) {
      // Ready for a new row
      this.offY += this.buildingRow.height + settings.margins;
      this.rows += 1;
      this.clearBuildingRow();
      this.$gallery.trigger('jg.rowflush');
    }
  };

  /**
   * Checks the width of the gallery container, to know if a new justification is needed
   */
  var scrollBarOn = false;
  JustifiedGallery.prototype.checkWidth = function () {
    this.checkWidthIntervalId = setInterval($.proxy(function () {
      var galleryWidth = parseFloat(this.$gallery.width());
      if (hasScrollBar() === scrollBarOn) {
        if (Math.abs(galleryWidth - this.galleryWidth) > this.settings.refreshSensitivity) {
          this.galleryWidth = galleryWidth;
          this.rewind();

          // Restart to analyze
          this.startImgAnalyzer(true);
        }
      } else {
        scrollBarOn = hasScrollBar();
        this.galleryWidth = galleryWidth;
      }
    }, this), this.settings.refreshTime);
  };

  /**
   * @returns {boolean} a boolean saying if the spinner is active or not
   */
  JustifiedGallery.prototype.isSpinnerActive = function () {
    return this.spinner.intervalId !== null;
  };

  /**
   * @returns {int} the spinner height
   */
  JustifiedGallery.prototype.getSpinnerHeight = function () {
    return this.spinner.$el.innerHeight();
  };

  /**
   * Stops the spinner animation and modify the gallery height to exclude the spinner
   */
  JustifiedGallery.prototype.stopLoadingSpinnerAnimation = function () {
    clearInterval(this.spinner.intervalId);
    this.spinner.intervalId = null;
    this.$gallery.height(this.$gallery.height() - this.getSpinnerHeight());
    this.spinner.$el.detach();
  };

  /**
   * Starts the spinner animation
   */
  JustifiedGallery.prototype.startLoadingSpinnerAnimation = function () {
    var spinnerContext = this.spinner;
    var $spinnerPoints = spinnerContext.$el.find('span');
    clearInterval(spinnerContext.intervalId);
    this.$gallery.append(spinnerContext.$el);
    this.$gallery.height(this.offY + this.buildingRow.height + this.getSpinnerHeight());
    spinnerContext.intervalId = setInterval(function () {
      if (spinnerContext.phase < $spinnerPoints.length) {
        $spinnerPoints.eq(spinnerContext.phase).fadeTo(spinnerContext.timeSlot, 1);
      } else {
        $spinnerPoints.eq(spinnerContext.phase - $spinnerPoints.length).fadeTo(spinnerContext.timeSlot, 0);
      }
      spinnerContext.phase = (spinnerContext.phase + 1) % ($spinnerPoints.length * 2);
    }, spinnerContext.timeSlot);
  };

  /**
   * Rewind the image analysis to start from the first entry.
   */
  JustifiedGallery.prototype.rewind = function () {
    this.lastAnalyzedIndex = -1;
    this.offY = this.border;
    this.rows = 0;
    this.clearBuildingRow();
  };

  /**
   * Update the entries searching it from the justified gallery HTML element
   *
   * @param norewind if norewind only the new entries will be changed (i.e. randomized, sorted or filtered)
   * @returns {boolean} true if some entries has been founded
   */
  JustifiedGallery.prototype.updateEntries = function (norewind) {
    this.entries = this.$gallery.find(this.settings.selector).toArray();
    if (this.entries.length === 0) {
      return false;
    }

    // Filter
    if (this.settings.filter) {
      this.modifyEntries(this.filterArray, norewind);
    } else {
      this.modifyEntries(this.resetFilters, norewind);
    }

    // Sort or randomize
    if ($.isFunction(this.settings.sort)) {
      this.modifyEntries(this.sortArray, norewind);
    } else if (this.settings.randomize) {
      this.modifyEntries(this.shuffleArray, norewind);
    }
    return true;
  };

  /**
   * Apply the entries order to the DOM, iterating the entries and appending the images
   *
   * @param entries the entries that has been modified and that must be re-ordered in the DOM
   */
  JustifiedGallery.prototype.insertToGallery = function (entries) {
    var that = this;
    $.each(entries, function () {
      $(this).appendTo(that.$gallery);
    });
  };

  /**
   * Shuffle the array using the Fisher-Yates shuffle algorithm
   *
   * @param a the array to shuffle
   * @return the shuffled array
   */
  JustifiedGallery.prototype.shuffleArray = function (a) {
    var i, j, temp;
    for (i = a.length - 1; i > 0; i--) {
      j = Math.floor(Math.random() * (i + 1));
      temp = a[i];
      a[i] = a[j];
      a[j] = temp;
    }
    this.insertToGallery(a);
    return a;
  };

  /**
   * Sort the array using settings.comparator as comparator
   *
   * @param a the array to sort (it is sorted)
   * @return the sorted array
   */
  JustifiedGallery.prototype.sortArray = function (a) {
    a.sort(this.settings.sort);
    this.insertToGallery(a);
    return a;
  };

  /**
   * Reset the filters removing the 'jg-filtered' class from all the entries
   *
   * @param a the array to reset
   */
  JustifiedGallery.prototype.resetFilters = function (a) {
    for (var i = 0; i < a.length; i++) {
      $(a[i]).removeClass('jg-filtered');
    }
    return a;
  };

  /**
   * Filter the entries considering theirs classes (if a string has been passed) or using a function for filtering.
   *
   * @param a the array to filter
   * @return the filtered array
   */
  JustifiedGallery.prototype.filterArray = function (a) {
    var settings = this.settings;
    if ($.type(settings.filter) === 'string') {
      // Filter only keeping the entries passed in the string
      return a.filter(function (el) {
        var $el = $(el);
        if ($el.is(settings.filter)) {
          $el.removeClass('jg-filtered');
          return true;
        } else {
          $el.addClass('jg-filtered');
          return false;
        }
      });
    } else if ($.isFunction(settings.filter)) {
      // Filter using the passed function
      return a.filter(settings.filter);
    }
  };

  /**
   * Modify the entries. With norewind only the new inserted images will be modified (the ones after lastAnalyzedIndex)
   *
   * @param functionToApply the function to call to modify the entries (e.g. sorting, randomization, filtering)
   * @param norewind specify if the norewind has been called or not
   */
  JustifiedGallery.prototype.modifyEntries = function (functionToApply, norewind) {
    var lastEntries = norewind ? this.entries.splice(this.lastAnalyzedIndex + 1, this.entries.length - this.lastAnalyzedIndex - 1) : this.entries;
    lastEntries = functionToApply.call(this, lastEntries);
    this.entries = norewind ? this.entries.concat(lastEntries) : lastEntries;
  };

  /**
   * Destroy the Justified Gallery instance.
   *
   * It clears all the css properties added in the style attributes. We doesn't backup the original
   * values for those css attributes, because it costs (performance) and because in general one
   * shouldn't use the style attribute for an uniform set of images (where we suppose the use of
   * classes). Creating a backup is also difficult because JG could be called multiple times and
   * with different style attributes.
   */
  JustifiedGallery.prototype.destroy = function () {
    clearInterval(this.checkWidthIntervalId);
    $.each(this.entries, $.proxy(function (_, entry) {
      var $entry = $(entry);

      // Reset entry style
      $entry.css('width', '');
      $entry.css('height', '');
      $entry.css('top', '');
      $entry.css('left', '');
      $entry.data('jg.loaded', undefined);
      $entry.removeClass('jg-entry');

      // Reset image style
      var $img = this.imgFromEntry($entry);
      $img.css('width', '');
      $img.css('height', '');
      $img.css('margin-left', '');
      $img.css('margin-top', '');
      $img.attr('src', $img.data('jg.originalSrc'));
      $img.data('jg.originalSrc', undefined);

      // Remove caption
      this.removeCaptionEventsHandlers($entry);
      var $caption = this.captionFromEntry($entry);
      if ($entry.data('jg.createdCaption')) {
        // remove also the caption element (if created by jg)
        $entry.data('jg.createdCaption', undefined);
        if ($caption !== null) {
          $caption.remove();
        }
      } else {
        if ($caption !== null) {
          $caption.fadeTo(0, 1);
        }
      }
    }, this));
    this.$gallery.css('height', '');
    this.$gallery.removeClass('envira-justified-gallery');
    this.$gallery.data('jg.controller', undefined);
  };

  /**
   * Analyze the images and builds the rows. It returns if it found an image that is not loaded.
   *
   * @param isForResize if the image analyzer is called for resizing or not, to call a different callback at the end
   */
  JustifiedGallery.prototype.analyzeImages = function (isForResize) {
    for (var i = this.lastAnalyzedIndex + 1; i < this.entries.length; i++) {
      var $entry = $(this.entries[i]);
      if ($entry.data('jg.loaded') === true || $entry.data('jg.loaded') === 'skipped') {
        var availableWidth = this.galleryWidth - 2 * this.border - (this.buildingRow.entriesBuff.length - 1) * this.settings.margins;
        var imgAspectRatio = $entry.data('jg.width') / $entry.data('jg.height');
        if (availableWidth / (this.buildingRow.aspectRatio + imgAspectRatio) < this.settings.rowHeight) {
          this.flushRow(false);
          if (++this["yield"].flushed >= this["yield"].every) {
            this.startImgAnalyzer(isForResize);
            return;
          }
        }
        this.buildingRow.entriesBuff.push($entry);
        this.buildingRow.aspectRatio += imgAspectRatio;
        this.buildingRow.width += imgAspectRatio * this.settings.rowHeight;
        this.lastAnalyzedIndex = i;
      } else if ($entry.data('jg.loaded') !== 'error') {
        return;
      }
    }

    // Last row flush (the row is not full)
    if (this.buildingRow.entriesBuff.length > 0) {
      this.flushRow(true);
    }
    if (this.isSpinnerActive()) {
      this.stopLoadingSpinnerAnimation();
    }

    /* Stop, if there is, the timeout to start the analyzeImages.
    This is because an image can be set loaded, and the timeout can be set,
    but this image can be analyzed yet.
    */
    this.stopImgAnalyzerStarter();

    // On complete callback
    this.$gallery.trigger(isForResize ? 'jg.resize' : 'jg.complete');
    this.$gallery.height(this.galleryHeightToSet);
  };

  /**
   * Stops any ImgAnalyzer starter (that has an assigned timeout)
   */
  JustifiedGallery.prototype.stopImgAnalyzerStarter = function () {
    this["yield"].flushed = 0;
    if (this.imgAnalyzerTimeout !== null) {
      clearTimeout(this.imgAnalyzerTimeout);
    }
  };

  /**
   * Starts the image analyzer. It is not immediately called to let the browser to update the view
   *
   * @param isForResize specifies if the image analyzer must be called for resizing or not
   */
  JustifiedGallery.prototype.startImgAnalyzer = function (isForResize) {
    var that = this;
    this.stopImgAnalyzerStarter();
    this.imgAnalyzerTimeout = setTimeout(function () {
      that.analyzeImages(isForResize);
    }, 0.001); // we can't start it immediately due to a IE different behaviour
  };

  /**
   * Checks if the image is loaded or not using another image object. We cannot use the 'complete' image property,
   * because some browsers, with a 404 set complete = true.
   *
   * @param imageSrc the image src to load
   * @param onLoad callback that is called when the image has been loaded
   * @param onError callback that is called in case of an error
   */
  JustifiedGallery.prototype.onImageEvent = function (imageSrc, onLoad, onError) {
    if (!onLoad && !onError) {
      return;
    }
    var memImage = new Image();
    var $memImage = $(memImage);
    if (onLoad) {
      $memImage.one('load', function () {
        $memImage.off('load error');
        onLoad(memImage);
      });
    }
    if (onError) {
      $memImage.one('error', function () {
        $memImage.off('load error');
        onError(memImage);
      });
    }
    memImage.src = imageSrc;
  };

  /**
   * Init of Justified Gallery controlled
   * It analyzes all the entries starting theirs loading and calling the image analyzer (that works with loaded images)
   */
  JustifiedGallery.prototype.init = function () {
    var imagesToLoad = false,
      skippedImages = false,
      that = this;
    $.each(this.entries, function (index, entry) {
      var $entry = $(entry);
      var $image = that.imgFromEntry($entry);
      $entry.addClass('jg-entry');
      if ($entry.data('jg.loaded') !== true && $entry.data('jg.loaded') !== 'skipped') {
        // Link Rel global overwrite
        if (that.settings.rel !== null) {
          $entry.attr('rel', that.settings.rel);
        }

        // Link Target global overwrite
        if (that.settings.target !== null) {
          $entry.attr('target', that.settings.target);
        }
        if ($image !== null) {
          // Image src
          var imageSrc = that.extractImgSrcFromImage($image);
          $image.attr('src', imageSrc);

          /* If we have the height and the width, we don't wait that the image is loaded, but we start directly
          * with the justification */
          if (that.settings.waitThumbnailsLoad === false) {
            var width = parseFloat($image.attr('width'));
            var height = parseFloat($image.attr('height'));
            if (!isNaN(width) && !isNaN(height)) {
              $entry.data('jg.width', width);
              $entry.data('jg.height', height);
              $entry.data('jg.loaded', 'skipped');
              skippedImages = true;
              that.startImgAnalyzer(false);
              return true; // continue
            }
          }

          $entry.data('jg.loaded', false);
          imagesToLoad = true;

          // Spinner start
          if (!that.isSpinnerActive()) {
            that.startLoadingSpinnerAnimation();
          }
          that.onImageEvent(imageSrc, function (loadImg) {
            // image loaded
            $entry.data('jg.width', $entry.find('.envira-gallery-image').data('envira-width'));
            $entry.data('jg.height', $entry.find('.envira-gallery-image').data('envira-height'));
            $entry.data('jg.loaded', true);
            that.startImgAnalyzer(false);
          }, function () {
            // image load error
            $entry.data('jg.loaded', 'error');
            that.startImgAnalyzer(false);
          });
        } else {
          $entry.data('jg.loaded', true);
          $entry.data('jg.width', $entry.width() | parseFloat($entry.css('width')) | 1);
          $entry.data('jg.height', $entry.height() | parseFloat($entry.css('height')) | 1);
        }
      }
    });
    if (!imagesToLoad && !skippedImages) {
      this.startImgAnalyzer(false);
    }
    this.checkWidth();
  };

  /**
   * Checks that it is a valid number. If a string is passed it is converted to a number
   *
   * @param settingContainer the object that contains the setting (to allow the conversion)
   * @param settingName the setting name
   */
  JustifiedGallery.prototype.checkOrConvertNumber = function (settingContainer, settingName) {
    if ($.type(settingContainer[settingName]) === 'string') {
      settingContainer[settingName] = parseFloat(settingContainer[settingName]);
    }
    if ($.type(settingContainer[settingName]) === 'number') {
      if (isNaN(settingContainer[settingName])) {
        throw 'invalid number for ' + settingName;
      }
    } else {
      throw settingName + ' must be a number';
    }
  };

  /**
   * Checks the sizeRangeSuffixes and, if necessary, converts
   * its keys from string (e.g. old settings with 'lt100') to int.
   */
  JustifiedGallery.prototype.checkSizeRangesSuffixes = function () {
    if ($.type(this.settings.sizeRangeSuffixes) !== 'object') {
      throw 'sizeRangeSuffixes must be defined and must be an object';
    }
    var suffixRanges = [];
    for (var rangeIdx in this.settings.sizeRangeSuffixes) {
      if (this.settings.sizeRangeSuffixes.hasOwnProperty(rangeIdx)) {
        suffixRanges.push(rangeIdx);
      }
    }
    var newSizeRngSuffixes = {
      0: ''
    };
    for (var i = 0; i < suffixRanges.length; i++) {
      if ($.type(suffixRanges[i]) === 'string') {
        try {
          var numIdx = parseInt(suffixRanges[i].replace(/^[a-z]+/, ''), 10);
          newSizeRngSuffixes[numIdx] = this.settings.sizeRangeSuffixes[suffixRanges[i]];
        } catch (e) {
          throw 'sizeRangeSuffixes keys must contains correct numbers (' + e + ')';
        }
      } else {
        newSizeRngSuffixes[suffixRanges[i]] = this.settings.sizeRangeSuffixes[suffixRanges[i]];
      }
    }
    this.settings.sizeRangeSuffixes = newSizeRngSuffixes;
  };

  /**
   * check and convert the maxRowHeight setting
   */
  JustifiedGallery.prototype.retrieveMaxRowHeight = function () {
    var newMaxRowHeight = {};
    if ($.type(this.settings.maxRowHeight) === 'string') {
      if (this.settings.maxRowHeight.match(/^[0-9]+%$/)) {
        newMaxRowHeight.value = parseFloat(this.settings.maxRowHeight.match(/^([0-9]+)%$/)[1]) / 100;
        newMaxRowHeight.isPercentage = false;
      } else {
        newMaxRowHeight.value = parseFloat(this.settings.maxRowHeight);
        newMaxRowHeight.isPercentage = true;
      }
    } else if ($.type(this.settings.maxRowHeight) === 'number') {
      newMaxRowHeight.value = this.settings.maxRowHeight;
      newMaxRowHeight.isPercentage = false;
    } else {
      throw 'maxRowHeight must be a number or a percentage';
    }

    // check if the converted value is not a number
    if (isNaN(newMaxRowHeight.value)) {
      throw 'invalid number for maxRowHeight';
    }

    // check values
    if (newMaxRowHeight.isPercentage) {
      if (newMaxRowHeight.value < 100) {
        newMaxRowHeight.value = 100;
      }
    } else {
      if (newMaxRowHeight.value > 0 && newMaxRowHeight.value < this.settings.rowHeight) {
        newMaxRowHeight.value = this.settings.rowHeight;
      }
    }
    return newMaxRowHeight;
  };

  /**
   * Checks the settings
   */
  JustifiedGallery.prototype.checkSettings = function () {
    this.checkSizeRangesSuffixes();
    this.checkOrConvertNumber(this.settings, 'rowHeight');
    this.checkOrConvertNumber(this.settings, 'margins');
    this.checkOrConvertNumber(this.settings, 'border');
    var lastRowModes = ['justify', 'nojustify', 'left', 'center', 'right', 'hide'];
    if (lastRowModes.indexOf(this.settings.lastRow) === -1) {
      throw 'lastRow must be one of: ' + lastRowModes.join(', ');
    }
    this.checkOrConvertNumber(this.settings, 'justifyThreshold');
    if (this.settings.justifyThreshold < 0 || this.settings.justifyThreshold > 1) {
      throw 'justifyThreshold must be in the interval [0,1]';
    }
    if ($.type(this.settings.cssAnimation) !== 'boolean') {
      throw 'cssAnimation must be a boolean';
    }
    if ($.type(this.settings.captions) !== 'boolean') {
      throw 'captions must be a boolean';
    }
    this.checkOrConvertNumber(this.settings.captionSettings, 'animationDuration');
    this.checkOrConvertNumber(this.settings.captionSettings, 'visibleOpacity');
    if (this.settings.captionSettings.visibleOpacity < 0 || this.settings.captionSettings.visibleOpacity > 1) {
      throw 'captionSettings.visibleOpacity must be in the interval [0, 1]';
    }
    this.checkOrConvertNumber(this.settings.captionSettings, 'nonVisibleOpacity');
    if (this.settings.captionSettings.nonVisibleOpacity < 0 || this.settings.captionSettings.nonVisibleOpacity > 1) {
      throw 'captionSettings.nonVisibleOpacity must be in the interval [0, 1]';
    }
    if ($.type(this.settings.fixedHeight) !== 'boolean') {
      throw 'fixedHeight must be a boolean';
    }
    this.checkOrConvertNumber(this.settings, 'imagesAnimationDuration');
    this.checkOrConvertNumber(this.settings, 'refreshTime');
    this.checkOrConvertNumber(this.settings, 'refreshSensitivity');
    if ($.type(this.settings.randomize) !== 'boolean') {
      throw 'randomize must be a boolean';
    }
    if ($.type(this.settings.selector) !== 'string') {
      throw 'selector must be a string';
    }
    if (this.settings.sort !== false && !$.isFunction(this.settings.sort)) {
      throw 'sort must be false or a comparison function';
    }
    if (this.settings.filter !== false && !$.isFunction(this.settings.filter) && $.type(this.settings.filter) !== 'string') {
      throw 'filter must be false, a string or a filter function';
    }
  };

  /**
   * It brings all the indexes from the sizeRangeSuffixes and it orders them. They are then sorted and returned.
   *
   * @returns {Array} sorted suffix ranges
   */
  JustifiedGallery.prototype.retrieveSuffixRanges = function () {
    var suffixRanges = [];
    for (var rangeIdx in this.settings.sizeRangeSuffixes) {
      if (this.settings.sizeRangeSuffixes.hasOwnProperty(rangeIdx)) {
        suffixRanges.push(parseInt(rangeIdx, 10));
      }
    }
    suffixRanges.sort(function (a, b) {
      return a > b ? 1 : a < b ? -1 : 0;
    });
    return suffixRanges;
  };

  /**
   * Update the existing settings only changing some of them
   *
   * @param newSettings the new settings (or a subgroup of them)
   */
  JustifiedGallery.prototype.updateSettings = function (newSettings) {
    // In this case Justified Gallery has been called again changing only some options
    this.settings = $.extend({}, this.settings, newSettings);
    this.checkSettings();

    // As reported in the settings: negative value = same as margins, 0 = disabled
    this.border = this.settings.border >= 0 ? this.settings.border : this.settings.margins;
    this.maxRowHeight = this.retrieveMaxRowHeight();
    this.suffixRanges = this.retrieveSuffixRanges();
  };

  /**
   * Justified Gallery plugin for jQuery
   *
   * Events
   *  - jg.complete : called when all the gallery has been created
   *  - jg.resize : called when the gallery has been resized
   *  - jg.rowflush : when a new row appears
   *
   * @param arg the action (or the settings) passed when the plugin is called
   * @returns {*} the object itself
   */
  $.fn.justifiedGallery = function (arg) {
    return this.each(function (index, gallery) {
      var $gallery = $(gallery);
      $gallery.addClass('envira-justified-gallery');
      var controller = $gallery.data('jg.controller');
      if (typeof controller === 'undefined') {
        // Create controller and assign it to the object data
        if (typeof arg !== 'undefined' && arg !== null && $.type(arg) !== 'object') {
          if (arg === 'destroy') {
            return; // Just a call to an unexisting object
          }

          throw 'The argument must be an object';
        }
        controller = new JustifiedGallery($gallery, $.extend({}, $.fn.justifiedGallery.defaults, arg));
        $gallery.data('jg.controller', controller);
      } else if (arg === 'norewind') {
        // In this case we don't rewind: we analyze only the latest images (e.g. to complete the last unfinished row
        // ... left to be more readable
      } else if (arg === 'destroy') {
        controller.destroy();
        return;
      } else {
        // In this case Justified Gallery has been called again changing only some options
        controller.updateSettings(arg);
        controller.rewind();
      }

      // Update the entries list
      if (!controller.updateEntries(arg === 'norewind')) {
        return;
      }

      // Init justified gallery
      controller.init();
    });
  };

  // Default options
  $.fn.justifiedGallery.defaults = {
    sizeRangeSuffixes: {},
    /* e.g. Flickr configuration
    {
    100: '_t',  // used when longest is less than 100px
    240: '_m',  // used when longest is between 101px and 240px
    320: '_n',  // ...
    500: '',
    640: '_z',
    1024: '_b'  // used as else case because it is the last
    }
    */
    thumbnailPath: undefined,
    /* If defined, sizeRangeSuffixes is not used, and this function is used to determine the
    path relative to a specific thumbnail size. The function should accept respectively three arguments:
    current path, width and height */
    rowHeight: 120,
    maxRowHeight: -1,
    // negative value = no limits, number to express the value in pixels,
    // '[0-9]+%' to express in percentage (e.g. 300% means that the row height
    // can't exceed 3 * rowHeight)
    margins: 1,
    border: -1,
    // negative value = same as margins, 0 = disabled, any other value to set the border

    lastRow: 'nojustify',
    // … which is the same as 'left', or can be 'justify', 'center', 'right' or 'hide'

    justifyThreshold: 0.90,
    /* if row width / available space > 0.90 it will be always justified
    * (i.e. lastRow setting is not considered) */
    fixedHeight: false,
    waitThumbnailsLoad: true,
    captions: true,
    cssAnimation: false,
    imagesAnimationDuration: 500,
    // ignored with css animations
    captionSettings: {
      // ignored with css animations
      animationDuration: 500,
      visibleOpacity: 0.7,
      nonVisibleOpacity: 0.0
    },
    rel: null,
    // rewrite the rel of each analyzed links
    target: null,
    // rewrite the target of all links
    extension: /\.[^.\\/] + $ /,
    // regexp to capture the extension of an image
    refreshTime: 100,
    // time interval (in ms) to check if the page changes its width
    refreshSensitivity: 0,
    // change in width allowed (in px) without re-building the gallery
    randomize: false,
    sort: false,
    /*
    - false: to do not sort
    - function: to sort them using the function as comparator (see Array.prototype.sort())
    */
    filter: false,
    /*
    - false: for a disabled filter
    - a string: an entry is kept if entry.is(filter string) returns true
    see jQuery's .is() function for further information
    - a function: invoked with arguments (entry, index, array). Return true to keep the entry, false otherwise.
    see Array.prototype.filter for further information.
    */
    selector: '> a, > div:not(.spinner)' // The selector that is used to know what are the entries of the gallery
  };
})(jQuery);

/***/ }),

/***/ 477:
/***/ ((module, exports, __webpack_require__) => {

var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
/**
 * Isotope LayoutMode
 */

(function (window, factory) {
  // universal module definition
  /* jshint strict: false */ /*globals define, module, require */
  if (true) {
    // AMD
    !(__WEBPACK_AMD_DEFINE_ARRAY__ = [__webpack_require__(131), __webpack_require__(794)], __WEBPACK_AMD_DEFINE_FACTORY__ = (factory),
		__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
		(__WEBPACK_AMD_DEFINE_FACTORY__.apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__)) : __WEBPACK_AMD_DEFINE_FACTORY__),
		__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
  } else {}
})(window, function factory(getSize, Outlayer) {
  'use strict';

  // layout mode class
  function LayoutMode(isotope) {
    this.isotope = isotope;
    // link properties
    if (isotope) {
      this.options = isotope.options[this.namespace];
      this.element = isotope.element;
      this.items = isotope.filteredItems;
      this.size = isotope.size;
    }
  }
  var proto = LayoutMode.prototype;

  /**
   * some methods should just defer to default Outlayer method
   * and reference the Isotope instance as `this`
   **/
  var facadeMethods = ['_resetLayout', '_getItemLayoutPosition', '_manageStamp', '_getContainerSize', '_getElementOffset', 'needsResizeLayout', '_getOption'];
  facadeMethods.forEach(function (methodName) {
    proto[methodName] = function () {
      return Outlayer.prototype[methodName].apply(this.isotope, arguments);
    };
  });

  // -----  ----- //
  // for horizontal layout modes, check vertical size
  proto.needsVerticalResizeLayout = function () {
    // don't trigger if size did not change
    var size = getSize(this.isotope.element);
    // check that this.size and size are there
    // IE8 triggers resize on body size change, so they might not be
    var hasSizes = this.isotope.size && size;
    return hasSizes && size.innerHeight != this.isotope.size.innerHeight;
  };

  // ----- measurements ----- //
  proto._getMeasurement = function () {
    this.isotope._getMeasurement.apply(this, arguments);
  };
  proto.getColumnWidth = function () {
    this.getSegmentSize('column', 'Width');
  };
  proto.getRowHeight = function () {
    this.getSegmentSize('row', 'Height');
  };

  /**
   * get columnWidth or rowHeight
   * segment: 'column' or 'row'
   * size 'Width' or 'Height'
   **/
  proto.getSegmentSize = function (segment, size) {
    var segmentName = segment + size;
    var outerSize = 'outer' + size;
    // columnWidth / outerWidth // rowHeight / outerHeight
    this._getMeasurement(segmentName, outerSize);
    // got rowHeight or columnWidth, we can chill
    if (this[segmentName]) {
      return;
    }
    // fall back to item of first element
    var firstItemSize = this.getFirstItemSize();
    this[segmentName] = firstItemSize && firstItemSize[outerSize] ||
    // or size of container
    this.isotope.size['inner' + size];
  };
  proto.getFirstItemSize = function () {
    var firstItem = this.isotope.filteredItems[0];
    return firstItem && firstItem.element && getSize(firstItem.element);
  };

  // ----- methods that should reference isotope ----- //
  proto.layout = function () {
    this.isotope.layout.apply(this.isotope, arguments);
  };
  proto.getSize = function () {
    this.isotope.getSize();
    this.size = this.isotope.size;
  };

  // -------------------------- create -------------------------- //
  LayoutMode.modes = {};
  LayoutMode.create = function (namespace, options) {
    function Mode() {
      LayoutMode.apply(this, arguments);
    }
    Mode.prototype = Object.create(proto);
    Mode.prototype.constructor = Mode;

    // default options
    if (options) {
      Mode.options = options;
    }
    Mode.prototype.namespace = namespace;
    // register in Isotope
    LayoutMode.modes[namespace] = Mode;
    return Mode;
  };
  return LayoutMode;
});

/***/ }),

/***/ 620:
/***/ ((module, exports, __webpack_require__) => {

var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
/**
 * fitRows layout mode
 */

(function (window, factory) {
  // universal module definition
  /* jshint strict: false */ /*globals define, module, require */
  if (true) {
    // AMD
    !(__WEBPACK_AMD_DEFINE_ARRAY__ = [__webpack_require__(477)], __WEBPACK_AMD_DEFINE_FACTORY__ = (factory),
		__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
		(__WEBPACK_AMD_DEFINE_FACTORY__.apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__)) : __WEBPACK_AMD_DEFINE_FACTORY__),
		__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
  } else {}
})(window, function factory(LayoutMode) {
  'use strict';

  var FitRows = LayoutMode.create('fitRows');
  var proto = FitRows.prototype;
  proto._resetLayout = function () {
    this.x = 0;
    this.y = 0;
    this.maxY = 0;
    this._getMeasurement('gutter', 'outerWidth');
  };
  proto._getItemLayoutPosition = function (item) {
    item.getSize();
    var itemWidth = item.size.outerWidth + this.gutter;
    // if this element cannot fit in the current row
    var containerWidth = this.isotope.size.innerWidth + this.gutter;
    if (this.x !== 0 && itemWidth + this.x > containerWidth) {
      this.x = 0;
      this.y = this.maxY;
    }
    var position = {
      x: this.x,
      y: this.y
    };
    this.maxY = Math.max(this.maxY, this.y + item.size.outerHeight);
    this.x += itemWidth;
    return position;
  };
  proto._getContainerSize = function () {
    return {
      height: this.maxY
    };
  };
  return FitRows;
});

/***/ }),

/***/ 245:
/***/ ((module, exports, __webpack_require__) => {

var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
/*!
 * Masonry layout mode
 * sub-classes Masonry
 * https://masonry.desandro.com
 */

(function (window, factory) {
  // universal module definition
  /* jshint strict: false */ /*globals define, module, require */
  if (true) {
    // AMD
    !(__WEBPACK_AMD_DEFINE_ARRAY__ = [__webpack_require__(477), __webpack_require__(751)], __WEBPACK_AMD_DEFINE_FACTORY__ = (factory),
		__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
		(__WEBPACK_AMD_DEFINE_FACTORY__.apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__)) : __WEBPACK_AMD_DEFINE_FACTORY__),
		__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
  } else {}
})(window, function factory(LayoutMode, Masonry) {
  'use strict';

  // -------------------------- masonryDefinition -------------------------- //
  // create an Outlayer layout class
  var MasonryMode = LayoutMode.create('masonry');
  var proto = MasonryMode.prototype;
  var keepModeMethods = {
    _getElementOffset: true,
    layout: true,
    _getMeasurement: true
  };

  // inherit Masonry prototype
  for (var method in Masonry.prototype) {
    // do not inherit mode methods
    if (!keepModeMethods[method]) {
      proto[method] = Masonry.prototype[method];
    }
  }
  var measureColumns = proto.measureColumns;
  proto.measureColumns = function () {
    // set items, used if measuring first item
    this.items = this.isotope.filteredItems;
    measureColumns.call(this);
  };

  // point to mode options for fitWidth
  var _getOption = proto._getOption;
  proto._getOption = function (option) {
    if (option == 'fitWidth') {
      return this.options.isFitWidth !== undefined ? this.options.isFitWidth : this.options.fitWidth;
    }
    return _getOption.apply(this.isotope, arguments);
  };
  return MasonryMode;
});

/***/ }),

/***/ 175:
/***/ ((module, exports, __webpack_require__) => {

var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
/**
 * vertical layout mode
 */

(function (window, factory) {
  // universal module definition
  /* jshint strict: false */ /*globals define, module, require */
  if (true) {
    // AMD
    !(__WEBPACK_AMD_DEFINE_ARRAY__ = [__webpack_require__(477)], __WEBPACK_AMD_DEFINE_FACTORY__ = (factory),
		__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
		(__WEBPACK_AMD_DEFINE_FACTORY__.apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__)) : __WEBPACK_AMD_DEFINE_FACTORY__),
		__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
  } else {}
})(window, function factory(LayoutMode) {
  'use strict';

  var Vertical = LayoutMode.create('vertical', {
    horizontalAlignment: 0
  });
  var proto = Vertical.prototype;
  proto._resetLayout = function () {
    this.y = 0;
  };
  proto._getItemLayoutPosition = function (item) {
    item.getSize();
    var x = (this.isotope.size.innerWidth - item.size.outerWidth) * this.options.horizontalAlignment;
    var y = this.y;
    this.y += item.size.outerHeight;
    return {
      x: x,
      y: y
    };
  };
  proto._getContainerSize = function () {
    return {
      height: this.y
    };
  };
  return Vertical;
});

/***/ }),

/***/ 613:
/***/ ((module, exports, __webpack_require__) => {

var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
/*!
 * jQuery Mousewheel 3.1.13
 *
 * Copyright jQuery Foundation and other contributors
 * Released under the MIT license
 * http://jquery.org/license
 */

(function (factory) {
  if (true) {
    // AMD. Register as an anonymous module.
    !(__WEBPACK_AMD_DEFINE_ARRAY__ = [__webpack_require__(311)], __WEBPACK_AMD_DEFINE_FACTORY__ = (factory),
		__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
		(__WEBPACK_AMD_DEFINE_FACTORY__.apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__)) : __WEBPACK_AMD_DEFINE_FACTORY__),
		__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
  } else {}
})(function ($) {
  var toFix = ['wheel', 'mousewheel', 'DOMMouseScroll', 'MozMousePixelScroll'],
    toBind = 'onwheel' in document || document.documentMode >= 9 ? ['wheel'] : ['mousewheel', 'DomMouseScroll', 'MozMousePixelScroll'],
    slice = Array.prototype.slice,
    nullLowestDeltaTimeout,
    lowestDelta;
  if ($.event.fixHooks) {
    for (var i = toFix.length; i;) {
      $.event.fixHooks[toFix[--i]] = $.event.mouseHooks;
    }
  }
  var special = $.event.special.mousewheel = {
    version: '3.1.12',
    setup: function setup() {
      if (this.addEventListener) {
        for (var i = toBind.length; i;) {
          this.addEventListener(toBind[--i], handler, false);
        }
      } else {
        this.onmousewheel = handler;
      }
      // Store the line height and page height for this particular element
      $.data(this, 'mousewheel-line-height', special.getLineHeight(this));
      $.data(this, 'mousewheel-page-height', special.getPageHeight(this));
    },
    teardown: function teardown() {
      if (this.removeEventListener) {
        for (var i = toBind.length; i;) {
          this.removeEventListener(toBind[--i], handler, false);
        }
      } else {
        this.onmousewheel = null;
      }
      // Clean up the data we added to the element
      $.removeData(this, 'mousewheel-line-height');
      $.removeData(this, 'mousewheel-page-height');
    },
    getLineHeight: function getLineHeight(elem) {
      var $elem = $(elem),
        $parent = $elem['offsetParent' in $.fn ? 'offsetParent' : 'parent']();
      if (!$parent.length) {
        $parent = $('body');
      }
      return parseInt($parent.css('fontSize'), 10) || parseInt($elem.css('fontSize'), 10) || 16;
    },
    getPageHeight: function getPageHeight(elem) {
      return $(elem).height();
    },
    settings: {
      adjustOldDeltas: true,
      // see shouldAdjustOldDeltas() below
      normalizeOffset: true // calls getBoundingClientRect for each event
    }
  };

  $.fn.extend({
    mousewheel: function mousewheel(fn) {
      return fn ? this.bind('mousewheel', fn) : this.trigger('mousewheel');
    },
    unmousewheel: function unmousewheel(fn) {
      return this.unbind('mousewheel', fn);
    }
  });
  function handler(event) {
    var orgEvent = event || window.event,
      args = slice.call(arguments, 1),
      delta = 0,
      deltaX = 0,
      deltaY = 0,
      absDelta = 0,
      offsetX = 0,
      offsetY = 0;
    event = $.event.fix(orgEvent);
    event.type = 'mousewheel';

    // Old school scrollwheel delta
    if ('detail' in orgEvent) {
      deltaY = orgEvent.detail * -1;
    }
    if ('wheelDelta' in orgEvent) {
      deltaY = orgEvent.wheelDelta;
    }
    if ('wheelDeltaY' in orgEvent) {
      deltaY = orgEvent.wheelDeltaY;
    }
    if ('wheelDeltaX' in orgEvent) {
      deltaX = orgEvent.wheelDeltaX * -1;
    }

    // Firefox < 17 horizontal scrolling related to DOMMouseScroll event
    if ('axis' in orgEvent && orgEvent.axis === orgEvent.HORIZONTAL_AXIS) {
      deltaX = deltaY * -1;
      deltaY = 0;
    }

    // Set delta to be deltaY or deltaX if deltaY is 0 for backwards compatabilitiy
    delta = deltaY === 0 ? deltaX : deltaY;

    // New school wheel delta (wheel event)
    if ('deltaY' in orgEvent) {
      deltaY = orgEvent.deltaY * -1;
      delta = deltaY;
    }
    if ('deltaX' in orgEvent) {
      deltaX = orgEvent.deltaX;
      if (deltaY === 0) {
        delta = deltaX * -1;
      }
    }

    // No change actually happened, no reason to go any further
    if (deltaY === 0 && deltaX === 0) {
      return;
    }

    // Need to convert lines and pages to pixels if we aren't already in pixels
    // There are three delta modes:
    // * deltaMode 0 is by pixels, nothing to do
    // * deltaMode 1 is by lines
    // * deltaMode 2 is by pages
    if (orgEvent.deltaMode === 1) {
      var lineHeight = $.data(this, 'mousewheel-line-height');
      delta *= lineHeight;
      deltaY *= lineHeight;
      deltaX *= lineHeight;
    } else if (orgEvent.deltaMode === 2) {
      var pageHeight = $.data(this, 'mousewheel-page-height');
      delta *= pageHeight;
      deltaY *= pageHeight;
      deltaX *= pageHeight;
    }

    // Store lowest absolute delta to normalize the delta values
    absDelta = Math.max(Math.abs(deltaY), Math.abs(deltaX));
    if (!lowestDelta || absDelta < lowestDelta) {
      lowestDelta = absDelta;

      // Adjust older deltas if necessary
      if (shouldAdjustOldDeltas(orgEvent, absDelta)) {
        lowestDelta /= 40;
      }
    }

    // Adjust older deltas if necessary
    if (shouldAdjustOldDeltas(orgEvent, absDelta)) {
      // Divide all the things by 40!
      delta /= 40;
      deltaX /= 40;
      deltaY /= 40;
    }

    // Get a whole, normalized value for the deltas
    delta = Math[delta >= 1 ? 'floor' : 'ceil'](delta / lowestDelta);
    deltaX = Math[deltaX >= 1 ? 'floor' : 'ceil'](deltaX / lowestDelta);
    deltaY = Math[deltaY >= 1 ? 'floor' : 'ceil'](deltaY / lowestDelta);

    // Normalise offsetX and offsetY properties
    if (special.settings.normalizeOffset && this.getBoundingClientRect) {
      var boundingRect = this.getBoundingClientRect();
      offsetX = event.clientX - boundingRect.left;
      offsetY = event.clientY - boundingRect.top;
    }

    // Add information to the event object
    event.deltaX = deltaX;
    event.deltaY = deltaY;
    event.deltaFactor = lowestDelta;
    event.offsetX = offsetX;
    event.offsetY = offsetY;
    // Go ahead and set deltaMode to 0 since we converted to pixels
    // Although this is a little odd since we overwrite the deltaX/Y
    // properties with normalized deltas.
    event.deltaMode = 0;

    // Add event and delta to the front of the arguments
    args.unshift(event, delta, deltaX, deltaY);

    // Clearout lowestDelta after sometime to better
    // handle multiple device types that give different
    // a different lowestDelta
    // Ex: trackpad = 3 and mouse wheel = 120
    if (nullLowestDeltaTimeout) {
      clearTimeout(nullLowestDeltaTimeout);
    }
    nullLowestDeltaTimeout = setTimeout(nullLowestDelta, 200);
    return ($.event.dispatch || $.event.handle).apply(this, args);
  }
  function nullLowestDelta() {
    lowestDelta = null;
  }
  function shouldAdjustOldDeltas(orgEvent, absDelta) {
    // If this is an older event and the delta is divisable by 120,
    // then we are assuming that the browser is treating this as an
    // older mouse wheel event and that we should divide the deltas
    // by 40 to try and get a more usable deltaFactor.
    // Side note, this actually impacts the reported scroll distance
    // in older browsers and can cause scrolling to be slower than native.
    // Turn this off by setting $.event.special.mousewheel.settings.adjustOldDeltas to false.
    return special.settings.adjustOldDeltas && orgEvent.type === 'mousewheel' && absDelta % 120 === 0;
  }
});

/***/ }),

/***/ 362:
/***/ (() => {

/*!
 * Envira Pollyfills
 */

/* add compatible Object.entries support for IE 11 */

if (!Object.entries) {
  Object.entries = function (obj) {
    var ownProps = Object.keys(obj),
      i = ownProps.length,
      resArray = new Array(i); // preallocate the Array
    while (i--) {
      resArray[i] = [ownProps[i], obj[ownProps[i]]];
    }
    return resArray;
  };
}

/***/ }),

/***/ 741:
/***/ ((module, exports, __webpack_require__) => {

var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_RESULT__;/**
 * matchesSelector v2.0.2
 * matchesSelector( element, '.selector' )
 * MIT license
 */

/*jshint browser: true, strict: true, undef: true, unused: true */

( function( window, factory ) {
  /*global define: false, module: false */
  'use strict';
  // universal module definition
  if ( true ) {
    // AMD
    !(__WEBPACK_AMD_DEFINE_FACTORY__ = (factory),
		__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
		(__WEBPACK_AMD_DEFINE_FACTORY__.call(exports, __webpack_require__, exports, module)) :
		__WEBPACK_AMD_DEFINE_FACTORY__),
		__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
  } else {}

}( window, function factory() {
  'use strict';

  var matchesMethod = ( function() {
    var ElemProto = window.Element.prototype;
    // check for the standard method name first
    if ( ElemProto.matches ) {
      return 'matches';
    }
    // check un-prefixed
    if ( ElemProto.matchesSelector ) {
      return 'matchesSelector';
    }
    // check vendor prefixes
    var prefixes = [ 'webkit', 'moz', 'ms', 'o' ];

    for ( var i=0; i < prefixes.length; i++ ) {
      var prefix = prefixes[i];
      var method = prefix + 'MatchesSelector';
      if ( ElemProto[ method ] ) {
        return method;
      }
    }
  })();

  return function matchesSelector( elem, selector ) {
    return elem[ matchesMethod ]( selector );
  };

}));


/***/ }),

/***/ 158:
/***/ (function(module, exports, __webpack_require__) {

var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_RESULT__;/**
 * EvEmitter v1.1.0
 * Lil' event emitter
 * MIT License
 */

/* jshint unused: true, undef: true, strict: true */

( function( global, factory ) {
  // universal module definition
  /* jshint strict: false */ /* globals define, module, window */
  if ( true ) {
    // AMD - RequireJS
    !(__WEBPACK_AMD_DEFINE_FACTORY__ = (factory),
		__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
		(__WEBPACK_AMD_DEFINE_FACTORY__.call(exports, __webpack_require__, exports, module)) :
		__WEBPACK_AMD_DEFINE_FACTORY__),
		__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
  } else {}

}( typeof window != 'undefined' ? window : this, function() {

"use strict";

function EvEmitter() {}

var proto = EvEmitter.prototype;

proto.on = function( eventName, listener ) {
  if ( !eventName || !listener ) {
    return;
  }
  // set events hash
  var events = this._events = this._events || {};
  // set listeners array
  var listeners = events[ eventName ] = events[ eventName ] || [];
  // only add once
  if ( listeners.indexOf( listener ) == -1 ) {
    listeners.push( listener );
  }

  return this;
};

proto.once = function( eventName, listener ) {
  if ( !eventName || !listener ) {
    return;
  }
  // add event
  this.on( eventName, listener );
  // set once flag
  // set onceEvents hash
  var onceEvents = this._onceEvents = this._onceEvents || {};
  // set onceListeners object
  var onceListeners = onceEvents[ eventName ] = onceEvents[ eventName ] || {};
  // set flag
  onceListeners[ listener ] = true;

  return this;
};

proto.off = function( eventName, listener ) {
  var listeners = this._events && this._events[ eventName ];
  if ( !listeners || !listeners.length ) {
    return;
  }
  var index = listeners.indexOf( listener );
  if ( index != -1 ) {
    listeners.splice( index, 1 );
  }

  return this;
};

proto.emitEvent = function( eventName, args ) {
  var listeners = this._events && this._events[ eventName ];
  if ( !listeners || !listeners.length ) {
    return;
  }
  // copy over to avoid interference if .off() in listener
  listeners = listeners.slice(0);
  args = args || [];
  // once stuff
  var onceListeners = this._onceEvents && this._onceEvents[ eventName ];

  for ( var i=0; i < listeners.length; i++ ) {
    var listener = listeners[i]
    var isOnce = onceListeners && onceListeners[ listener ];
    if ( isOnce ) {
      // remove listener
      // remove before trigger to prevent recursion
      this.off( eventName, listener );
      // unset once flag
      delete onceListeners[ listener ];
    }
    // trigger listener
    listener.apply( this, args );
  }

  return this;
};

proto.allOff = function() {
  delete this._events;
  delete this._onceEvents;
};

return EvEmitter;

}));


/***/ }),

/***/ 131:
/***/ ((module) => {

/*!
 * Infinite Scroll v2.0.4
 * measure size of elements
 * MIT license
 */

( function( window, factory ) {
  if (  true && module.exports ) {
    // CommonJS
    module.exports = factory();
  } else {
    // browser global
    window.getSize = factory();
  }

} )( window, function factory() {

// -------------------------- helpers -------------------------- //

// get a number from a string, not a percentage
function getStyleSize( value ) {
  let num = parseFloat( value );
  // not a percent like '100%', and a number
  let isValid = value.indexOf('%') == -1 && !isNaN( num );
  return isValid && num;
}

// -------------------------- measurements -------------------------- //

let measurements = [
  'paddingLeft',
  'paddingRight',
  'paddingTop',
  'paddingBottom',
  'marginLeft',
  'marginRight',
  'marginTop',
  'marginBottom',
  'borderLeftWidth',
  'borderRightWidth',
  'borderTopWidth',
  'borderBottomWidth',
];

let measurementsLength = measurements.length;

function getZeroSize() {
  let size = {
    width: 0,
    height: 0,
    innerWidth: 0,
    innerHeight: 0,
    outerWidth: 0,
    outerHeight: 0,
  };
  measurements.forEach( ( measurement ) => {
    size[ measurement ] = 0;
  } );
  return size;
}

// -------------------------- getSize -------------------------- //

function getSize( elem ) {
  // use querySeletor if elem is string
  if ( typeof elem == 'string' ) elem = document.querySelector( elem );

  // do not proceed on non-objects
  let isElement = elem && typeof elem == 'object' && elem.nodeType;
  if ( !isElement ) return;

  let style = getComputedStyle( elem );

  // if hidden, everything is 0
  if ( style.display == 'none' ) return getZeroSize();

  let size = {};
  size.width = elem.offsetWidth;
  size.height = elem.offsetHeight;

  let isBorderBox = size.isBorderBox = style.boxSizing == 'border-box';

  // get all measurements
  measurements.forEach( ( measurement ) => {
    let value = style[ measurement ];
    let num = parseFloat( value );
    // any 'auto', 'medium' value will be 0
    size[ measurement ] = !isNaN( num ) ? num : 0;
  } );

  let paddingWidth = size.paddingLeft + size.paddingRight;
  let paddingHeight = size.paddingTop + size.paddingBottom;
  let marginWidth = size.marginLeft + size.marginRight;
  let marginHeight = size.marginTop + size.marginBottom;
  let borderWidth = size.borderLeftWidth + size.borderRightWidth;
  let borderHeight = size.borderTopWidth + size.borderBottomWidth;

  // overwrite width and height if we can get it from style
  let styleWidth = getStyleSize( style.width );
  if ( styleWidth !== false ) {
    size.width = styleWidth +
      // add padding and border unless it's already including it
      ( isBorderBox ? 0 : paddingWidth + borderWidth );
  }

  let styleHeight = getStyleSize( style.height );
  if ( styleHeight !== false ) {
    size.height = styleHeight +
      // add padding and border unless it's already including it
      ( isBorderBox ? 0 : paddingHeight + borderHeight );
  }

  size.innerWidth = size.width - ( paddingWidth + borderWidth );
  size.innerHeight = size.height - ( paddingHeight + borderHeight );

  size.outerWidth = size.width + marginWidth;
  size.outerHeight = size.height + marginHeight;

  return size;
}

return getSize;

} );


/***/ }),

/***/ 751:
/***/ ((module, exports, __webpack_require__) => {

var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;/*!
 * Masonry v4.2.2
 * Cascading grid layout library
 * https://masonry.desandro.com
 * MIT License
 * by David DeSandro
 */

( function( window, factory ) {
  // universal module definition
  /* jshint strict: false */ /*globals define, module, require */
  if ( true ) {
    // AMD
    !(__WEBPACK_AMD_DEFINE_ARRAY__ = [
        __webpack_require__(794),
        __webpack_require__(291)
      ], __WEBPACK_AMD_DEFINE_FACTORY__ = (factory),
		__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
		(__WEBPACK_AMD_DEFINE_FACTORY__.apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__)) : __WEBPACK_AMD_DEFINE_FACTORY__),
		__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
  } else {}

}( window, function factory( Outlayer, getSize ) {

'use strict';

// -------------------------- masonryDefinition -------------------------- //

  // create an Outlayer layout class
  var Masonry = Outlayer.create('masonry');
  // isFitWidth -> fitWidth
  Masonry.compatOptions.fitWidth = 'isFitWidth';

  var proto = Masonry.prototype;

  proto._resetLayout = function() {
    this.getSize();
    this._getMeasurement( 'columnWidth', 'outerWidth' );
    this._getMeasurement( 'gutter', 'outerWidth' );
    this.measureColumns();

    // reset column Y
    this.colYs = [];
    for ( var i=0; i < this.cols; i++ ) {
      this.colYs.push( 0 );
    }

    this.maxY = 0;
    this.horizontalColIndex = 0;
  };

  proto.measureColumns = function() {
    this.getContainerWidth();
    // if columnWidth is 0, default to outerWidth of first item
    if ( !this.columnWidth ) {
      var firstItem = this.items[0];
      var firstItemElem = firstItem && firstItem.element;
      // columnWidth fall back to item of first element
      this.columnWidth = firstItemElem && getSize( firstItemElem ).outerWidth ||
        // if first elem has no width, default to size of container
        this.containerWidth;
    }

    var columnWidth = this.columnWidth += this.gutter;

    // calculate columns
    var containerWidth = this.containerWidth + this.gutter;
    var cols = containerWidth / columnWidth;
    // fix rounding errors, typically with gutters
    var excess = columnWidth - containerWidth % columnWidth;
    // if overshoot is less than a pixel, round up, otherwise floor it
    var mathMethod = excess && excess < 1 ? 'round' : 'floor';
    cols = Math[ mathMethod ]( cols );
    this.cols = Math.max( cols, 1 );
  };

  proto.getContainerWidth = function() {
    // container is parent if fit width
    var isFitWidth = this._getOption('fitWidth');
    var container = isFitWidth ? this.element.parentNode : this.element;
    // check that this.size and size are there
    // IE8 triggers resize on body size change, so they might not be
    var size = getSize( container );
    this.containerWidth = size && size.innerWidth;
  };

  proto._getItemLayoutPosition = function( item ) {
    item.getSize();
    // how many columns does this brick span
    var remainder = item.size.outerWidth % this.columnWidth;
    var mathMethod = remainder && remainder < 1 ? 'round' : 'ceil';
    // round if off by 1 pixel, otherwise use ceil
    var colSpan = Math[ mathMethod ]( item.size.outerWidth / this.columnWidth );
    colSpan = Math.min( colSpan, this.cols );
    // use horizontal or top column position
    var colPosMethod = this.options.horizontalOrder ?
      '_getHorizontalColPosition' : '_getTopColPosition';
    var colPosition = this[ colPosMethod ]( colSpan, item );
    // position the brick
    var position = {
      x: this.columnWidth * colPosition.col,
      y: colPosition.y
    };
    // apply setHeight to necessary columns
    var setHeight = colPosition.y + item.size.outerHeight;
    var setMax = colSpan + colPosition.col;
    for ( var i = colPosition.col; i < setMax; i++ ) {
      this.colYs[i] = setHeight;
    }

    return position;
  };

  proto._getTopColPosition = function( colSpan ) {
    var colGroup = this._getTopColGroup( colSpan );
    // get the minimum Y value from the columns
    var minimumY = Math.min.apply( Math, colGroup );

    return {
      col: colGroup.indexOf( minimumY ),
      y: minimumY,
    };
  };

  /**
   * @param {Number} colSpan - number of columns the element spans
   * @returns {Array} colGroup
   */
  proto._getTopColGroup = function( colSpan ) {
    if ( colSpan < 2 ) {
      // if brick spans only one column, use all the column Ys
      return this.colYs;
    }

    var colGroup = [];
    // how many different places could this brick fit horizontally
    var groupCount = this.cols + 1 - colSpan;
    // for each group potential horizontal position
    for ( var i = 0; i < groupCount; i++ ) {
      colGroup[i] = this._getColGroupY( i, colSpan );
    }
    return colGroup;
  };

  proto._getColGroupY = function( col, colSpan ) {
    if ( colSpan < 2 ) {
      return this.colYs[ col ];
    }
    // make an array of colY values for that one group
    var groupColYs = this.colYs.slice( col, col + colSpan );
    // and get the max value of the array
    return Math.max.apply( Math, groupColYs );
  };

  // get column position based on horizontal index. #873
  proto._getHorizontalColPosition = function( colSpan, item ) {
    var col = this.horizontalColIndex % this.cols;
    var isOver = colSpan > 1 && col + colSpan > this.cols;
    // shift to next row if item can't fit on current row
    col = isOver ? 0 : col;
    // don't let zero-size items take up space
    var hasSize = item.size.outerWidth && item.size.outerHeight;
    this.horizontalColIndex = hasSize ? col + colSpan : this.horizontalColIndex;

    return {
      col: col,
      y: this._getColGroupY( col, colSpan ),
    };
  };

  proto._manageStamp = function( stamp ) {
    var stampSize = getSize( stamp );
    var offset = this._getElementOffset( stamp );
    // get the columns that this stamp affects
    var isOriginLeft = this._getOption('originLeft');
    var firstX = isOriginLeft ? offset.left : offset.right;
    var lastX = firstX + stampSize.outerWidth;
    var firstCol = Math.floor( firstX / this.columnWidth );
    firstCol = Math.max( 0, firstCol );
    var lastCol = Math.floor( lastX / this.columnWidth );
    // lastCol should not go over if multiple of columnWidth #425
    lastCol -= lastX % this.columnWidth ? 0 : 1;
    lastCol = Math.min( this.cols - 1, lastCol );
    // set colYs to bottom of the stamp

    var isOriginTop = this._getOption('originTop');
    var stampMaxY = ( isOriginTop ? offset.top : offset.bottom ) +
      stampSize.outerHeight;
    for ( var i = firstCol; i <= lastCol; i++ ) {
      this.colYs[i] = Math.max( stampMaxY, this.colYs[i] );
    }
  };

  proto._getContainerSize = function() {
    this.maxY = Math.max.apply( Math, this.colYs );
    var size = {
      height: this.maxY
    };

    if ( this._getOption('fitWidth') ) {
      size.width = this._getContainerFitWidth();
    }

    return size;
  };

  proto._getContainerFitWidth = function() {
    var unusedCols = 0;
    // count unused columns
    var i = this.cols;
    while ( --i ) {
      if ( this.colYs[i] !== 0 ) {
        break;
      }
      unusedCols++;
    }
    // fit container to columns that have been used
    return ( this.cols - unusedCols ) * this.columnWidth - this.gutter;
  };

  proto.needsResizeLayout = function() {
    var previousWidth = this.containerWidth;
    this.getContainerWidth();
    return previousWidth != this.containerWidth;
  };

  return Masonry;

}));


/***/ }),

/***/ 291:
/***/ ((module, exports, __webpack_require__) => {

var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_RESULT__;/*!
 * getSize v2.0.3
 * measure size of elements
 * MIT license
 */

/* jshint browser: true, strict: true, undef: true, unused: true */
/* globals console: false */

( function( window, factory ) {
  /* jshint strict: false */ /* globals define, module */
  if ( true ) {
    // AMD
    !(__WEBPACK_AMD_DEFINE_FACTORY__ = (factory),
		__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
		(__WEBPACK_AMD_DEFINE_FACTORY__.call(exports, __webpack_require__, exports, module)) :
		__WEBPACK_AMD_DEFINE_FACTORY__),
		__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
  } else {}

})( window, function factory() {
'use strict';

// -------------------------- helpers -------------------------- //

// get a number from a string, not a percentage
function getStyleSize( value ) {
  var num = parseFloat( value );
  // not a percent like '100%', and a number
  var isValid = value.indexOf('%') == -1 && !isNaN( num );
  return isValid && num;
}

function noop() {}

var logError = typeof console == 'undefined' ? noop :
  function( message ) {
    console.error( message );
  };

// -------------------------- measurements -------------------------- //

var measurements = [
  'paddingLeft',
  'paddingRight',
  'paddingTop',
  'paddingBottom',
  'marginLeft',
  'marginRight',
  'marginTop',
  'marginBottom',
  'borderLeftWidth',
  'borderRightWidth',
  'borderTopWidth',
  'borderBottomWidth'
];

var measurementsLength = measurements.length;

function getZeroSize() {
  var size = {
    width: 0,
    height: 0,
    innerWidth: 0,
    innerHeight: 0,
    outerWidth: 0,
    outerHeight: 0
  };
  for ( var i=0; i < measurementsLength; i++ ) {
    var measurement = measurements[i];
    size[ measurement ] = 0;
  }
  return size;
}

// -------------------------- getStyle -------------------------- //

/**
 * getStyle, get style of element, check for Firefox bug
 * https://bugzilla.mozilla.org/show_bug.cgi?id=548397
 */
function getStyle( elem ) {
  var style = getComputedStyle( elem );
  if ( !style ) {
    logError( 'Style returned ' + style +
      '. Are you running this code in a hidden iframe on Firefox? ' +
      'See https://bit.ly/getsizebug1' );
  }
  return style;
}

// -------------------------- setup -------------------------- //

var isSetup = false;

var isBoxSizeOuter;

/**
 * setup
 * check isBoxSizerOuter
 * do on first getSize() rather than on page load for Firefox bug
 */
function setup() {
  // setup once
  if ( isSetup ) {
    return;
  }
  isSetup = true;

  // -------------------------- box sizing -------------------------- //

  /**
   * Chrome & Safari measure the outer-width on style.width on border-box elems
   * IE11 & Firefox<29 measures the inner-width
   */
  var div = document.createElement('div');
  div.style.width = '200px';
  div.style.padding = '1px 2px 3px 4px';
  div.style.borderStyle = 'solid';
  div.style.borderWidth = '1px 2px 3px 4px';
  div.style.boxSizing = 'border-box';

  var body = document.body || document.documentElement;
  body.appendChild( div );
  var style = getStyle( div );
  // round value for browser zoom. desandro/masonry#928
  isBoxSizeOuter = Math.round( getStyleSize( style.width ) ) == 200;
  getSize.isBoxSizeOuter = isBoxSizeOuter;

  body.removeChild( div );
}

// -------------------------- getSize -------------------------- //

function getSize( elem ) {
  setup();

  // use querySeletor if elem is string
  if ( typeof elem == 'string' ) {
    elem = document.querySelector( elem );
  }

  // do not proceed on non-objects
  if ( !elem || typeof elem != 'object' || !elem.nodeType ) {
    return;
  }

  var style = getStyle( elem );

  // if hidden, everything is 0
  if ( style.display == 'none' ) {
    return getZeroSize();
  }

  var size = {};
  size.width = elem.offsetWidth;
  size.height = elem.offsetHeight;

  var isBorderBox = size.isBorderBox = style.boxSizing == 'border-box';

  // get all measurements
  for ( var i=0; i < measurementsLength; i++ ) {
    var measurement = measurements[i];
    var value = style[ measurement ];
    var num = parseFloat( value );
    // any 'auto', 'medium' value will be 0
    size[ measurement ] = !isNaN( num ) ? num : 0;
  }

  var paddingWidth = size.paddingLeft + size.paddingRight;
  var paddingHeight = size.paddingTop + size.paddingBottom;
  var marginWidth = size.marginLeft + size.marginRight;
  var marginHeight = size.marginTop + size.marginBottom;
  var borderWidth = size.borderLeftWidth + size.borderRightWidth;
  var borderHeight = size.borderTopWidth + size.borderBottomWidth;

  var isBorderBoxSizeOuter = isBorderBox && isBoxSizeOuter;

  // overwrite width and height if we can get it from style
  var styleWidth = getStyleSize( style.width );
  if ( styleWidth !== false ) {
    size.width = styleWidth +
      // add padding and border unless it's already including it
      ( isBorderBoxSizeOuter ? 0 : paddingWidth + borderWidth );
  }

  var styleHeight = getStyleSize( style.height );
  if ( styleHeight !== false ) {
    size.height = styleHeight +
      // add padding and border unless it's already including it
      ( isBorderBoxSizeOuter ? 0 : paddingHeight + borderHeight );
  }

  size.innerWidth = size.width - ( paddingWidth + borderWidth );
  size.innerHeight = size.height - ( paddingHeight + borderHeight );

  size.outerWidth = size.width + marginWidth;
  size.outerHeight = size.height + marginHeight;

  return size;
}

return getSize;

});


/***/ }),

/***/ 652:
/***/ ((module, exports, __webpack_require__) => {

var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;/**
 * Outlayer Item
 */

( function( window, factory ) {
  // universal module definition
  /* jshint strict: false */ /* globals define, module, require */
  if ( true ) {
    // AMD - RequireJS
    !(__WEBPACK_AMD_DEFINE_ARRAY__ = [
        __webpack_require__(158),
        __webpack_require__(69)
      ], __WEBPACK_AMD_DEFINE_FACTORY__ = (factory),
		__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
		(__WEBPACK_AMD_DEFINE_FACTORY__.apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__)) : __WEBPACK_AMD_DEFINE_FACTORY__),
		__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
  } else {}

}( window, function factory( EvEmitter, getSize ) {
'use strict';

// ----- helpers ----- //

function isEmptyObj( obj ) {
  for ( var prop in obj ) {
    return false;
  }
  prop = null;
  return true;
}

// -------------------------- CSS3 support -------------------------- //


var docElemStyle = document.documentElement.style;

var transitionProperty = typeof docElemStyle.transition == 'string' ?
  'transition' : 'WebkitTransition';
var transformProperty = typeof docElemStyle.transform == 'string' ?
  'transform' : 'WebkitTransform';

var transitionEndEvent = {
  WebkitTransition: 'webkitTransitionEnd',
  transition: 'transitionend'
}[ transitionProperty ];

// cache all vendor properties that could have vendor prefix
var vendorProperties = {
  transform: transformProperty,
  transition: transitionProperty,
  transitionDuration: transitionProperty + 'Duration',
  transitionProperty: transitionProperty + 'Property',
  transitionDelay: transitionProperty + 'Delay'
};

// -------------------------- Item -------------------------- //

function Item( element, layout ) {
  if ( !element ) {
    return;
  }

  this.element = element;
  // parent layout class, i.e. Masonry, Isotope, or Packery
  this.layout = layout;
  this.position = {
    x: 0,
    y: 0
  };

  this._create();
}

// inherit EvEmitter
var proto = Item.prototype = Object.create( EvEmitter.prototype );
proto.constructor = Item;

proto._create = function() {
  // transition objects
  this._transn = {
    ingProperties: {},
    clean: {},
    onEnd: {}
  };

  this.css({
    position: 'absolute'
  });
};

// trigger specified handler for event type
proto.handleEvent = function( event ) {
  var method = 'on' + event.type;
  if ( this[ method ] ) {
    this[ method ]( event );
  }
};

proto.getSize = function() {
  this.size = getSize( this.element );
};

/**
 * apply CSS styles to element
 * @param {Object} style
 */
proto.css = function( style ) {
  var elemStyle = this.element.style;

  for ( var prop in style ) {
    // use vendor property if available
    var supportedProp = vendorProperties[ prop ] || prop;
    elemStyle[ supportedProp ] = style[ prop ];
  }
};

 // measure position, and sets it
proto.getPosition = function() {
  var style = getComputedStyle( this.element );
  var isOriginLeft = this.layout._getOption('originLeft');
  var isOriginTop = this.layout._getOption('originTop');
  var xValue = style[ isOriginLeft ? 'left' : 'right' ];
  var yValue = style[ isOriginTop ? 'top' : 'bottom' ];
  var x = parseFloat( xValue );
  var y = parseFloat( yValue );
  // convert percent to pixels
  var layoutSize = this.layout.size;
  if ( xValue.indexOf('%') != -1 ) {
    x = ( x / 100 ) * layoutSize.width;
  }
  if ( yValue.indexOf('%') != -1 ) {
    y = ( y / 100 ) * layoutSize.height;
  }
  // clean up 'auto' or other non-integer values
  x = isNaN( x ) ? 0 : x;
  y = isNaN( y ) ? 0 : y;
  // remove padding from measurement
  x -= isOriginLeft ? layoutSize.paddingLeft : layoutSize.paddingRight;
  y -= isOriginTop ? layoutSize.paddingTop : layoutSize.paddingBottom;

  this.position.x = x;
  this.position.y = y;
};

// set settled position, apply padding
proto.layoutPosition = function() {
  var layoutSize = this.layout.size;
  var style = {};
  var isOriginLeft = this.layout._getOption('originLeft');
  var isOriginTop = this.layout._getOption('originTop');

  // x
  var xPadding = isOriginLeft ? 'paddingLeft' : 'paddingRight';
  var xProperty = isOriginLeft ? 'left' : 'right';
  var xResetProperty = isOriginLeft ? 'right' : 'left';

  var x = this.position.x + layoutSize[ xPadding ];
  // set in percentage or pixels
  style[ xProperty ] = this.getXValue( x );
  // reset other property
  style[ xResetProperty ] = '';

  // y
  var yPadding = isOriginTop ? 'paddingTop' : 'paddingBottom';
  var yProperty = isOriginTop ? 'top' : 'bottom';
  var yResetProperty = isOriginTop ? 'bottom' : 'top';

  var y = this.position.y + layoutSize[ yPadding ];
  // set in percentage or pixels
  style[ yProperty ] = this.getYValue( y );
  // reset other property
  style[ yResetProperty ] = '';

  this.css( style );
  this.emitEvent( 'layout', [ this ] );
};

proto.getXValue = function( x ) {
  var isHorizontal = this.layout._getOption('horizontal');
  return this.layout.options.percentPosition && !isHorizontal ?
    ( ( x / this.layout.size.width ) * 100 ) + '%' : x + 'px';
};

proto.getYValue = function( y ) {
  var isHorizontal = this.layout._getOption('horizontal');
  return this.layout.options.percentPosition && isHorizontal ?
    ( ( y / this.layout.size.height ) * 100 ) + '%' : y + 'px';
};

proto._transitionTo = function( x, y ) {
  this.getPosition();
  // get current x & y from top/left
  var curX = this.position.x;
  var curY = this.position.y;

  var didNotMove = x == this.position.x && y == this.position.y;

  // save end position
  this.setPosition( x, y );

  // if did not move and not transitioning, just go to layout
  if ( didNotMove && !this.isTransitioning ) {
    this.layoutPosition();
    return;
  }

  var transX = x - curX;
  var transY = y - curY;
  var transitionStyle = {};
  transitionStyle.transform = this.getTranslate( transX, transY );

  this.transition({
    to: transitionStyle,
    onTransitionEnd: {
      transform: this.layoutPosition
    },
    isCleaning: true
  });
};

proto.getTranslate = function( x, y ) {
  // flip cooridinates if origin on right or bottom
  var isOriginLeft = this.layout._getOption('originLeft');
  var isOriginTop = this.layout._getOption('originTop');
  x = isOriginLeft ? x : -x;
  y = isOriginTop ? y : -y;
  return 'translate3d(' + x + 'px, ' + y + 'px, 0)';
};

// non transition + transform support
proto.goTo = function( x, y ) {
  this.setPosition( x, y );
  this.layoutPosition();
};

proto.moveTo = proto._transitionTo;

proto.setPosition = function( x, y ) {
  this.position.x = parseFloat( x );
  this.position.y = parseFloat( y );
};

// ----- transition ----- //

/**
 * @param {Object} style - CSS
 * @param {Function} onTransitionEnd
 */

// non transition, just trigger callback
proto._nonTransition = function( args ) {
  this.css( args.to );
  if ( args.isCleaning ) {
    this._removeStyles( args.to );
  }
  for ( var prop in args.onTransitionEnd ) {
    args.onTransitionEnd[ prop ].call( this );
  }
};

/**
 * proper transition
 * @param {Object} args - arguments
 *   @param {Object} to - style to transition to
 *   @param {Object} from - style to start transition from
 *   @param {Boolean} isCleaning - removes transition styles after transition
 *   @param {Function} onTransitionEnd - callback
 */
proto.transition = function( args ) {
  // redirect to nonTransition if no transition duration
  if ( !parseFloat( this.layout.options.transitionDuration ) ) {
    this._nonTransition( args );
    return;
  }

  var _transition = this._transn;
  // keep track of onTransitionEnd callback by css property
  for ( var prop in args.onTransitionEnd ) {
    _transition.onEnd[ prop ] = args.onTransitionEnd[ prop ];
  }
  // keep track of properties that are transitioning
  for ( prop in args.to ) {
    _transition.ingProperties[ prop ] = true;
    // keep track of properties to clean up when transition is done
    if ( args.isCleaning ) {
      _transition.clean[ prop ] = true;
    }
  }

  // set from styles
  if ( args.from ) {
    this.css( args.from );
    // force redraw. http://blog.alexmaccaw.com/css-transitions
    var h = this.element.offsetHeight;
    // hack for JSHint to hush about unused var
    h = null;
  }
  // enable transition
  this.enableTransition( args.to );
  // set styles that are transitioning
  this.css( args.to );

  this.isTransitioning = true;

};

// dash before all cap letters, including first for
// WebkitTransform => -webkit-transform
function toDashedAll( str ) {
  return str.replace( /([A-Z])/g, function( $1 ) {
    return '-' + $1.toLowerCase();
  });
}

var transitionProps = 'opacity,' + toDashedAll( transformProperty );

proto.enableTransition = function(/* style */) {
  // HACK changing transitionProperty during a transition
  // will cause transition to jump
  if ( this.isTransitioning ) {
    return;
  }

  // make `transition: foo, bar, baz` from style object
  // HACK un-comment this when enableTransition can work
  // while a transition is happening
  // var transitionValues = [];
  // for ( var prop in style ) {
  //   // dash-ify camelCased properties like WebkitTransition
  //   prop = vendorProperties[ prop ] || prop;
  //   transitionValues.push( toDashedAll( prop ) );
  // }
  // munge number to millisecond, to match stagger
  var duration = this.layout.options.transitionDuration;
  duration = typeof duration == 'number' ? duration + 'ms' : duration;
  // enable transition styles
  this.css({
    transitionProperty: transitionProps,
    transitionDuration: duration,
    transitionDelay: this.staggerDelay || 0
  });
  // listen for transition end event
  this.element.addEventListener( transitionEndEvent, this, false );
};

// ----- events ----- //

proto.onwebkitTransitionEnd = function( event ) {
  this.ontransitionend( event );
};

proto.onotransitionend = function( event ) {
  this.ontransitionend( event );
};

// properties that I munge to make my life easier
var dashedVendorProperties = {
  '-webkit-transform': 'transform'
};

proto.ontransitionend = function( event ) {
  // disregard bubbled events from children
  if ( event.target !== this.element ) {
    return;
  }
  var _transition = this._transn;
  // get property name of transitioned property, convert to prefix-free
  var propertyName = dashedVendorProperties[ event.propertyName ] || event.propertyName;

  // remove property that has completed transitioning
  delete _transition.ingProperties[ propertyName ];
  // check if any properties are still transitioning
  if ( isEmptyObj( _transition.ingProperties ) ) {
    // all properties have completed transitioning
    this.disableTransition();
  }
  // clean style
  if ( propertyName in _transition.clean ) {
    // clean up style
    this.element.style[ event.propertyName ] = '';
    delete _transition.clean[ propertyName ];
  }
  // trigger onTransitionEnd callback
  if ( propertyName in _transition.onEnd ) {
    var onTransitionEnd = _transition.onEnd[ propertyName ];
    onTransitionEnd.call( this );
    delete _transition.onEnd[ propertyName ];
  }

  this.emitEvent( 'transitionEnd', [ this ] );
};

proto.disableTransition = function() {
  this.removeTransitionStyles();
  this.element.removeEventListener( transitionEndEvent, this, false );
  this.isTransitioning = false;
};

/**
 * removes style property from element
 * @param {Object} style
**/
proto._removeStyles = function( style ) {
  // clean up transition styles
  var cleanStyle = {};
  for ( var prop in style ) {
    cleanStyle[ prop ] = '';
  }
  this.css( cleanStyle );
};

var cleanTransitionStyle = {
  transitionProperty: '',
  transitionDuration: '',
  transitionDelay: ''
};

proto.removeTransitionStyles = function() {
  // remove transition
  this.css( cleanTransitionStyle );
};

// ----- stagger ----- //

proto.stagger = function( delay ) {
  delay = isNaN( delay ) ? 0 : delay;
  this.staggerDelay = delay + 'ms';
};

// ----- show/hide/remove ----- //

// remove element from DOM
proto.removeElem = function() {
  this.element.parentNode.removeChild( this.element );
  // remove display: none
  this.css({ display: '' });
  this.emitEvent( 'remove', [ this ] );
};

proto.remove = function() {
  // just remove element if no transition support or no transition
  if ( !transitionProperty || !parseFloat( this.layout.options.transitionDuration ) ) {
    this.removeElem();
    return;
  }

  // start transition
  this.once( 'transitionEnd', function() {
    this.removeElem();
  });
  this.hide();
};

proto.reveal = function() {
  delete this.isHidden;
  // remove display: none
  this.css({ display: '' });

  var options = this.layout.options;

  var onTransitionEnd = {};
  var transitionEndProperty = this.getHideRevealTransitionEndProperty('visibleStyle');
  onTransitionEnd[ transitionEndProperty ] = this.onRevealTransitionEnd;

  this.transition({
    from: options.hiddenStyle,
    to: options.visibleStyle,
    isCleaning: true,
    onTransitionEnd: onTransitionEnd
  });
};

proto.onRevealTransitionEnd = function() {
  // check if still visible
  // during transition, item may have been hidden
  if ( !this.isHidden ) {
    this.emitEvent('reveal');
  }
};

/**
 * get style property use for hide/reveal transition end
 * @param {String} styleProperty - hiddenStyle/visibleStyle
 * @returns {String}
 */
proto.getHideRevealTransitionEndProperty = function( styleProperty ) {
  var optionStyle = this.layout.options[ styleProperty ];
  // use opacity
  if ( optionStyle.opacity ) {
    return 'opacity';
  }
  // get first property
  for ( var prop in optionStyle ) {
    return prop;
  }
};

proto.hide = function() {
  // set flag
  this.isHidden = true;
  // remove display: none
  this.css({ display: '' });

  var options = this.layout.options;

  var onTransitionEnd = {};
  var transitionEndProperty = this.getHideRevealTransitionEndProperty('hiddenStyle');
  onTransitionEnd[ transitionEndProperty ] = this.onHideTransitionEnd;

  this.transition({
    from: options.visibleStyle,
    to: options.hiddenStyle,
    // keep hidden stuff hidden
    isCleaning: true,
    onTransitionEnd: onTransitionEnd
  });
};

proto.onHideTransitionEnd = function() {
  // check if still hidden
  // during transition, item may have been un-hidden
  if ( this.isHidden ) {
    this.css({ display: 'none' });
    this.emitEvent('hide');
  }
};

proto.destroy = function() {
  this.css({
    position: '',
    left: '',
    right: '',
    top: '',
    bottom: '',
    transition: '',
    transform: ''
  });
};

return Item;

}));


/***/ }),

/***/ 95:
/***/ ((module, exports, __webpack_require__) => {

var __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;/**
 * Fizzy UI utils v2.0.7
 * MIT license
 */

/*jshint browser: true, undef: true, unused: true, strict: true */

( function( window, factory ) {
  // universal module definition
  /*jshint strict: false */ /*globals define, module, require */

  if ( true ) {
    // AMD
    !(__WEBPACK_AMD_DEFINE_ARRAY__ = [
      __webpack_require__(741)
    ], __WEBPACK_AMD_DEFINE_RESULT__ = (function( matchesSelector ) {
      return factory( window, matchesSelector );
    }).apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__),
		__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
  } else {}

}( window, function factory( window, matchesSelector ) {

'use strict';

var utils = {};

// ----- extend ----- //

// extends objects
utils.extend = function( a, b ) {
  for ( var prop in b ) {
    a[ prop ] = b[ prop ];
  }
  return a;
};

// ----- modulo ----- //

utils.modulo = function( num, div ) {
  return ( ( num % div ) + div ) % div;
};

// ----- makeArray ----- //

var arraySlice = Array.prototype.slice;

// turn element or nodeList into an array
utils.makeArray = function( obj ) {
  if ( Array.isArray( obj ) ) {
    // use object if already an array
    return obj;
  }
  // return empty array if undefined or null. #6
  if ( obj === null || obj === undefined ) {
    return [];
  }

  var isArrayLike = typeof obj == 'object' && typeof obj.length == 'number';
  if ( isArrayLike ) {
    // convert nodeList to array
    return arraySlice.call( obj );
  }

  // array of single index
  return [ obj ];
};

// ----- removeFrom ----- //

utils.removeFrom = function( ary, obj ) {
  var index = ary.indexOf( obj );
  if ( index != -1 ) {
    ary.splice( index, 1 );
  }
};

// ----- getParent ----- //

utils.getParent = function( elem, selector ) {
  while ( elem.parentNode && elem != document.body ) {
    elem = elem.parentNode;
    if ( matchesSelector( elem, selector ) ) {
      return elem;
    }
  }
};

// ----- getQueryElement ----- //

// use element as selector string
utils.getQueryElement = function( elem ) {
  if ( typeof elem == 'string' ) {
    return document.querySelector( elem );
  }
  return elem;
};

// ----- handleEvent ----- //

// enable .ontype to trigger from .addEventListener( elem, 'type' )
utils.handleEvent = function( event ) {
  var method = 'on' + event.type;
  if ( this[ method ] ) {
    this[ method ]( event );
  }
};

// ----- filterFindElements ----- //

utils.filterFindElements = function( elems, selector ) {
  // make array of elems
  elems = utils.makeArray( elems );
  var ffElems = [];

  elems.forEach( function( elem ) {
    // check that elem is an actual element
    if ( !( elem instanceof HTMLElement ) ) {
      return;
    }
    // add elem if no selector
    if ( !selector ) {
      ffElems.push( elem );
      return;
    }
    // filter & find items if we have a selector
    // filter
    if ( matchesSelector( elem, selector ) ) {
      ffElems.push( elem );
    }
    // find children
    var childElems = elem.querySelectorAll( selector );
    // concat childElems to filterFound array
    for ( var i=0; i < childElems.length; i++ ) {
      ffElems.push( childElems[i] );
    }
  });

  return ffElems;
};

// ----- debounceMethod ----- //

utils.debounceMethod = function( _class, methodName, threshold ) {
  threshold = threshold || 100;
  // original method
  var method = _class.prototype[ methodName ];
  var timeoutName = methodName + 'Timeout';

  _class.prototype[ methodName ] = function() {
    var timeout = this[ timeoutName ];
    clearTimeout( timeout );

    var args = arguments;
    var _this = this;
    this[ timeoutName ] = setTimeout( function() {
      method.apply( _this, args );
      delete _this[ timeoutName ];
    }, threshold );
  };
};

// ----- docReady ----- //

utils.docReady = function( callback ) {
  var readyState = document.readyState;
  if ( readyState == 'complete' || readyState == 'interactive' ) {
    // do async to allow for other scripts to run. metafizzy/flickity#441
    setTimeout( callback );
  } else {
    document.addEventListener( 'DOMContentLoaded', callback );
  }
};

// ----- htmlInit ----- //

// http://jamesroberts.name/blog/2010/02/22/string-functions-for-javascript-trim-to-camel-case-to-dashed-and-to-underscore/
utils.toDashed = function( str ) {
  return str.replace( /(.)([A-Z])/g, function( match, $1, $2 ) {
    return $1 + '-' + $2;
  }).toLowerCase();
};

var console = window.console;
/**
 * allow user to initialize classes via [data-namespace] or .js-namespace class
 * htmlInit( Widget, 'widgetName' )
 * options are parsed from data-namespace-options
 */
utils.htmlInit = function( WidgetClass, namespace ) {
  utils.docReady( function() {
    var dashedNamespace = utils.toDashed( namespace );
    var dataAttr = 'data-' + dashedNamespace;
    var dataAttrElems = document.querySelectorAll( '[' + dataAttr + ']' );
    var jsDashElems = document.querySelectorAll( '.js-' + dashedNamespace );
    var elems = utils.makeArray( dataAttrElems )
      .concat( utils.makeArray( jsDashElems ) );
    var dataOptionsAttr = dataAttr + '-options';
    var jQuery = window.jQuery;

    elems.forEach( function( elem ) {
      var attr = elem.getAttribute( dataAttr ) ||
        elem.getAttribute( dataOptionsAttr );
      var options;
      try {
        options = attr && JSON.parse( attr );
      } catch ( error ) {
        // log error, do not initialize
        if ( console ) {
          console.error( 'Error parsing ' + dataAttr + ' on ' + elem.className +
          ': ' + error );
        }
        return;
      }
      // initialize
      var instance = new WidgetClass( elem, options );
      // make available via $().data('namespace')
      if ( jQuery ) {
        jQuery.data( elem, namespace, instance );
      }
    });

  });
};

// -----  ----- //

return utils;

}));


/***/ }),

/***/ 69:
/***/ ((module, exports, __webpack_require__) => {

var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_RESULT__;/*!
 * getSize v2.0.3
 * measure size of elements
 * MIT license
 */

/* jshint browser: true, strict: true, undef: true, unused: true */
/* globals console: false */

( function( window, factory ) {
  /* jshint strict: false */ /* globals define, module */
  if ( true ) {
    // AMD
    !(__WEBPACK_AMD_DEFINE_FACTORY__ = (factory),
		__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
		(__WEBPACK_AMD_DEFINE_FACTORY__.call(exports, __webpack_require__, exports, module)) :
		__WEBPACK_AMD_DEFINE_FACTORY__),
		__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
  } else {}

})( window, function factory() {
'use strict';

// -------------------------- helpers -------------------------- //

// get a number from a string, not a percentage
function getStyleSize( value ) {
  var num = parseFloat( value );
  // not a percent like '100%', and a number
  var isValid = value.indexOf('%') == -1 && !isNaN( num );
  return isValid && num;
}

function noop() {}

var logError = typeof console == 'undefined' ? noop :
  function( message ) {
    console.error( message );
  };

// -------------------------- measurements -------------------------- //

var measurements = [
  'paddingLeft',
  'paddingRight',
  'paddingTop',
  'paddingBottom',
  'marginLeft',
  'marginRight',
  'marginTop',
  'marginBottom',
  'borderLeftWidth',
  'borderRightWidth',
  'borderTopWidth',
  'borderBottomWidth'
];

var measurementsLength = measurements.length;

function getZeroSize() {
  var size = {
    width: 0,
    height: 0,
    innerWidth: 0,
    innerHeight: 0,
    outerWidth: 0,
    outerHeight: 0
  };
  for ( var i=0; i < measurementsLength; i++ ) {
    var measurement = measurements[i];
    size[ measurement ] = 0;
  }
  return size;
}

// -------------------------- getStyle -------------------------- //

/**
 * getStyle, get style of element, check for Firefox bug
 * https://bugzilla.mozilla.org/show_bug.cgi?id=548397
 */
function getStyle( elem ) {
  var style = getComputedStyle( elem );
  if ( !style ) {
    logError( 'Style returned ' + style +
      '. Are you running this code in a hidden iframe on Firefox? ' +
      'See https://bit.ly/getsizebug1' );
  }
  return style;
}

// -------------------------- setup -------------------------- //

var isSetup = false;

var isBoxSizeOuter;

/**
 * setup
 * check isBoxSizerOuter
 * do on first getSize() rather than on page load for Firefox bug
 */
function setup() {
  // setup once
  if ( isSetup ) {
    return;
  }
  isSetup = true;

  // -------------------------- box sizing -------------------------- //

  /**
   * Chrome & Safari measure the outer-width on style.width on border-box elems
   * IE11 & Firefox<29 measures the inner-width
   */
  var div = document.createElement('div');
  div.style.width = '200px';
  div.style.padding = '1px 2px 3px 4px';
  div.style.borderStyle = 'solid';
  div.style.borderWidth = '1px 2px 3px 4px';
  div.style.boxSizing = 'border-box';

  var body = document.body || document.documentElement;
  body.appendChild( div );
  var style = getStyle( div );
  // round value for browser zoom. desandro/masonry#928
  isBoxSizeOuter = Math.round( getStyleSize( style.width ) ) == 200;
  getSize.isBoxSizeOuter = isBoxSizeOuter;

  body.removeChild( div );
}

// -------------------------- getSize -------------------------- //

function getSize( elem ) {
  setup();

  // use querySeletor if elem is string
  if ( typeof elem == 'string' ) {
    elem = document.querySelector( elem );
  }

  // do not proceed on non-objects
  if ( !elem || typeof elem != 'object' || !elem.nodeType ) {
    return;
  }

  var style = getStyle( elem );

  // if hidden, everything is 0
  if ( style.display == 'none' ) {
    return getZeroSize();
  }

  var size = {};
  size.width = elem.offsetWidth;
  size.height = elem.offsetHeight;

  var isBorderBox = size.isBorderBox = style.boxSizing == 'border-box';

  // get all measurements
  for ( var i=0; i < measurementsLength; i++ ) {
    var measurement = measurements[i];
    var value = style[ measurement ];
    var num = parseFloat( value );
    // any 'auto', 'medium' value will be 0
    size[ measurement ] = !isNaN( num ) ? num : 0;
  }

  var paddingWidth = size.paddingLeft + size.paddingRight;
  var paddingHeight = size.paddingTop + size.paddingBottom;
  var marginWidth = size.marginLeft + size.marginRight;
  var marginHeight = size.marginTop + size.marginBottom;
  var borderWidth = size.borderLeftWidth + size.borderRightWidth;
  var borderHeight = size.borderTopWidth + size.borderBottomWidth;

  var isBorderBoxSizeOuter = isBorderBox && isBoxSizeOuter;

  // overwrite width and height if we can get it from style
  var styleWidth = getStyleSize( style.width );
  if ( styleWidth !== false ) {
    size.width = styleWidth +
      // add padding and border unless it's already including it
      ( isBorderBoxSizeOuter ? 0 : paddingWidth + borderWidth );
  }

  var styleHeight = getStyleSize( style.height );
  if ( styleHeight !== false ) {
    size.height = styleHeight +
      // add padding and border unless it's already including it
      ( isBorderBoxSizeOuter ? 0 : paddingHeight + borderHeight );
  }

  size.innerWidth = size.width - ( paddingWidth + borderWidth );
  size.innerHeight = size.height - ( paddingHeight + borderHeight );

  size.outerWidth = size.width + marginWidth;
  size.outerHeight = size.height + marginHeight;

  return size;
}

return getSize;

});


/***/ }),

/***/ 794:
/***/ ((module, exports, __webpack_require__) => {

var __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;/*!
 * Outlayer v2.1.1
 * the brains and guts of a layout library
 * MIT license
 */

( function( window, factory ) {
  'use strict';
  // universal module definition
  /* jshint strict: false */ /* globals define, module, require */
  if ( true ) {
    // AMD - RequireJS
    !(__WEBPACK_AMD_DEFINE_ARRAY__ = [
        __webpack_require__(158),
        __webpack_require__(69),
        __webpack_require__(95),
        __webpack_require__(652)
      ], __WEBPACK_AMD_DEFINE_RESULT__ = (function( EvEmitter, getSize, utils, Item ) {
        return factory( window, EvEmitter, getSize, utils, Item);
      }).apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__),
		__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
  } else {}

}( window, function factory( window, EvEmitter, getSize, utils, Item ) {
'use strict';

// ----- vars ----- //

var console = window.console;
var jQuery = window.jQuery;
var noop = function() {};

// -------------------------- Outlayer -------------------------- //

// globally unique identifiers
var GUID = 0;
// internal store of all Outlayer intances
var instances = {};


/**
 * @param {Element, String} element
 * @param {Object} options
 * @constructor
 */
function Outlayer( element, options ) {
  var queryElement = utils.getQueryElement( element );
  if ( !queryElement ) {
    if ( console ) {
      console.error( 'Bad element for ' + this.constructor.namespace +
        ': ' + ( queryElement || element ) );
    }
    return;
  }
  this.element = queryElement;
  // add jQuery
  if ( jQuery ) {
    this.$element = jQuery( this.element );
  }

  // options
  this.options = utils.extend( {}, this.constructor.defaults );
  this.option( options );

  // add id for Outlayer.getFromElement
  var id = ++GUID;
  this.element.outlayerGUID = id; // expando
  instances[ id ] = this; // associate via id

  // kick it off
  this._create();

  var isInitLayout = this._getOption('initLayout');
  if ( isInitLayout ) {
    this.layout();
  }
}

// settings are for internal use only
Outlayer.namespace = 'outlayer';
Outlayer.Item = Item;

// default options
Outlayer.defaults = {
  containerStyle: {
    position: 'relative'
  },
  initLayout: true,
  originLeft: true,
  originTop: true,
  resize: true,
  resizeContainer: true,
  // item options
  transitionDuration: '0.4s',
  hiddenStyle: {
    opacity: 0,
    transform: 'scale(0.001)'
  },
  visibleStyle: {
    opacity: 1,
    transform: 'scale(1)'
  }
};

var proto = Outlayer.prototype;
// inherit EvEmitter
utils.extend( proto, EvEmitter.prototype );

/**
 * set options
 * @param {Object} opts
 */
proto.option = function( opts ) {
  utils.extend( this.options, opts );
};

/**
 * get backwards compatible option value, check old name
 */
proto._getOption = function( option ) {
  var oldOption = this.constructor.compatOptions[ option ];
  return oldOption && this.options[ oldOption ] !== undefined ?
    this.options[ oldOption ] : this.options[ option ];
};

Outlayer.compatOptions = {
  // currentName: oldName
  initLayout: 'isInitLayout',
  horizontal: 'isHorizontal',
  layoutInstant: 'isLayoutInstant',
  originLeft: 'isOriginLeft',
  originTop: 'isOriginTop',
  resize: 'isResizeBound',
  resizeContainer: 'isResizingContainer'
};

proto._create = function() {
  // get items from children
  this.reloadItems();
  // elements that affect layout, but are not laid out
  this.stamps = [];
  this.stamp( this.options.stamp );
  // set container style
  utils.extend( this.element.style, this.options.containerStyle );

  // bind resize method
  var canBindResize = this._getOption('resize');
  if ( canBindResize ) {
    this.bindResize();
  }
};

// goes through all children again and gets bricks in proper order
proto.reloadItems = function() {
  // collection of item elements
  this.items = this._itemize( this.element.children );
};


/**
 * turn elements into Outlayer.Items to be used in layout
 * @param {Array or NodeList or HTMLElement} elems
 * @returns {Array} items - collection of new Outlayer Items
 */
proto._itemize = function( elems ) {

  var itemElems = this._filterFindItemElements( elems );
  var Item = this.constructor.Item;

  // create new Outlayer Items for collection
  var items = [];
  for ( var i=0; i < itemElems.length; i++ ) {
    var elem = itemElems[i];
    var item = new Item( elem, this );
    items.push( item );
  }

  return items;
};

/**
 * get item elements to be used in layout
 * @param {Array or NodeList or HTMLElement} elems
 * @returns {Array} items - item elements
 */
proto._filterFindItemElements = function( elems ) {
  return utils.filterFindElements( elems, this.options.itemSelector );
};

/**
 * getter method for getting item elements
 * @returns {Array} elems - collection of item elements
 */
proto.getItemElements = function() {
  return this.items.map( function( item ) {
    return item.element;
  });
};

// ----- init & layout ----- //

/**
 * lays out all items
 */
proto.layout = function() {
  this._resetLayout();
  this._manageStamps();

  // don't animate first layout
  var layoutInstant = this._getOption('layoutInstant');
  var isInstant = layoutInstant !== undefined ?
    layoutInstant : !this._isLayoutInited;
  this.layoutItems( this.items, isInstant );

  // flag for initalized
  this._isLayoutInited = true;
};

// _init is alias for layout
proto._init = proto.layout;

/**
 * logic before any new layout
 */
proto._resetLayout = function() {
  this.getSize();
};


proto.getSize = function() {
  this.size = getSize( this.element );
};

/**
 * get measurement from option, for columnWidth, rowHeight, gutter
 * if option is String -> get element from selector string, & get size of element
 * if option is Element -> get size of element
 * else use option as a number
 *
 * @param {String} measurement
 * @param {String} size - width or height
 * @private
 */
proto._getMeasurement = function( measurement, size ) {
  var option = this.options[ measurement ];
  var elem;
  if ( !option ) {
    // default to 0
    this[ measurement ] = 0;
  } else {
    // use option as an element
    if ( typeof option == 'string' ) {
      elem = this.element.querySelector( option );
    } else if ( option instanceof HTMLElement ) {
      elem = option;
    }
    // use size of element, if element
    this[ measurement ] = elem ? getSize( elem )[ size ] : option;
  }
};

/**
 * layout a collection of item elements
 * @api public
 */
proto.layoutItems = function( items, isInstant ) {
  items = this._getItemsForLayout( items );

  this._layoutItems( items, isInstant );

  this._postLayout();
};

/**
 * get the items to be laid out
 * you may want to skip over some items
 * @param {Array} items
 * @returns {Array} items
 */
proto._getItemsForLayout = function( items ) {
  return items.filter( function( item ) {
    return !item.isIgnored;
  });
};

/**
 * layout items
 * @param {Array} items
 * @param {Boolean} isInstant
 */
proto._layoutItems = function( items, isInstant ) {
  this._emitCompleteOnItems( 'layout', items );

  if ( !items || !items.length ) {
    // no items, emit event with empty array
    return;
  }

  var queue = [];

  items.forEach( function( item ) {
    // get x/y object from method
    var position = this._getItemLayoutPosition( item );
    // enqueue
    position.item = item;
    position.isInstant = isInstant || item.isLayoutInstant;
    queue.push( position );
  }, this );

  this._processLayoutQueue( queue );
};

/**
 * get item layout position
 * @param {Outlayer.Item} item
 * @returns {Object} x and y position
 */
proto._getItemLayoutPosition = function( /* item */ ) {
  return {
    x: 0,
    y: 0
  };
};

/**
 * iterate over array and position each item
 * Reason being - separating this logic prevents 'layout invalidation'
 * thx @paul_irish
 * @param {Array} queue
 */
proto._processLayoutQueue = function( queue ) {
  this.updateStagger();
  queue.forEach( function( obj, i ) {
    this._positionItem( obj.item, obj.x, obj.y, obj.isInstant, i );
  }, this );
};

// set stagger from option in milliseconds number
proto.updateStagger = function() {
  var stagger = this.options.stagger;
  if ( stagger === null || stagger === undefined ) {
    this.stagger = 0;
    return;
  }
  this.stagger = getMilliseconds( stagger );
  return this.stagger;
};

/**
 * Sets position of item in DOM
 * @param {Outlayer.Item} item
 * @param {Number} x - horizontal position
 * @param {Number} y - vertical position
 * @param {Boolean} isInstant - disables transitions
 */
proto._positionItem = function( item, x, y, isInstant, i ) {
  if ( isInstant ) {
    // if not transition, just set CSS
    item.goTo( x, y );
  } else {
    item.stagger( i * this.stagger );
    item.moveTo( x, y );
  }
};

/**
 * Any logic you want to do after each layout,
 * i.e. size the container
 */
proto._postLayout = function() {
  this.resizeContainer();
};

proto.resizeContainer = function() {
  var isResizingContainer = this._getOption('resizeContainer');
  if ( !isResizingContainer ) {
    return;
  }
  var size = this._getContainerSize();
  if ( size ) {
    this._setContainerMeasure( size.width, true );
    this._setContainerMeasure( size.height, false );
  }
};

/**
 * Sets width or height of container if returned
 * @returns {Object} size
 *   @param {Number} width
 *   @param {Number} height
 */
proto._getContainerSize = noop;

/**
 * @param {Number} measure - size of width or height
 * @param {Boolean} isWidth
 */
proto._setContainerMeasure = function( measure, isWidth ) {
  if ( measure === undefined ) {
    return;
  }

  var elemSize = this.size;
  // add padding and border width if border box
  if ( elemSize.isBorderBox ) {
    measure += isWidth ? elemSize.paddingLeft + elemSize.paddingRight +
      elemSize.borderLeftWidth + elemSize.borderRightWidth :
      elemSize.paddingBottom + elemSize.paddingTop +
      elemSize.borderTopWidth + elemSize.borderBottomWidth;
  }

  measure = Math.max( measure, 0 );
  this.element.style[ isWidth ? 'width' : 'height' ] = measure + 'px';
};

/**
 * emit eventComplete on a collection of items events
 * @param {String} eventName
 * @param {Array} items - Outlayer.Items
 */
proto._emitCompleteOnItems = function( eventName, items ) {
  var _this = this;
  function onComplete() {
    _this.dispatchEvent( eventName + 'Complete', null, [ items ] );
  }

  var count = items.length;
  if ( !items || !count ) {
    onComplete();
    return;
  }

  var doneCount = 0;
  function tick() {
    doneCount++;
    if ( doneCount == count ) {
      onComplete();
    }
  }

  // bind callback
  items.forEach( function( item ) {
    item.once( eventName, tick );
  });
};

/**
 * emits events via EvEmitter and jQuery events
 * @param {String} type - name of event
 * @param {Event} event - original event
 * @param {Array} args - extra arguments
 */
proto.dispatchEvent = function( type, event, args ) {
  // add original event to arguments
  var emitArgs = event ? [ event ].concat( args ) : args;
  this.emitEvent( type, emitArgs );

  if ( jQuery ) {
    // set this.$element
    this.$element = this.$element || jQuery( this.element );
    if ( event ) {
      // create jQuery event
      var $event = jQuery.Event( event );
      $event.type = type;
      this.$element.trigger( $event, args );
    } else {
      // just trigger with type if no event available
      this.$element.trigger( type, args );
    }
  }
};

// -------------------------- ignore & stamps -------------------------- //


/**
 * keep item in collection, but do not lay it out
 * ignored items do not get skipped in layout
 * @param {Element} elem
 */
proto.ignore = function( elem ) {
  var item = this.getItem( elem );
  if ( item ) {
    item.isIgnored = true;
  }
};

/**
 * return item to layout collection
 * @param {Element} elem
 */
proto.unignore = function( elem ) {
  var item = this.getItem( elem );
  if ( item ) {
    delete item.isIgnored;
  }
};

/**
 * adds elements to stamps
 * @param {NodeList, Array, Element, or String} elems
 */
proto.stamp = function( elems ) {
  elems = this._find( elems );
  if ( !elems ) {
    return;
  }

  this.stamps = this.stamps.concat( elems );
  // ignore
  elems.forEach( this.ignore, this );
};

/**
 * removes elements to stamps
 * @param {NodeList, Array, or Element} elems
 */
proto.unstamp = function( elems ) {
  elems = this._find( elems );
  if ( !elems ){
    return;
  }

  elems.forEach( function( elem ) {
    // filter out removed stamp elements
    utils.removeFrom( this.stamps, elem );
    this.unignore( elem );
  }, this );
};

/**
 * finds child elements
 * @param {NodeList, Array, Element, or String} elems
 * @returns {Array} elems
 */
proto._find = function( elems ) {
  if ( !elems ) {
    return;
  }
  // if string, use argument as selector string
  if ( typeof elems == 'string' ) {
    elems = this.element.querySelectorAll( elems );
  }
  elems = utils.makeArray( elems );
  return elems;
};

proto._manageStamps = function() {
  if ( !this.stamps || !this.stamps.length ) {
    return;
  }

  this._getBoundingRect();

  this.stamps.forEach( this._manageStamp, this );
};

// update boundingLeft / Top
proto._getBoundingRect = function() {
  // get bounding rect for container element
  var boundingRect = this.element.getBoundingClientRect();
  var size = this.size;
  this._boundingRect = {
    left: boundingRect.left + size.paddingLeft + size.borderLeftWidth,
    top: boundingRect.top + size.paddingTop + size.borderTopWidth,
    right: boundingRect.right - ( size.paddingRight + size.borderRightWidth ),
    bottom: boundingRect.bottom - ( size.paddingBottom + size.borderBottomWidth )
  };
};

/**
 * @param {Element} stamp
**/
proto._manageStamp = noop;

/**
 * get x/y position of element relative to container element
 * @param {Element} elem
 * @returns {Object} offset - has left, top, right, bottom
 */
proto._getElementOffset = function( elem ) {
  var boundingRect = elem.getBoundingClientRect();
  var thisRect = this._boundingRect;
  var size = getSize( elem );
  var offset = {
    left: boundingRect.left - thisRect.left - size.marginLeft,
    top: boundingRect.top - thisRect.top - size.marginTop,
    right: thisRect.right - boundingRect.right - size.marginRight,
    bottom: thisRect.bottom - boundingRect.bottom - size.marginBottom
  };
  return offset;
};

// -------------------------- resize -------------------------- //

// enable event handlers for listeners
// i.e. resize -> onresize
proto.handleEvent = utils.handleEvent;

/**
 * Bind layout to window resizing
 */
proto.bindResize = function() {
  window.addEventListener( 'resize', this );
  this.isResizeBound = true;
};

/**
 * Unbind layout to window resizing
 */
proto.unbindResize = function() {
  window.removeEventListener( 'resize', this );
  this.isResizeBound = false;
};

proto.onresize = function() {
  this.resize();
};

utils.debounceMethod( Outlayer, 'onresize', 100 );

proto.resize = function() {
  // don't trigger if size did not change
  // or if resize was unbound. See #9
  if ( !this.isResizeBound || !this.needsResizeLayout() ) {
    return;
  }

  this.layout();
};

/**
 * check if layout is needed post layout
 * @returns Boolean
 */
proto.needsResizeLayout = function() {
  var size = getSize( this.element );
  // check that this.size and size are there
  // IE8 triggers resize on body size change, so they might not be
  var hasSizes = this.size && size;
  return hasSizes && size.innerWidth !== this.size.innerWidth;
};

// -------------------------- methods -------------------------- //

/**
 * add items to Outlayer instance
 * @param {Array or NodeList or Element} elems
 * @returns {Array} items - Outlayer.Items
**/
proto.addItems = function( elems ) {
  var items = this._itemize( elems );
  // add items to collection
  if ( items.length ) {
    this.items = this.items.concat( items );
  }
  return items;
};

/**
 * Layout newly-appended item elements
 * @param {Array or NodeList or Element} elems
 */
proto.appended = function( elems ) {
  var items = this.addItems( elems );
  if ( !items.length ) {
    return;
  }
  // layout and reveal just the new items
  this.layoutItems( items, true );
  this.reveal( items );
};

/**
 * Layout prepended elements
 * @param {Array or NodeList or Element} elems
 */
proto.prepended = function( elems ) {
  var items = this._itemize( elems );
  if ( !items.length ) {
    return;
  }
  // add items to beginning of collection
  var previousItems = this.items.slice(0);
  this.items = items.concat( previousItems );
  // start new layout
  this._resetLayout();
  this._manageStamps();
  // layout new stuff without transition
  this.layoutItems( items, true );
  this.reveal( items );
  // layout previous items
  this.layoutItems( previousItems );
};

/**
 * reveal a collection of items
 * @param {Array of Outlayer.Items} items
 */
proto.reveal = function( items ) {
  this._emitCompleteOnItems( 'reveal', items );
  if ( !items || !items.length ) {
    return;
  }
  var stagger = this.updateStagger();
  items.forEach( function( item, i ) {
    item.stagger( i * stagger );
    item.reveal();
  });
};

/**
 * hide a collection of items
 * @param {Array of Outlayer.Items} items
 */
proto.hide = function( items ) {
  this._emitCompleteOnItems( 'hide', items );
  if ( !items || !items.length ) {
    return;
  }
  var stagger = this.updateStagger();
  items.forEach( function( item, i ) {
    item.stagger( i * stagger );
    item.hide();
  });
};

/**
 * reveal item elements
 * @param {Array}, {Element}, {NodeList} items
 */
proto.revealItemElements = function( elems ) {
  var items = this.getItems( elems );
  this.reveal( items );
};

/**
 * hide item elements
 * @param {Array}, {Element}, {NodeList} items
 */
proto.hideItemElements = function( elems ) {
  var items = this.getItems( elems );
  this.hide( items );
};

/**
 * get Outlayer.Item, given an Element
 * @param {Element} elem
 * @param {Function} callback
 * @returns {Outlayer.Item} item
 */
proto.getItem = function( elem ) {
  // loop through items to get the one that matches
  for ( var i=0; i < this.items.length; i++ ) {
    var item = this.items[i];
    if ( item.element == elem ) {
      // return item
      return item;
    }
  }
};

/**
 * get collection of Outlayer.Items, given Elements
 * @param {Array} elems
 * @returns {Array} items - Outlayer.Items
 */
proto.getItems = function( elems ) {
  elems = utils.makeArray( elems );
  var items = [];
  elems.forEach( function( elem ) {
    var item = this.getItem( elem );
    if ( item ) {
      items.push( item );
    }
  }, this );

  return items;
};

/**
 * remove element(s) from instance and DOM
 * @param {Array or NodeList or Element} elems
 */
proto.remove = function( elems ) {
  var removeItems = this.getItems( elems );

  this._emitCompleteOnItems( 'remove', removeItems );

  // bail if no items to remove
  if ( !removeItems || !removeItems.length ) {
    return;
  }

  removeItems.forEach( function( item ) {
    item.remove();
    // remove item from collection
    utils.removeFrom( this.items, item );
  }, this );
};

// ----- destroy ----- //

// remove and disable Outlayer instance
proto.destroy = function() {
  // clean up dynamic styles
  var style = this.element.style;
  style.height = '';
  style.position = '';
  style.width = '';
  // destroy items
  this.items.forEach( function( item ) {
    item.destroy();
  });

  this.unbindResize();

  var id = this.element.outlayerGUID;
  delete instances[ id ]; // remove reference to instance by id
  delete this.element.outlayerGUID;
  // remove data for jQuery
  if ( jQuery ) {
    jQuery.removeData( this.element, this.constructor.namespace );
  }

};

// -------------------------- data -------------------------- //

/**
 * get Outlayer instance from element
 * @param {Element} elem
 * @returns {Outlayer}
 */
Outlayer.data = function( elem ) {
  elem = utils.getQueryElement( elem );
  var id = elem && elem.outlayerGUID;
  return id && instances[ id ];
};


// -------------------------- create Outlayer class -------------------------- //

/**
 * create a layout class
 * @param {String} namespace
 */
Outlayer.create = function( namespace, options ) {
  // sub-class Outlayer
  var Layout = subclass( Outlayer );
  // apply new options and compatOptions
  Layout.defaults = utils.extend( {}, Outlayer.defaults );
  utils.extend( Layout.defaults, options );
  Layout.compatOptions = utils.extend( {}, Outlayer.compatOptions  );

  Layout.namespace = namespace;

  Layout.data = Outlayer.data;

  // sub-class Item
  Layout.Item = subclass( Item );

  // -------------------------- declarative -------------------------- //

  utils.htmlInit( Layout, namespace );

  // -------------------------- jQuery bridge -------------------------- //

  // make into jQuery plugin
  if ( jQuery && jQuery.bridget ) {
    jQuery.bridget( namespace, Layout );
  }

  return Layout;
};

function subclass( Parent ) {
  function SubClass() {
    Parent.apply( this, arguments );
  }

  SubClass.prototype = Object.create( Parent.prototype );
  SubClass.prototype.constructor = SubClass;

  return SubClass;
}

// ----- helpers ----- //

// how many milliseconds are in each unit
var msUnits = {
  ms: 1,
  s: 1000
};

// munge time-like parameter into millisecond number
// '0.4s' -> 40
function getMilliseconds( time ) {
  if ( typeof time == 'number' ) {
    return time;
  }
  var matches = time.match( /(^\d*\.?\d*)(\w*)/ );
  var num = matches && matches[1];
  var unit = matches && matches[2];
  if ( !num.length ) {
    return 0;
  }
  num = parseFloat( num );
  var mult = msUnits[ unit ] || 1;
  return num * mult;
}

// ----- fin ----- //

// back in global
Outlayer.Item = Item;

return Outlayer;

}));


/***/ }),

/***/ 311:
/***/ ((module) => {

"use strict";
module.exports = jQuery;

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be in strict mode.
(() => {
"use strict";

// EXTERNAL MODULE: ./envira-gallery/assets/js/lib/pollyfills.js
var pollyfills = __webpack_require__(362);
// EXTERNAL MODULE: ./envira-gallery/assets/js/lib/mousewheel.js
var mousewheel = __webpack_require__(613);
// EXTERNAL MODULE: ./envira-gallery/assets/js/lib/jquery.justifiedGallery.js
var jquery_justifiedGallery = __webpack_require__(59);
// EXTERNAL MODULE: ./envira-gallery/assets/js/lib/enviraJustifiedGallery-extensions.js
var enviraJustifiedGallery_extensions = __webpack_require__(556);
// EXTERNAL MODULE: ./envira-gallery/assets/js/lib/imagesloaded.js
var imagesloaded = __webpack_require__(204);
// EXTERNAL MODULE: ./envira-gallery/assets/js/lib/isotope.js
var isotope = __webpack_require__(968);
// EXTERNAL MODULE: ./envira-gallery/assets/js/lib/envirabox.js
var envirabox = __webpack_require__(962);
// EXTERNAL MODULE: ./envira-gallery/assets/js/lib/envirabox-fullscreen.js
var envirabox_fullscreen = __webpack_require__(390);
// EXTERNAL MODULE: ./envira-gallery/assets/js/lib/envirabox-media.js
var envirabox_media = __webpack_require__(827);
// EXTERNAL MODULE: ./envira-gallery/assets/js/lib/envirabox-wheel.js
var envirabox_wheel = __webpack_require__(746);
// EXTERNAL MODULE: ./envira-gallery/assets/js/lib/envirabox-guestures.js
var envirabox_guestures = __webpack_require__(850);
// EXTERNAL MODULE: ./envira-gallery/assets/js/lib/envirabox-thumbs.js
var envirabox_thumbs = __webpack_require__(655);
// EXTERNAL MODULE: ./envira-gallery/assets/js/lib/envirabox-slideshow.js
var envirabox_slideshow = __webpack_require__(75);
// EXTERNAL MODULE: ./envira-gallery/assets/js/lib/enviraLazy.js
var enviraLazy = __webpack_require__(496);
var enviraLazy_default = /*#__PURE__*/__webpack_require__.n(enviraLazy);
;// CONCATENATED MODULE: ./envira-gallery/assets/js/gallery-init.js
/* provided dependency */ var $ = __webpack_require__(311);
function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }
function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }
function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }
function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }
function _iterableToArrayLimit(arr, i) { var _i = arr == null ? null : typeof Symbol !== "undefined" && arr[Symbol.iterator] || arr["@@iterator"]; if (_i == null) return; var _arr = []; var _n = true; var _d = false; var _s, _e; try { for (_i = _i.call(arr); !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"] != null) _i["return"](); } finally { if (_d) throw _e; } } return _arr; }
function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }
function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }
function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }

var Envira = /*#__PURE__*/function () {
  /**
   * Constructor function for Envira.
   *
   * @since 1.7.1
   */
  function Envira(id, data, images, lightbox) {
    _classCallCheck(this, Envira);
    var self = this;

    //Setup our Vars
    self.data = data;
    self.images = images;
    self.id = id;
    self.envirabox_config = lightbox;
    self.initImage = false;

    //Log if ENVIRA_DEBUG enabled
    self.log(self.data);
    self.log(self.images);
    self.log(self.envirabox_config);
    self.log(self.id);

    //self init
    self.init();
  }

  /**
   * Initizlize the proper scripts based on settings.
   *
   * @since 1.7.1
   */
  _createClass(Envira, [{
    key: "init",
    value: function init() {
      var self = this;
      var isLazyLoadingOn = self.get_config('lazy_loading');
      switch (self.get_config('layout')) {
        case 'automatic':
          self.justified();
          $(document).trigger('envira_gallery_api_justified', self.data);
          if (isLazyLoadingOn) {
            self.load_images();

            // Pagination specific lazy loading.
            $(document).on('envira_pagination_ajax_load_completed', function () {
              return $('#envira-gallery-' + self.id).on('jg.complete', function (event) {
                event.preventDefault();
                self.load_images();
              });
            });
          }
          break;
        case 'mason':
          setTimeout(function () {
            self.enviratopes();
          }, 100);
          if (isLazyLoadingOn) {
            self.load_images();
            self.enviratopes();
            $(window).scroll(function (e) {
              self.enviratopes();
            });
          }
          break;
        default:
          if (isLazyLoadingOn) {
            self.load_images();
          }
      }

      //Lightbox setup
      if (self.get_config('lightbox_enabled') || self.get_config('lightbox')) {
        self.lightbox();
      }
      $(document).trigger('envira_gallery_api_init', self);
    }

    /**
     * LazyLoading and nothing more.
     *
     * Avoid adding other features here.
     *
     * @since 1.7.1
     */
  }, {
    key: "load_images",
    value: function load_images() {
      var self = this;
      enviraLazy_default().run('#envira-gallery-' + self.id);
    }

    /**
     * Outputs the gallery init script in the footer.
     *
     * @since 1.7.1
     */
  }, {
    key: "justified",
    value: function justified() {
      var self = this;
      $('#envira-gallery-' + self.id).enviraJustifiedGallery({
        rowHeight: self.is_mobile() ? this.get_config('mobile_justified_row_height') : this.get_config('justified_row_height'),
        maxRowHeight: -1,
        waitThumbnailsLoad: true,
        selector: '> div > div',
        lastRow: this.get_config('justified_last_row'),
        border: 0,
        margins: this.get_config('justified_margins')
      });
      $(document).trigger('envira_gallery_api_start_justified', self);
      $('#envira-gallery-' + this.id).css('opacity', '1');
    }
  }, {
    key: "justified_norewind",
    value: function justified_norewind() {
      $('#envira-gallery-' + self.id).enviraJustifiedGallery('norewind');
    }

    /**
     * Outputs the gallery init script in the footer.
     *
     * @since 1.7.1
     */
  }, {
    key: "enviratopes",
    value: function enviratopes() {
      var self = this;
      var envira_isotopes_config = {
        itemSelector: '.envira-gallery-item',
        masonry: {
          columnWidth: '.envira-gallery-item'
        }
      };
      $(document).trigger('envira_gallery_api_enviratope_config', [self]);

      // Initialize Isotope
      $('#envira-gallery-' + self.id).enviratope(envira_isotopes_config);
      // Re-layout Isotope when each image loads
      $('#envira-gallery-' + self.id).enviraImagesLoaded().always(function () {
        $('#envira-gallery-' + self.id).enviratope('layout');
      });
      $(document).trigger('envira_gallery_api_enviratope', [self]);
    }

    /**
     * Outputs the gallery init script in the footer.
     *
     * @since 1.7.1
     */
  }, {
    key: "lightbox",
    value: function lightbox() {
      var self = this,
        touch_general = self.get_config('mobile_touchwipe_close') ? {
          vertical: true,
          momentum: true
        } : {
          vertical: false,
          momentum: false
        },
        thumbs_hide_on_open = self.get_config('thumbnails_hide') ? self.get_config('thumbnails_hide') : true,
        thumbs_rowHeight = self.is_mobile() ? this.get_config('mobile_thumbnails_height') : this.get_config('thumbnails_height'),
        thumbs = self.get_config('thumbnails') ? {
          autoStart: thumbs_hide_on_open,
          hideOnClose: true,
          position: self.get_lightbox_config('thumbs_position'),
          rowHeight: 'side' === self.get_lightbox_config('thumbs_position') ? false : thumbs_rowHeight
        } : false,
        slideshow = self.get_config('slideshow') ? {
          autoStart: self.get_config('autoplay'),
          speed: self.get_config('ss_speed')
        } : false,
        fullscreen = self.get_config('fullscreen') && self.get_config('open_fullscreen') ? {
          autoStart: true
        } : true,
        animationEffect = self.get_config('lightbox_open_close_effect') == 'zomm-in-out' ? 'zoom-in-out' : self.get_config('lightbox_open_close_effect'),
        transitionEffect = self.get_config('effect') == 'zomm-in-out' ? 'zoom' : self.get_config('effect'),
        lightbox_images = [],
        overlay_divs = '',
        no_cap_title_show_lightbox_themes = ['classical_dark', 'classical_light', 'infinity_dark', 'infinity_light'];
      self.lightbox_options = {
        selector: '[data-envirabox="' + self.id + '"]',
        loop: self.get_config('loop'),
        // Enable infinite gallery navigation
        margin: self.get_lightbox_config('margins'),
        // Space around image, ignored if zoomed-in or viewport width is smaller than 800px
        gutter: self.get_lightbox_config('gutter'),
        // Horizontal space between slides
        keyboard: self.get_config('keyboard'),
        // Enable keyboard navigation
        arrows: self.get_lightbox_config('arrows'),
        // Should display navigation arrows at the screen edges
        arrow_position: self.get_lightbox_config('arrow_position'),
        infobar: self.get_lightbox_config('infobar'),
        // Should display infobar (counter and arrows at the top)
        toolbar: self.get_lightbox_config('toolbar'),
        // Should display toolbar (buttons at the top)
        idleTime: self.get_lightbox_config('idle_time') ? self.get_lightbox_config('idle_time') : false,
        // by default there shouldn't be any, otherwise value is in seconds
        smallBtn: self.get_lightbox_config('show_smallbtn'),
        protect: false,
        // Disable right-click and use simple image protection for images
        image: {
          preload: false
        },
        animationEffect: animationEffect,
        animationDuration: self.get_lightbox_config('animation_duration') ? self.get_lightbox_config('animation_duration') : 300,
        // Duration in ms for open/close animation
        btnTpl: {
          smallBtn: self.get_lightbox_config('small_btn_template')
        },
        zoomOpacity: 'auto',
        transitionEffect: transitionEffect,
        // Transition effect between slides
        transitionDuration: self.get_lightbox_config('transition_duration') ? self.get_lightbox_config('transition_duration') : 200,
        // Duration in ms for transition animation
        baseTpl: self.get_lightbox_config('base_template'),
        // Base template for layout
        spinnerTpl: '<div class="envirabox-loading"></div>',
        // Loading indicator template
        errorTpl: self.get_lightbox_config('error_template'),
        // Error message template
        fullScreen: self.get_config('fullscreen') ? fullscreen : false,
        touch: touch_general,
        // Set `touch: false` to disable dragging/swiping
        hash: false,
        insideCap: self.get_lightbox_config('inner_caption'),
        capPosition: self.get_lightbox_config('caption_position'),
        capTitleShow: self.get_config('lightbox_title_caption') && self.get_config('lightbox_title_caption') != 'none' && self.get_config('lightbox_title_caption') != '0' && false === no_cap_title_show_lightbox_themes.includes(self.get_config('lightbox_theme')) ? self.get_config('lightbox_title_caption') : false,
        media: {
          youtube: {
            params: {
              autoplay: 0
            }
          }
        },
        wheel: this.get_config('mousewheel') == false ? false : true,
        slideShow: slideshow,
        thumbs: thumbs,
        lightbox_theme: self.get_config('lightbox_theme'),
        mobile: {
          clickContent: function clickContent(current, event) {
            return self.get_lightbox_config('click_content') ? self.get_lightbox_config('click_content') : 'toggleControls';
          },
          clickSlide: function clickSlide(current, event) {
            return self.get_lightbox_config('click_slide') ? self.get_lightbox_config('click_slide') : 'close'; // clicked on the slide
          },

          dblclickContent: false,
          dblclickSlide: false
        },
        // Clicked on the content
        clickContent: function clickContent(current, event) {
          return current.type === 'image' && (self.get_config('disable_zoom') === 'undefined' || self.get_config('disable_zoom') != '1') && (self.get_config('zoom_hover') != '1' || typeof envira_zoom === 'undefined' || typeof envira_zoom.enviraZoomActive === 'undefined') ? 'zoom' : false;
        },
        // clicked on the image itself
        clickSlide: self.get_lightbox_config('click_slide') ? self.get_lightbox_config('click_slide') : 'close',
        // clicked on the slide
        clickOutside: self.get_lightbox_config('click_outside') ? self.get_lightbox_config('click_outside') : 'toggleControls',
        // clicked on the background (backdrop) element

        // Same as previous two, but for double click
        dblclickContent: false,
        dblclickSlide: false,
        dblclickOutside: false,
        // Video settings
        videoPlayPause: self.get_config('videos_playpause') ? true : false,
        videoProgressBar: self.get_config('videos_progress') ? true : false,
        videoPlaybackTime: self.get_config('videos_current') ? true : false,
        videoVideoLength: self.get_config('videos_duration') ? true : false,
        videoVolumeControls: self.get_config('videos_volume') ? true : false,
        videoControlBar: self.get_config('videos_controls') ? true : false,
        videoFullscreen: self.get_config('videos_fullscreen') ? true : false,
        videoDownload: self.get_config('videos_download') ? true : false,
        videoPlayIcon: self.get_config('videos_play_icon_thumbnails') ? true : false,
        videoAutoPlay: self.get_config('videos_autoplay') ? true : false,
        // Callbacks
        //==========
        onInit: function onInit(instance, current) {
          self.initImage = true;
          $(document).trigger('envirabox_api_on_init', [self, instance, current]);
        },
        beforeLoad: function beforeLoad(instance, current) {
          $(document).trigger('envirabox_api_before_load', [self, instance, current]);
        },
        afterLoad: function afterLoad(instance, current) {
          $(document).trigger('envirabox_api_after_load', [self, instance, current]);
        },
        beforeShow: function beforeShow(instance, current) {
          if (self.data.lightbox_theme == 'base' && overlay_divs == '' && $('.envirabox-position-overlay').length > 0) {
            overlay_divs = $('.envirabox-position-overlay');
          }
          self.initImage = false;
          if (self.get_config('loop') === 0 && instance.currIndex == 0) {
            // hide the back navigation arrow
            $('.envirabox-slide--current a.envirabox-prev').hide();
          } else {
            $('.envirabox-slide--current a.envirabox-prev').show();
          }
          if (self.get_config('loop') === 0 && instance.currIndex == Object.keys(instance.group).length - 1) {
            // hide the next navigation arrow
            $('.envirabox-slide--current a.envirabox-next').hide();
          } else {
            $('.envirabox-slide--current a.envirabox-next').show();
          }
          $(document).trigger('envirabox_api_before_show', [self, instance, current]);
        },
        afterShow: function afterShow(instance, current) {
          /* this changes the classes for a visible box */

          $('.envirabox-thumbs ul').find('li').removeClass('focused');
          $('.envirabox-thumbs ul').find('li.envirabox-thumbs-active').focus().addClass('focused');
          if (prepend == undefined || prepend_cap == undefined) {
            var prepend = false,
              prepend_cap = false;
          }
          if (prepend != true) {
            $('.envirabox-position-overlay').each(function () {
              $(this).prependTo(current.$content);
            });
            prepend = true;
          }
          if (self.get_config('loop') === 0 && instance.currIndex == 0) {
            // hide the back navigation arrow
            $('.envirabox-outer a.envirabox-prev').hide();
          } else {
            $('.envirabox-outer a.envirabox-prev').show();
          }
          if (self.get_config('loop') === 0 && instance.currIndex == Object.keys(instance.group).length - 1) {
            // hide the next navigation arrow
            $('.envirabox-outer a.envirabox-next').hide();
          } else {
            $('.envirabox-outer a.envirabox-next').show();
          }

          /* support older galleries or if someone overrides the keyboard configuration via a filter, etc. */

          if (self.get_config('keyboard') !== undefined && self.get_config('keyboard') === 0) {
            $(window).keypress(function (event) {
              if ([32, 37, 38, 39, 40].indexOf(event.keyCode) > -1) {
                event.preventDefault();
              }
            });
          }

          /* legacy theme we hide certain elements initially to prevent user seeing them for a second in the upper left until the CSS fully loads */

          $('.envirabox-slide--current .envirabox-title').css('visibility', 'visible');
          if ($('.envirabox-slide--current .envirabox-caption').length > 0 && $('.envirabox-slide--current .envirabox-caption').html().length > 0) {
            $('.envirabox-slide--current .envirabox-caption').css('visibility', 'visible');
            $('.envirabox-slide--current .envirabox-caption-wrap').css('visibility', 'visible');
          } else {
            $('.envirabox-slide--current .envirabox-caption').css('visibility', 'hidden');
            $('.envirabox-slide--current .envirabox-caption-wrap').css('visibility', 'hidden');
          }
          $('.envirabox-navigation').show();
          $('.envirabox-navigation-inside').show();

          /* if there's overlay divs to show, show them (applies again only to legacy) */

          if (overlay_divs !== undefined && overlay_divs != '' && $('.envirabox-slide--current .envirabox-image-wrap').length > 0) {
            $('.envirabox-image-wrap').prepend(overlay_divs);
          } else if (overlay_divs !== undefined && overlay_divs != '' && $('.envirabox-slide--current .envirabox-content').length > 0) {
            $('.envirabox-content').prepend(overlay_divs);
          }
          $(document).trigger('envirabox_api_after_show', [self, instance, current]);

          /* double check caption */

          if (instance.opts.capTitleShow !== undefined && (instance.opts.capTitleShow == 'caption' || instance.opts.capTitleShow == 'title_caption') && current.caption == '') {
            $('.envirabox-caption-wrap .envirabox-caption').css('visibility', 'hidden');
          } else {
            $('.envirabox-caption-wrap .envirabox-caption').css('visibility', 'visible');
          }
        },
        beforeClose: function beforeClose(instance, current) {
          $(document).trigger('envirabox_api_before_close', [self, instance, current]);
        },
        afterClose: function afterClose(instance, current) {
          $(document).trigger('envirabox_api_after_close', [self, instance, current]);
        },
        onActivate: function onActivate(instance, current) {
          $(document).trigger('envirabox_api_on_activate', [self, instance, current]);
        },
        onDeactivate: function onDeactivate(instance, current) {
          $(document).trigger('envirabox_api_on_deactivate', [self, instance, current]);
        }
      };
      // Mobile Overrides
      if (self.is_mobile()) {
        if (self.get_config('mobile_thumbnails') !== 1) {
          self.lightbox_options.thumbs = false;
        }
      }
      // Load from json object if load all images is ture
      if (self.get_lightbox_config('load_all')) {
        var the_images = self.images;
        if (_typeof(the_images) !== 'object') {
          // this will cause a JS error
          return;
        }
        $.each(the_images, function (i) {
          if (this.video !== undefined && this.video.embed_url !== undefined) {
            // if this is a video, then the lightbox needs the embed url and not an image
            this.src = this.video.embed_url;
          }
          lightbox_images.push(this);
        });
      } else {
        var newIndex = 0;
        var $images = $('.envira-gallery-' + self.id);
        $.each($images, function (i) {
          lightbox_images.push(this);
        });
      }
      $('#envira-gallery-wrap-' + self.id + ' .envira-gallery-link').on('click', function (e) {
        var _this = this;
        e.preventDefault();
        e.stopImmediatePropagation();
        var index = $(this).find('img').data('envira-index'),
          src = $(this).find('img').attr('src'),
          found = false;

        // Override index if sorting is random or pagination is on
        if (parseInt(self.get_config('pagination')) === 1 && parseInt(index) === 0 || self.get_config('sort_order') == '1') {
          Object.entries(lightbox_images).forEach(function (entry) {
            var _entry = _slicedToArray(entry, 2),
              key = _entry[0],
              value = _entry[1];
            // src might need encodeURI for foreign characters? related: #3487
            if ($(value).prop('data-envira-item-src') == src || $(value).find('img').prop('src') == src) {
              index = key;
              found = true;
            }
          });
          if (found !== true) {
            Object.entries(lightbox_images).forEach(function (entry) {
              var _entry2 = _slicedToArray(entry, 2),
                key = _entry2[0],
                value = _entry2[1];
              if (value == $(_this).attr('href') || value.src == $(_this).find('img').data('envira-item-src')) {
                index = key;
                found = true;
              }
            });
          }
        }
        $.envirabox.open(lightbox_images, self.lightbox_options, index);
      });
      $(document).trigger('envirabox_lightbox_api', self);
    }

    /**
     * Get a config option based off of a key.
     *
     * @since 1.7.1
     */
  }, {
    key: "get_config",
    value: function get_config(key) {
      return this.data[key];
    }

    /**
     * Helper method to get config by key.
     *
     * @since 1.7.1
     */
  }, {
    key: "get_lightbox_config",
    value: function get_lightbox_config(key) {
      return this.envirabox_config[key];
    }

    /**
     * Helper method to get image from id
     *
     * @since 1.7.1
     */
  }, {
    key: "get_image",
    value: function get_image(id) {
      return this.images[id];
    }
  }, {
    key: "is_mobile",
    value: function is_mobile() {
      if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
        return true;
      }
      return false;
    }

    /**
     * Helper method for logging if ENVIRA_DEBUG is true.
     *
     * @since 1.7.1
     */
  }, {
    key: "log",
    value: function log(_log) {
      //Bail if debug or log is not set.
      if (envira_gallery.debug == undefined || !envira_gallery.debug || _log == undefined) {
        return;
      }
      console.log(_log);
    }
  }]);
  return Envira;
}();
/* harmony default export */ const gallery_init = (Envira);
// EXTERNAL MODULE: ./envira-gallery/assets/js/gallery-link.js
var gallery_link = __webpack_require__(617);
var gallery_link_default = /*#__PURE__*/__webpack_require__.n(gallery_link);
;// CONCATENATED MODULE: ./envira-gallery/assets/js/envira.js
/* provided dependency */ var jQuery = __webpack_require__(311);













// import './lib/envirabox-downloads.js';



var envira_galleries = window.envira_galleries || {},
  envira_links = window.envira_links || {};
(function ($, window, document, Envira, Envira_Link, envira_gallery, envira_galleries) {
  $(function () {
    window.envira_galleries = envira_galleries;
    window.envira_links = envira_links;
    $(document).on('envira_load', function (e) {
      e.stopPropagation();
      envira_galleries = {};
      envira_links = {};
      var envira_galleries_total = [];
      $('.envira-gallery-public').each(function () {
        var $this = $(this),
          $id = $this.data('envira-id'),
          $envira_galleries = $this.data('gallery-config'),
          $envira_images = $this.data('gallery-images'),
          $envira_lightbox = $this.data('lightbox-theme'),
          $suffix_id = '';

        // check to see if multiple duplicate ids exist, and if so add a suffix to make each unique (and therefore get init)
        if (envira_galleries_total[$envira_galleries['gallery_id']] !== undefined) {
          $suffix_id = '_' + (envira_galleries_total[$envira_galleries['gallery_id']] ? parseInt(envira_galleries_total[$envira_galleries['gallery_id']]) + 1 : 2);
          envira_galleries_total[$envira_galleries['gallery_id']] = parseInt(envira_galleries_total[$envira_galleries['gallery_id']]) + 1;
        } else {
          envira_galleries_total[$envira_galleries['gallery_id']] = 1;
        }
        envira_galleries[$envira_galleries['gallery_id']] = new Envira($envira_galleries['gallery_id'] + $suffix_id, $envira_galleries, $envira_images, $envira_lightbox);

        // the loader wont disappear if lazy loading is disabled (that is the point of the loader so we will do this here if the loading is disabled).
        if ($("#envira-gallery-wrap-" + $envira_galleries['gallery_id'] + $suffix_id + ".envira-lazy-loading-disabled").length) {
          $("#envira-gallery-wrap-" + $envira_galleries['gallery_id'] + $suffix_id + ".envira-lazy-loading-disabled").find(".envira-loader").remove();
        } else {
          $(".envira-gallery-wrap").find(".envira-loader").remove();
        }
      });
      $('.envira-gallery-links').each(function () {
        var $this = $(this),
          $envira_galleries = $this.data('gallery-config'),
          $envira_images = $this.data('gallery-images'),
          $envira_lightbox = $this.data('lightbox-theme');
        if (envira_links[$envira_galleries['gallery_id']] === undefined) {
          envira_links[$envira_galleries['gallery_id']] = new Envira_Link($envira_galleries, $envira_images, $envira_lightbox);
        }
      });
      $(document).trigger('envira_loaded', [envira_galleries, envira_links]);
    });
    $(document).trigger('envira_load');
    if (envira_gallery.debug !== undefined && envira_gallery.debug) {
      console.log(envira_links);
      console.log(envira_galleries);
    }
    $('body').on('click', 'div.envirabox-title a[href*="#"]:not([href="#"])', function (e) {
      if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
        $.envirabox.close();
        return false;
      }
    });

    // `$('#envira-gallery-wrap-' + event.gallery_id).find('iframe').closest('div.envira-gallery-item-inner').find( 'div.envira-gallery-position-overlay' ).delay( 100 ).show();
    /* setup lazy load event */
    $(document).on('envira_image_lazy_load_complete', function (event) {
      var envira_container = '';
      if (event !== undefined && (event.image_id !== undefined && event.image_id !== null || event.video_id !== undefined && event.video_id !== null)) {
        if ($('#envira-gallery-wrap-' + event.gallery_id).find('#' + event.video_id + ' iframe').length > 0) {
          envira_container = $('#envira-gallery-wrap-' + event.gallery_id).find('#' + event.video_id + ' iframe');
        } else if ($('#envira-gallery-wrap-' + event.gallery_id).find('#' + event.video_id + ' video').length > 0) {
          envira_container = $('#envira-gallery-wrap-' + event.gallery_id).find('#' + event.video_id + ' video');
        } else {
          envira_container = $('#envira-gallery-wrap-' + event.gallery_id).find('img#' + event.image_id);
        }
        if ($("#envira-gallery-wrap-" + event.gallery_id).length) {
          $("#envira-gallery-wrap-" + event.gallery_id).find(".envira-loader").remove();
        } else {
          $(".envira-gallery-wrap").find(".envira-loader").remove();
        }
        if (envira_container === undefined || envira_container === '') {
          return;
        }
        if ($('#envira-gallery-wrap-' + event.gallery_id).find('div.envira-gallery-public').hasClass('envira-gallery-0-columns')) {
          /* this is an automatic gallery */
          $(envira_container).closest('div.envira-gallery-item-inner').find('div.envira-gallery-position-overlay').delay(100).show();
        } else {
          /* this is a legacy gallery */
          $(envira_container).closest('div.envira-gallery-item-inner').find('div.envira-gallery-position-overlay').delay(100).show();

          /* re-do the padding bottom */
          /* $padding_bottom = ( $output_height / $output_width ) * 100; */

          var envira_lazy_width = $(envira_container).closest('div.envira-gallery-item-inner').find('.envira-lazy').width();
          var ratio1 = event.naturalHeight / event.naturalWidth;
          var ratio2 = event.naturalHeight / envira_lazy_width;
          if (ratio2 < ratio1) {
            var ratio = ratio2;
          } else {
            var ratio = ratio1;
          }
          var padding_bottom = ratio * 100;
          if (envira_container.closest('div.envira-gallery-public').parent().hasClass('envira-gallery-theme-sleek')) {
            // add additional padding for this theme
            padding_bottom = padding_bottom + 2;
          }
          var div_envira_lazy = $(envira_container).closest('div.envira-gallery-item-inner').find('div.envira-lazy');
          var caption_height = div_envira_lazy.closest('div.envira-gallery-item-inner').find('.envira-gallery-captioned-data').height();
          if ($(envira_container).closest('div.envira-gallery-item').hasClass('enviratope-item')) {
            div_envira_lazy.css('padding-bottom', padding_bottom + '%').attr('data-envira-changed', 'true');
            var div_overlay = $(envira_container).closest('div.envira-gallery-item-inner').find('.envira-gallery-position-overlay.envira-gallery-bottom-right');
            div_overlay.css('bottom', caption_height);
            div_overlay = $(envira_container).closest('div.envira-gallery-item-inner').find('.envira-gallery-position-overlay.envira-gallery-bottom-left');
            div_overlay.css('bottom', caption_height);
          } else {
            div_envira_lazy.css('height', 'auto').css('padding-bottom', 'unset').attr('data-envira-changed', 'true');
            var div_overlay = $(envira_container).closest('div.envira-gallery-item-inner').find('.envira-gallery-position-overlay.envira-gallery-bottom-right');
            div_overlay.css('bottom', caption_height + 10);
            div_overlay = $(envira_container).closest('div.envira-gallery-item-inner').find('.envira-gallery-position-overlay.envira-gallery-bottom-left');
            div_overlay.css('bottom', caption_height + 10);
          }
          $(envira_container).closest('div.envira-gallery-item-inner').find('span.envira-title').delay(1000).css('visibility', 'visible');
          $(envira_container).closest('div.envira-gallery-item-inner').find('span.envira-caption').delay(1000).css('visibility', 'visible');
          if (window['envira_container_' + event.gallery_id] !== undefined) {
            if ($('#envira-gallery-' + event.gallery_id).hasClass('enviratope')) {
              window['envira_container_' + event.gallery_id].on('layoutComplete', function (event, laidOutItems) {
                $(envira_container).closest('div.envira-gallery-item-inner').find('span.envira-title').delay(1000).css('visibility', 'visible');
                $(envira_container).closest('div.envira-gallery-item-inner').find('span.envira-caption').delay(1000).css('visibility', 'visible');
              });
            } else {}
          }
        }
      }
    });
  });
})(jQuery, window, document, gallery_init, (gallery_link_default()), envira_gallery, envira_galleries);
})();

/******/ })()
;