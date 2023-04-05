/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ 999:
/***/ ((__unused_webpack_module, __unused_webpack_exports, __webpack_require__) => {

/* provided dependency */ var jQuery = __webpack_require__(311);
/**
 * jQuery Conditions 1.0.1
 *
 * Copyright 2016 Bejamin Rojas
 * @license Released under the MIT license.
 * http://jquery.org/license
 */
(function ($) {
  'use strict';

  $.fn.conditions = function (conditions) {
    return this.each(function (index, element) {
      var CJS = new ConditionsJS(element, conditions, $.fn.conditions.defaults);
      CJS.init();
    });
  };
  $.fn.conditions.defaults = {
    condition: null,
    actions: {},
    effect: 'fade'
  };
  var ConditionsJS = function ConditionsJS(element, conditions, defaults) {
    var that = this;
    that.element = $(element);
    that.defaults = defaults;
    that.conditions = conditions;
    that._init = false;
    if (!$.isArray(that.conditions)) {
      that.conditions = [that.conditions];
    }
    $.each(that.conditions, function (i, v) {
      that.conditions[i] = $.extend({}, that.defaults, v);
    });
  };
  ConditionsJS.prototype.init = function () {
    var that = this;
    that._init = true;
    // Set up event listener
    $(that.element).on('change', function () {
      that.matchConditions();
    });
    $(that.element).on('keyup', function () {
      that.matchConditions();
    });

    //Show based on current value on page load
    that.matchConditions(true);
  };
  ConditionsJS.prototype.matchConditions = function (init) {
    var that = this;
    if (!init) {
      that._init = false;
    }
    $.each(that.conditions, function (ind, cond) {
      var condition_matches = false,
        all_conditions_match = true;
      if (!$.isArray(cond.conditions)) {
        cond.conditions = [cond.conditions];
      }
      $.each(cond.conditions, function (i, c) {
        c = $.extend({
          element: null,
          type: 'val',
          operator: '==',
          condition: null,
          multiple: 'single'
        }, c);
        c.element = $(c.element);
        switch (c.type) {
          case 'value':
          case 'val':
            switch (c.operator) {
              case '===':
              case '==':
              case '=':
                if ($.isArray(c.element.val())) {
                  var m_single_condition_matches = false;
                  var m_all_condition_matches = true;
                  $.each(c.element.val(), function (index, value) {
                    if (value === c.condition) {
                      m_single_condition_matches = true;
                    } else {
                      m_all_condition_matches = false;
                    }
                  });
                  condition_matches = 'single' == c.multiple ? m_single_condition_matches : m_all_condition_matches;
                } else {
                  condition_matches = c.element.val() === c.condition;
                }
                break;
              case '!==':
              case '!=':
                if ($.isArray(c.element.val())) {
                  var m_single_condition_matches = false;
                  var m_all_condition_matches = true;
                  $.each(c.element.val(), function (index, value) {
                    if (value !== c.condition) {
                      m_single_condition_matches = true;
                    } else {
                      m_all_condition_matches = false;
                    }
                  });
                  condition_matches = 'single' == c.multiple ? m_single_condition_matches : m_all_condition_matches;
                } else {
                  condition_matches = c.element.val() !== c.condition;
                }
                break;
              case 'array':
                if ($.isArray(c.element.val())) {
                  var m_single_condition_matches = false;
                  var m_all_condition_matches = c.element.val().length === c.condition.length;
                  $.each(c.element.val(), function (index, value) {
                    if ($.inArray(value, c.condition) !== -1) {
                      m_single_condition_matches = true;
                    } else {
                      m_all_condition_matches = false;
                    }
                  });
                  condition_matches = 'single' == c.multiple ? m_single_condition_matches : m_all_condition_matches;
                } else {
                  condition_matches = $.inArray(c.element.val(), c.condition) !== -1;
                }
                break;
              case '!array':
                if ($.isArray(c.element.val())) {
                  var m_single_condition_matches = false;
                  var m_all_condition_matches = true;
                  var selected = [];
                  $.each(c.element.val(), function (index, value) {
                    if ($.inArray(value, c.condition) === -1) {
                      m_single_condition_matches = true;
                    } else {
                      selected.push(value);
                    }
                  });
                  if (selected.length == c.condition.length) {
                    m_all_condition_matches = false;
                  }
                  condition_matches = 'single' == c.multiple ? m_single_condition_matches : m_all_condition_matches;
                } else {
                  condition_matches = $.inArray(c.element.val(), c.condition) === -1;
                }
                break;
            }
            break;
          case 'checked':
            switch (c.operator) {
              case 'is':
                condition_matches = c.element.is(':checked');
                break;
              case '!is':
                condition_matches = !c.element.is(':checked');
                break;
            }
            break;
        }
        if (!condition_matches && all_conditions_match) {
          all_conditions_match = false;
        }
      });
      if (all_conditions_match) {
        if (!$.isEmptyObject(cond.actions["if"])) {
          if (!$.isArray(cond.actions["if"])) {
            cond.actions["if"] = [cond.actions["if"]];
          }
          $.each(cond.actions["if"], function (i, condition) {
            that.showAndHide(condition, cond.effect);
          });
        }
      } else {
        if (!$.isEmptyObject(cond.actions["else"])) {
          if (!$.isArray(cond.actions["else"])) {
            cond.actions["else"] = [cond.actions["else"]];
          }
          $.each(cond.actions["else"], function (i, condition) {
            that.showAndHide(condition, cond.effect);
          });
        }
      }
    });
  };
  ConditionsJS.prototype.showAndHide = function (condition, effect) {
    var that = this;
    switch (condition.action) {
      case 'show':
        that._show($(condition.element), effect);
        break;
      case 'hide':
        that._hide($(condition.element), effect);
        break;
    }
  };
  ConditionsJS.prototype._show = function (element, effect) {
    var that = this;
    if (that._init) {
      element.show();
    } else {
      switch (effect) {
        case 'appear':
          element.show();
          break;
        case 'slide':
          element.slideDown();
          break;
        case 'fade':
          element.fadeIn(300);
          break;
      }
    }
  };
  ConditionsJS.prototype._hide = function (element, effect) {
    var that = this;
    if (that._init) {
      element.hide();
    } else {
      switch (effect) {
        case 'appear':
          element.hide();
          break;
        case 'slide':
          element.slideUp();
          break;
        case 'fade':
          element.fadeOut(300);
          break;
      }
    }
  };
})(jQuery);

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
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
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
/* harmony import */ var _conditions__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(999);
/* harmony import */ var _conditions__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_conditions__WEBPACK_IMPORTED_MODULE_0__);
/* provided dependency */ var jQuery = __webpack_require__(311);


/**
 * Handles showing and hiding fields conditionally
 */
jQuery(document).ready(function ($) {
  // Show/hide elements as necessary when a conditional field is changed
  $('#envira-gallery-settings input:not([type=hidden]), #envira-gallery-settings select').conditions([{
    // Main Theme Elements
    conditions: {
      element: '[name="_envira_gallery[lightbox_theme]"]',
      type: 'value',
      operator: 'array',
      condition: ['base', 'captioned', 'polaroid', 'showcase', 'sleek', 'subtle']
    },
    actions: {
      "if": [{
        element: '#envira-config-lightbox-title-display-box, #envira-config-lightbox-arrows-box, #envira-config-lightbox-toolbar-box',
        action: 'show'
      }]
    }
  }, {
    // Main Theme Elements
    conditions: {
      element: '[name="_envira_gallery[lightbox_theme]"]',
      type: 'value',
      operator: 'array',
      condition: ['base_dark', 'base_light']
    },
    actions: {
      "if": [{
        element: '#envira-config-lightbox-arrows-box',
        action: 'show'
      }]
    }
  }, {
    conditions: {
      element: '[name="_envira_gallery[lightbox_theme]"]',
      type: 'value',
      operator: 'array',
      condition: ['base_dark', 'base_light']
    },
    actions: {
      "if": [{
        element: '#envira-config-lightbox-title-display-box, #envira-config-lightbox-toolbar-box',
        action: 'hide'
      }]
    }
  }, {
    // Gallery arrows Dependant on Theme
    conditions: [{
      element: '[name="_envira_gallery[lightbox_theme]"]',
      type: 'value',
      operator: 'array',
      condition: ['base', 'captioned', 'polaroid', 'showcase', 'sleek', 'subtle']
    }, {
      element: '[name="_envira_gallery[arrows]"]',
      type: 'checked',
      operator: 'is'
    }],
    actions: {
      "if": {
        element: '#envira-config-lightbox-arrows-position-box',
        action: 'show'
      },
      "else": {
        element: '#envira-config-lightbox-arrows-position-box',
        action: 'hide'
      }
    }
  }, {
    // Gallery arrows Dependant on Theme
    conditions: [{
      element: '[name="_envira_gallery[lightbox_theme]"]',
      type: 'value',
      operator: 'array',
      condition: ['base', 'captioned', 'polaroid', 'showcase', 'sleek', 'subtle']
    }],
    actions: {
      "if": {
        element: '#envira-config-fullscreen-box, #envira-config-open-fullscreen-box',
        action: 'hide'
      }
    }
  }, {
    // Gallery arrows Dependant on Theme
    conditions: [{
      element: '[name="_envira_gallery[lightbox_theme]"]',
      type: 'value',
      operator: 'array',
      condition: ['base_dark', 'base_light']
    }],
    actions: {
      "if": {
        element: '#envira-config-fullscreen-box, #envira-config-open-fullscreen-box',
        action: 'show'
      }
    }
  }, {
    // Gallery arrows Dependant on Theme
    conditions: [{
      element: '[name="_envira_gallery[lightbox_theme]"]',
      type: 'value',
      operator: 'array',
      condition: ['base', 'captioned', 'polaroid', 'showcase', 'sleek', 'subtle']
    }, {
      element: '[name="_envira_gallery[toolbar]"]',
      type: 'checked',
      operator: 'is'
    }],
    actions: {
      "if": {
        element: '#envira-config-fullscreen-box, #envira-config-open-fullscreen-box',
        action: 'show'
      }
    }
  }, {
    // Items that are dependent on dark and new themes
    conditions: [{
      element: '[name="_envira_gallery[lightbox_theme]"]',
      type: 'value',
      operator: 'array',
      condition: ['base_dark', 'base_light', 'infinity_light', 'infinity_dark', 'classical_light', 'classical_dark']
    }],
    actions: {
      "if": {
        element: '#envira-config-image-counter',
        action: 'show'
      },
      "else": {
        element: '#envira-config-image-counter',
        action: 'hide'
      }
    }
  }, {
    // Gallery Toolbar
    conditions: [{
      element: '[name="_envira_gallery[toolbar]"]',
      type: 'checked',
      operator: 'is'
    }, {
      element: '[name="_envira_gallery[lightbox_theme]"]',
      type: 'value',
      operator: 'array',
      condition: ['base', 'captioned', 'polaroid', 'showcase', 'sleek', 'subtle']
    }],
    actions: {
      "if": [{
        element: '#envira-config-lightbox-toolbar-title-box, #envira-config-lightbox-toolbar-position-box',
        action: 'show'
      }],
      "else": [{
        element: '#envira-config-lightbox-toolbar-title-box, #envira-config-lightbox-toolbar-position-box',
        action: 'hide'
      }]
    }
  }, {
    // Mobile Elements Dependant on Theme
    conditions: [{
      element: '[name="_envira_gallery[lightbox_theme]"]',
      type: 'value',
      operator: 'array',
      condition: ['base', 'captioned', 'polaroid', 'showcase', 'sleek', 'subtle']
    }, {
      element: '[name="_envira_gallery[mobile_lightbox]"]',
      type: 'checked',
      operator: 'is'
    }],
    actions: {
      "if": {
        element: '#envira-config-mobile-toolbar-box',
        action: 'show'
      },
      "else": {
        element: '#envira-config-mobile-toolbar-box',
        action: 'hide'
      }
    }
  }, {
    // Mobile Elements Independant of Theme
    conditions: {
      element: '[name="_envira_gallery[mobile_lightbox]"]',
      type: 'checked',
      operator: 'is'
    },
    actions: {
      "if": {
        element: '#envira-config-mobile-touchwipe-box, #envira-config-mobile-touchwipe-close-box, #envira-config-mobile-thumbnails-box',
        action: 'show'
      },
      "else": {
        element: '#envira-config-mobile-touchwipe-box, #envira-config-mobile-touchwipe-close-box, #envira-config-mobile-thumbnails-box',
        action: 'hide'
      }
    }
  }, {
    // Mobile Elements Independant of Theme
    conditions: {
      element: '[name="_envira_gallery[mobile_lightbox]"]',
      type: 'checked',
      operator: 'is'
    },
    actions: {
      "if": {
        element: '#envira-config-lightbox-mobile-enable-links',
        action: 'hide'
      },
      "else": {
        element: '#envira-config-lightbox-mobile-enable-links',
        action: 'show'
      }
    }
  }, {
    // Thumbnail Elements Dependant on Theme
    conditions: [{
      element: '[name="_envira_gallery[lightbox_theme]"]',
      type: 'value',
      operator: 'array',
      condition: ['base', 'captioned', 'polaroid', 'showcase', 'sleek', 'subtle']
    }, {
      element: '[name="_envira_gallery[thumbnails]"]',
      type: 'checked',
      operator: 'is'
    }],
    actions: {
      "if": {
        element: '#envira-config-thumbnails-position-box',
        action: 'show'
      },
      "else": {
        element: '#envira-config-thumbnails-position-box',
        action: 'hide'
      }
    }
  }, {
    // Thumbnail Elements Independant of Theme
    conditions: [{
      element: '[name="_envira_gallery[thumbnails]"]',
      type: 'checked',
      operator: 'is'
    }],
    actions: {
      "if": {
        element: '#envira-config-thumbnails-custom-size',
        action: 'show'
      },
      "else": {
        element: '#envira-config-thumbnails-custom-size',
        action: 'hide'
      }
    }
  }, {
    // Thumbnail Elements Independant of Theme
    conditions: [{
      element: '[name="_envira_gallery[thumbnails_toggle]"]',
      type: 'checked',
      operator: 'is'
    }],
    actions: {
      "if": {
        element: '#envira-config-thumbnail-hide',
        action: 'show'
      },
      "else": {
        element: '#envira-config-thumbnail-hide',
        action: 'hide'
      }
    }
  }, {
    // Thumbnail Elements Independant of Theme
    conditions: [{
      element: '[name="_envira_gallery[thumbnails_custom_size]"]',
      type: 'checked',
      operator: 'is'
    }],
    actions: {
      "if": {
        element: '#envira-config-thumbnails-height-box, #envira-config-thumbnails-width-box',
        action: 'show'
      },
      "else": {
        element: '#envira-config-thumbnails-height-box, #envira-config-thumbnails-width-box',
        action: 'hide'
      }
    }
  }, {
    // Thumbnail Elements Dependant on Base Theme
    conditions: [{
      element: '[name="_envira_gallery[lightbox_theme]"]',
      type: 'value',
      operator: 'array',
      condition: ['base_dark', 'base_light']
    }, {
      element: '[name="_envira_gallery[thumbnails]"]',
      type: 'checked',
      operator: 'is'
    }],
    actions: {
      "if": {
        element: '#envira-config-thumbnail-button',
        action: 'show'
      },
      "else": {
        element: '#envira-config-thumbnail-button',
        action: 'hide'
      }
    }
  }, {
    // Automatic
    conditions: {
      element: '[name="_envira_gallery[layout]"]',
      type: 'value',
      operator: 'array',
      condition: ['automatic']
    },
    actions: {
      "if": [{
        element: '#envira-config-standard-settings-box, #envira-config-additional-copy-box, #envira-config-title-caption-column-mobile',
        action: 'hide'
      }, {
        element: '#envira-config-justified-settings-box, #envira-config-mobile-justified-row-height, #envira-config-additional-copy-box-automatic, #envira-config-title-caption-automatic-mobile',
        action: 'show'
      }],
      "else": [{
        element: '#envira-config-standard-settings-box, #envira-config-additional-copy-box, #envira-config-title-caption-column-mobile',
        action: 'show'
      }, {
        element: '#envira-config-justified-settings-box, #envira-config-mobile-justified-row-height, #envira-config-additional-copy-box-automatic, #envira-config-title-caption-automatic-mobile',
        action: 'hide'
      }]
    }
  }, {
    // Automatic
    conditions: {
      element: '[name="_envira_gallery[layout]"]',
      type: 'value',
      operator: 'array',
      condition: ['blogroll', 'automatic']
    },
    actions: {
      "if": [{
        element: '#envira-config-columns-box',
        action: 'hide'
      }],
      "else": [{
        element: '#envira-config-columns-box',
        action: 'show'
      }]
    }
  }, {
    // Gallery Description
    conditions: {
      element: '[name="_envira_gallery[description_position]"]',
      type: 'value',
      operator: 'array',
      condition: ['0']
    },
    actions: {
      "if": [{
        element: '#envira-config-description-box',
        action: 'hide'
      }],
      "else": [{
        element: '#envira-config-description-box',
        action: 'show'
      }]
    }
  }, {
    // Gallery image size
    conditions: {
      element: '[name="_envira_gallery[image_size]"]',
      type: 'value',
      operator: 'array',
      condition: ['default']
    },
    actions: {
      "if": [{
        element: '#envira-config-crop-size-box, #envira-config-crop-box',
        action: 'show'
      }],
      "else": [{
        element: '#envira-config-crop-size-box, #envira-config-crop-box',
        action: 'hide'
      }]
    }
  }, {
    // Automatic
    conditions: {
      element: '[name="_envira_gallery[layout]"]',
      type: 'value',
      operator: 'array',
      condition: ['blogroll']
    },
    actions: {
      "if": [{
        element: '#envira-config-crop-size-box, #envira-config-crop-box',
        action: 'hide'
      }],
      "else": [{
        element: '#envira-config-crop-size-box, #envira-config-crop-box',
        action: 'show'
      }]
    }
  }, {
    // Automatic
    conditions: {
      element: '[name="_envira_gallery[layout]"]',
      type: 'value',
      operator: 'array',
      condition: ['square']
    },
    actions: {
      "if": [{
        element: '#envira-config-square-size-box',
        action: 'show'
      }, {
        element: '#envira-config-crop-size-box, #envira-config-crop-box, #envira-config-image-size-box',
        action: 'hide'
      }],
      "else": [{
        element: '#envira-config-square-size-box',
        action: 'hide'
      }, {
        element: '#envira-config-crop-size-box, #envira-config-crop-box, #envira-config-image-size-box',
        action: 'show'
      }]
    }
  }, {
    // Gallery Lightbox
    conditions: {
      element: '[name="_envira_gallery[lightbox_enabled]"]',
      type: 'checked',
      operator: 'is'
    },
    actions: {
      "if": [{
        element: '#envira-lightbox-settings',
        action: 'show'
      }],
      "else": [{
        element: '#envira-lightbox-settings',
        action: 'hide'
      }]
    }
  }, {
    // Gallery Lightbox
    conditions: {
      element: '[name="_envira_gallery[lightbox_enabled]"]',
      type: 'checked',
      operator: 'is'
    },
    actions: {
      "if": [{
        element: '#envira-config-lightbox-enabled-link',
        action: 'hide'
      }],
      "else": [{
        element: '#envira-config-lightbox-enabled-link',
        action: 'show'
      }]
    }
  }, {
    // Album Mobile Images
    conditions: {
      element: '[name="_envira_gallery[mobile]"]',
      type: 'checked',
      operator: 'is'
    },
    actions: {
      "if": [{
        element: '#envira-config-mobile-size-box',
        action: 'show'
      }],
      "else": [{
        element: '#envira-config-mobile-size-box',
        action: 'hide'
      }]
    }
  }, {
    conditions: [{
      element: '[name="_envira_gallery[mobile_thumbnails]"]',
      type: 'checked',
      operator: 'is'
    }, {
      element: '[name="_envira_gallery[mobile_lightbox]"]',
      type: 'checked',
      operator: 'is'
    }, {
      element: '[name="_envira_gallery[mobile_lightbox]"]',
      type: 'checked',
      operator: 'is'
    }],
    actions: {
      "if": {
        element: '#envira-config-mobile-thumbnails-width-box, #envira-config-mobile-thumbnails-height-box',
        action: 'show'
      },
      "else": {
        element: '#envira-config-mobile-thumbnails-width-box, #envira-config-mobile-thumbnails-height-box',
        action: 'hide'
      }
    }
  }, {
    // Album Mobile Touchwipe
    conditions: {
      element: '[name="_envira_gallery[lazy_loading]"]',
      type: 'checked',
      operator: 'is'
    },
    actions: {
      "if": [{
        element: '#envira-config-lazy-loading-delay',
        action: 'show'
      }],
      "else": [{
        element: '#envira-config-lazy-loading-delay',
        action: 'hide'
      }]
    }
  }, {
    // Gallery Sorting
    conditions: {
      element: '#envira-config-sorting-defaults',
      type: 'value',
      operator: 'array',
      condition: ['0', '1']
    },
    actions: {
      "if": [{
        element: '#envira-config-sorting-direction-box',
        action: 'hide'
      }],
      "else": [{
        element: '#envira-config-sorting-direction-box',
        action: 'show'
      }]
    }
  }]);
});
})();

/******/ })()
;