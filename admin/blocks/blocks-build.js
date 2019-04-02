/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 2);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ }),
/* 1 */
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ }),
/* 2 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);

// EXTERNAL MODULE: ./src/admin/blocks/channel-feed/editor.scss
var editor = __webpack_require__(0);

// CONCATENATED MODULE: ./src/admin/blocks/channel-feed/block.js
//  Import CSS.

var el = wp.element.createElement;
var __ = wp.i18n.__;
var registerBlockType = wp.blocks.registerBlockType;
var InspectorControls = wp.editor.InspectorControls;
var _wp$components = wp.components,
    PanelBody = _wp$components.PanelBody,
    RadioControl = _wp$components.RadioControl,
    TextControl = _wp$components.TextControl,
    Dashicon = _wp$components.Dashicon;
registerBlockType('wptelegram/widget-channel-feed', {
  title: __('Telegram Channel Feed'),
  icon: 'format-aside',
  category: 'widgets',
  attributes: {
    widget_width: {
      type: 'string',
      default: '100'
    },
    author_photo: {
      type: 'string',
      default: 'auto'
    },
    num_messages: {
      type: 'string',
      default: '5'
    }
  },
  edit: function edit(_ref) {
    var attributes = _ref.attributes,
        setAttributes = _ref.setAttributes,
        className = _ref.className;
    var widget_width = attributes.widget_width,
        author_photo = attributes.author_photo,
        num_messages = attributes.num_messages;
    var controls = [el(InspectorControls, null, el(PanelBody, {
      title: __('Widget Options')
    }, el(TextControl, {
      label: __('Widget Width'),
      value: widget_width,
      onChange: function onChange(newWidth) {
        return setAttributes({
          widget_width: newWidth
        });
      },
      type: "number",
      min: "10",
      max: "100"
    }), el(RadioControl, {
      label: __('Author Photo'),
      selected: author_photo,
      onChange: function onChange(newStyle) {
        return setAttributes({
          author_photo: newStyle
        });
      },
      options: [{
        label: 'Auto',
        value: 'auto'
      }, {
        label: 'Always show',
        value: 'always_show'
      }, {
        label: 'Always hide',
        value: 'always_hide'
      }]
    }), el(TextControl, {
      label: __('Number of Messages'),
      value: num_messages,
      onChange: function onChange(newValue) {
        return setAttributes({
          num_messages: newValue
        });
      },
      type: "number",
      min: "1",
      max: "50"
    })))];
    var label = el("label", null, el(Dashicon, {
      icon: "shortcode"
    }), el("span", null, __('Telegram Channel Feed')));
    var text = '[wptelegram-widget';
    text += attributes.widget_width ? " widget_width=\"".concat(attributes.widget_width, "\"") : '';
    text += " author_photo=\"".concat(attributes.author_photo, "\" num_messages=\"").concat(attributes.num_messages, "\"");
    return [controls, el("div", {
      className: className
    }, label, el("code", {
      className: "widget-shortcode"
    }, text))];
  },
  save: function save(_ref2) {
    var attributes = _ref2.attributes;
    var widget_width = attributes.widget_width,
        author_photo = attributes.author_photo,
        num_messages = attributes.num_messages;
    var text = "[wptelegram-widget author_photo=\"".concat(author_photo, "\" num_messages=\"").concat(num_messages, "\"");
    text += widget_width ? " widget_width=\"".concat(widget_width, "\"]") : ']';
    return el("div", null, text);
  }
});
// EXTERNAL MODULE: ./src/admin/blocks/single-post/editor.scss
var single_post_editor = __webpack_require__(1);

// CONCATENATED MODULE: ./src/admin/blocks/single-post/placeholder.js
/**
 * WordPress dependencies
 */
var placeholder_el = wp.element.createElement;
var placeholder_ = wp.i18n.__;
var placeholder_wp$components = wp.components,
    Button = placeholder_wp$components.Button,
    Placeholder = placeholder_wp$components.Placeholder;

var EmbedPlaceholder = function EmbedPlaceholder(props) {
  var label = props.label,
      url = props.url,
      onSubmit = props.onSubmit,
      onChangeURL = props.onChangeURL,
      error = props.error;
  var style = error ? {
    border: '2px solid #f71717'
  } : {};
  return placeholder_el(Placeholder, {
    icon: "wordpress-alt",
    label: label,
    className: "wp-block-embed-telegram"
  }, placeholder_el("form", {
    onSubmit: onSubmit
  }, placeholder_el("input", {
    style: style,
    type: "url",
    value: url || '',
    className: "components-placeholder__input",
    "aria-label": label,
    placeholder: "https://t.me/WPTelegram/102",
    onChange: onChangeURL
  }), placeholder_el(Button, {
    isLarge: true,
    type: "submit"
  }, placeholder_('Embed'))));
};

/* harmony default export */ var placeholder = (EmbedPlaceholder);
// CONCATENATED MODULE: ./src/admin/blocks/single-post/controls.js
/**
 * WordPress dependencies
 */
var controls_el = wp.element.createElement;
var controls_ = wp.i18n.__;
var Fragment = wp.element.Fragment;
var _wp$editor = wp.editor,
    controls_InspectorControls = _wp$editor.InspectorControls,
    BlockControls = _wp$editor.BlockControls,
    BlockAlignmentToolbar = _wp$editor.BlockAlignmentToolbar;
var controls_wp$components = wp.components,
    controls_PanelBody = controls_wp$components.PanelBody,
    IconButton = controls_wp$components.IconButton,
    Toolbar = controls_wp$components.Toolbar,
    controls_TextControl = controls_wp$components.TextControl,
    ToggleControl = controls_wp$components.ToggleControl;

var AllControls = function AllControls(props) {
  var userpic = props.userpic,
      toggleUserPic = props.toggleUserPic,
      showEditButton = props.showEditButton,
      switchBackToURLInput = props.switchBackToURLInput,
      alignment = props.alignment,
      changeAlignment = props.changeAlignment;
  return controls_el(Fragment, null, controls_el(controls_InspectorControls, null, controls_el(controls_PanelBody, {
    title: controls_('Options')
  }, controls_el(ToggleControl, {
    label: controls_('Author Photo'),
    checked: userpic,
    onChange: toggleUserPic
  }))), controls_el(BlockControls, null, controls_el(BlockAlignmentToolbar, {
    value: alignment,
    onChange: changeAlignment
  }), controls_el(Toolbar, null, showEditButton && controls_el(IconButton, {
    className: "components-toolbar__control",
    label: controls_('Edit URL'),
    icon: "edit",
    onClick: switchBackToURLInput
  }))));
};

/* harmony default export */ var controls = (AllControls);
// CONCATENATED MODULE: ./src/admin/blocks/single-post/edit.js
function _typeof(obj) { if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

function _wrapRegExp(re, groups) { _wrapRegExp = function _wrapRegExp(re, groups) { return new BabelRegExp(re, groups); }; var _RegExp = _wrapNativeSuper(RegExp); var _super = RegExp.prototype; var _groups = new WeakMap(); function BabelRegExp(re, groups) { var _this = _RegExp.call(this, re); _groups.set(_this, groups); return _this; } _inherits(BabelRegExp, _RegExp); BabelRegExp.prototype.exec = function (str) { var result = _super.exec.call(this, str); if (result) result.groups = buildGroups(result, this); return result; }; BabelRegExp.prototype[Symbol.replace] = function (str, substitution) { if (typeof substitution === "string") { var groups = _groups.get(this); return _super[Symbol.replace].call(this, str, substitution.replace(/\$<([^>]+)>/g, function (_, name) { return "$" + groups[name]; })); } else if (typeof substitution === "function") { var _this = this; return _super[Symbol.replace].call(this, str, function () { var args = []; args.push.apply(args, arguments); if (_typeof(args[args.length - 1]) !== "object") { args.push(buildGroups(args, _this)); } return substitution.apply(this, args); }); } else { return _super[Symbol.replace].call(this, str, substitution); } }; function buildGroups(result, re) { var g = _groups.get(re); return Object.keys(g).reduce(function (groups, name) { groups[name] = result[g[name]]; return groups; }, Object.create(null)); } return _wrapRegExp.apply(this, arguments); }

function _wrapNativeSuper(Class) { var _cache = typeof Map === "function" ? new Map() : undefined; _wrapNativeSuper = function _wrapNativeSuper(Class) { if (Class === null || !_isNativeFunction(Class)) return Class; if (typeof Class !== "function") { throw new TypeError("Super expression must either be null or a function"); } if (typeof _cache !== "undefined") { if (_cache.has(Class)) return _cache.get(Class); _cache.set(Class, Wrapper); } function Wrapper() { return _construct(Class, arguments, _getPrototypeOf(this).constructor); } Wrapper.prototype = Object.create(Class.prototype, { constructor: { value: Wrapper, enumerable: false, writable: true, configurable: true } }); return _setPrototypeOf(Wrapper, Class); }; return _wrapNativeSuper(Class); }

function isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Date.prototype.toString.call(Reflect.construct(Date, [], function () {})); return true; } catch (e) { return false; } }

function _construct(Parent, args, Class) { if (isNativeReflectConstruct()) { _construct = Reflect.construct; } else { _construct = function _construct(Parent, args, Class) { var a = [null]; a.push.apply(a, args); var Constructor = Function.bind.apply(Parent, a); var instance = new Constructor(); if (Class) _setPrototypeOf(instance, Class.prototype); return instance; }; } return _construct.apply(null, arguments); }

function _isNativeFunction(fn) { return Function.toString.call(fn).indexOf("[native code]") !== -1; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

function _possibleConstructorReturn(self, call) { if (call && (_typeof(call) === "object" || typeof call === "function")) { return call; } return _assertThisInitialized(self); }

function _getPrototypeOf(o) { _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf : function _getPrototypeOf(o) { return o.__proto__ || Object.getPrototypeOf(o); }; return _getPrototypeOf(o); }

function _assertThisInitialized(self) { if (self === void 0) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function"); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, writable: true, configurable: true } }); if (superClass) _setPrototypeOf(subClass, superClass); }

function _setPrototypeOf(o, p) { _setPrototypeOf = Object.setPrototypeOf || function _setPrototypeOf(o, p) { o.__proto__ = p; return o; }; return _setPrototypeOf(o, p); }

/**
 * Internal dependencies
 */


/**
 * WordPress dependencies
 */

var edit_el = wp.element.createElement;
var _wp$element = wp.element,
    Component = _wp$element.Component,
    edit_Fragment = _wp$element.Fragment,
    createRef = _wp$element.createRef;
var edit_ = wp.i18n.__;
var edit_wp$components = wp.components,
    FocusableIframe = edit_wp$components.FocusableIframe,
    Spinner = edit_wp$components.Spinner;
var message_view_url = window.wptelegram_widget.misc.message_view_url;
var addQueryArgs = wp.url.addQueryArgs; // export function getPostEditComponent() {

var edit_default =
/*#__PURE__*/
function (_Component) {
  _inherits(_default, _Component);

  function _default() {
    var _this;

    _classCallCheck(this, _default);

    _this = _possibleConstructorReturn(this, _getPrototypeOf(_default).apply(this, arguments));
    _this.iframe_ref = createRef();
    _this.switchBackToURLInput = _this.switchBackToURLInput.bind(_assertThisInitialized(_this));
    _this.getIframeSrc = _this.getIframeSrc.bind(_assertThisInitialized(_this));
    _this.toggleUserPic = _this.toggleUserPic.bind(_assertThisInitialized(_this));
    _this.resizeIframe = _this.resizeIframe.bind(_assertThisInitialized(_this));
    _this.setUrl = _this.setUrl.bind(_assertThisInitialized(_this));
    _this.state = {
      loading: true,
      editingURL: false,
      error: false,
      url: _this.props.attributes.url,
      userpic: _this.props.attributes.userpic,
      iframe_height: null
    };
    return _this;
  }

  _createClass(_default, [{
    key: "toggleUserPic",
    value: function toggleUserPic() {
      var userpic = !this.state.userpic;
      var loading = true;
      var iframe_src = this.props.attributes.iframe_src;
      iframe_src = addQueryArgs(iframe_src, {
        userpic: userpic
      });
      this.setState({
        userpic: userpic,
        loading: loading
      });
      this.props.setAttributes({
        userpic: userpic,
        iframe_src: iframe_src
      });
    }
  }, {
    key: "setUrl",
    value: function setUrl(event) {
      if (event) {
        event.preventDefault();
      }

      var url = this.state.url;

      var regex = _wrapRegExp(/^(?:https?:\/\/)?t\.me\/([a-z][a-z0-9_]{3,30}[a-z0-9])\/(\d+)$/i, {
        username: 1,
        message_id: 2
      });

      var match = url.match(regex); // validate URL

      if (null === match) {
        this.setState({
          error: true
        });
      } else {
        var iframe_src = this.getIframeSrc(match.groups);
        var setAttributes = this.props.setAttributes;
        this.setState({
          loading: true,
          editingURL: false,
          error: false
        });
        setAttributes({
          url: url,
          iframe_src: iframe_src
        });
      }
    }
  }, {
    key: "getIframeSrc",
    value: function getIframeSrc(data) {
      return message_view_url.replace('%username%', data.username).replace('%message_id%', data.message_id).replace('%userpic%', this.state.userpic);
    }
  }, {
    key: "switchBackToURLInput",
    value: function switchBackToURLInput() {
      this.setState({
        editingURL: true
      });
    }
  }, {
    key: "componentDidMount",
    value: function componentDidMount() {
      window.addEventListener('resize', this.resizeIframe);
    }
  }, {
    key: "componentWillUnmount",
    value: function componentWillUnmount() {
      window.removeEventListener('resize', this.resizeIframe);
    }
  }, {
    key: "resizeIframe",
    value: function resizeIframe() {
      if (null === this.iframe_ref.current || 'undefined' === typeof this.iframe_ref.current.contentWindow) {
        return;
      }

      var iframe_height = this.iframe_ref.current.contentWindow.document.body.scrollHeight;
      console.log(iframe_height);

      if (iframe_height !== this.state.iframe_height) {
        this.setState({
          iframe_height: iframe_height
        });
      }
    }
  }, {
    key: "render",
    value: function render() {
      var _this2 = this;

      var _this$state = this.state,
          loading = _this$state.loading,
          editingURL = _this$state.editingURL,
          url = _this$state.url,
          error = _this$state.error,
          userpic = _this$state.userpic;
      var _this$props = this.props,
          className = _this$props.className,
          setAttributes = _this$props.setAttributes;
      var _this$props$attribute = this.props.attributes,
          alignment = _this$props$attribute.alignment,
          iframe_src = _this$props$attribute.iframe_src;

      var label = edit_('Telegram post URL');

      if (editingURL || !iframe_src) {
        return edit_el(placeholder, {
          label: label,
          error: error,
          url: url,
          onChangeURL: function onChangeURL(event) {
            return _this2.setState({
              url: event.target.value
            });
          },
          onSubmit: this.setUrl
        });
      }

      var iframe_height = loading ? 0 : this.state.iframe_height;
      return edit_el(edit_Fragment, null, edit_el(controls, {
        userpic: userpic,
        toggleUserPic: this.toggleUserPic,
        showEditButton: true,
        switchBackToURLInput: this.switchBackToURLInput,
        alignment: alignment,
        changeAlignment: function changeAlignment(alignment) {
          _this2.resizeIframe();

          setAttributes({
            alignment: alignment
          });
        }
      }), loading && edit_el("div", {
        className: "wp-block-embed is-loading"
      }, edit_el(Spinner, null), edit_el("p", null, edit_('Loading…'))), edit_el("div", {
        className: className + ' wptelegram-widget-message'
      }, edit_el("div", {
        className: 'wp-block-embed__content-wrapper'
      }, edit_el(FocusableIframe, {
        iframeRef: this.iframe_ref,
        frameBorder: "0",
        scrolling: "no",
        src: iframe_src,
        onLoad: function onLoad() {
          _this2.setState({
            loading: false
          });

          _this2.resizeIframe();
        },
        height: iframe_height
      }, "Your Browser Does Not Support iframes!"))));
    }
  }]);

  return _default;
}(Component);


;
// CONCATENATED MODULE: ./src/admin/blocks/single-post/block.js
//  Import CSS.

/**
 * Internal dependencies
 */


/**
 * WordPress dependencies
 */

var block_el = wp.element.createElement;
var block_ = wp.i18n.__;
var block_registerBlockType = wp.blocks.registerBlockType;
block_registerBlockType('wptelegram/widget-single-post', {
  title: block_('Telegram Single Post'),
  icon: 'format-aside',
  category: 'widgets',
  getEditWrapperProps: function getEditWrapperProps(attributes) {
    var alignment = attributes.alignment;

    if (['left', 'center', 'right', 'wide', 'full'].includes(alignment)) {
      return {
        'data-align': alignment
      };
    }
  },
  attributes: {
    url: {
      type: 'string',
      default: ''
    },
    iframe_src: {
      type: 'string',
      default: ''
    },
    userpic: {
      type: 'boolean',
      default: true
    },
    alignment: {
      type: 'string',
      default: 'center'
    }
  },
  edit: edit_default,
  save: function save(_ref) {
    var attributes = _ref.attributes;
    var alignment = attributes.alignment,
        iframe_src = attributes.iframe_src;
    return block_el("div", {
      className: 'wp-block-wptelegram-widget-single-post wptelegram-widget-message align' + alignment
    }, block_el("iframe", {
      frameBorder: "0",
      scrolling: "no",
      src: iframe_src
    }, "Your Browser Does Not Support iframes!"));
  }
});
// CONCATENATED MODULE: ./src/admin/blocks/index.js
/**
 * Gutenberg Blocks
 *
 * All blocks should be included here since this is the file that
 * Webpack is compiling as the input file.
 */



/***/ })
/******/ ]);