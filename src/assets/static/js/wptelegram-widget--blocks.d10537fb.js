/*! For license information please see wptelegram-widget--blocks.d10537fb.js.LICENSE.txt */
!function(e){var t={};function n(r){if(t[r])return t[r].exports;var i=t[r]={i:r,l:!1,exports:{}};return e[r].call(i.exports,i,i.exports,n),i.l=!0,i.exports}n.m=e,n.c=t,n.d=function(e,t,r){n.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:r})},n.r=function(e){"undefined"!==typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},n.t=function(e,t){if(1&t&&(e=n(e)),8&t)return e;if(4&t&&"object"===typeof e&&e&&e.__esModule)return e;var r=Object.create(null);if(n.r(r),Object.defineProperty(r,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var i in e)n.d(r,i,function(t){return e[t]}.bind(null,i));return r},n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,"a",t),t},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.p="/",n(n.s=222)}({0:function(e,t){e.exports=window.React},1:function(e,t,n){"use strict";e.exports=n(144)},101:function(e,t,n){"use strict";n.d(t,"a",(function(){return c}));var r=n(23),i=n(46);var a=n(71);function o(e,t,n){return(o=Object(a.a)()?Reflect.construct:function(e,t,n){var r=[null];r.push.apply(r,t);var a=new(Function.bind.apply(e,r));return n&&Object(i.a)(a,n.prototype),a}).apply(null,arguments)}function c(e){var t="function"===typeof Map?new Map:void 0;return(c=function(e){if(null===e||(n=e,-1===Function.toString.call(n).indexOf("[native code]")))return e;var n;if("function"!==typeof e)throw new TypeError("Super expression must either be null or a function");if("undefined"!==typeof t){if(t.has(e))return t.get(e);t.set(e,a)}function a(){return o(e,arguments,Object(r.a)(this).constructor)}return a.prototype=Object.create(e.prototype,{constructor:{value:a,enumerable:!1,writable:!0,configurable:!0}}),Object(i.a)(a,e)})(e)}},144:function(e,t,n){"use strict";n(145);var r=n(0),i=60103;if(t.Fragment=60107,"function"===typeof Symbol&&Symbol.for){var a=Symbol.for;i=a("react.element"),t.Fragment=a("react.fragment")}var o=r.__SECRET_INTERNALS_DO_NOT_USE_OR_YOU_WILL_BE_FIRED.ReactCurrentOwner,c=Object.prototype.hasOwnProperty,s={key:!0,ref:!0,__self:!0,__source:!0};function l(e,t,n){var r,a={},l=null,u=null;for(r in void 0!==n&&(l=""+n),void 0!==t.key&&(l=""+t.key),void 0!==t.ref&&(u=t.ref),t)c.call(t,r)&&!s.hasOwnProperty(r)&&(a[r]=t[r]);if(e&&e.defaultProps)for(r in t=e.defaultProps)void 0===a[r]&&(a[r]=t[r]);return{$$typeof:i,type:e,key:l,ref:u,props:a,_owner:o.current}}t.jsx=l,t.jsxs=l},145:function(e,t,n){"use strict";var r=Object.getOwnPropertySymbols,i=Object.prototype.hasOwnProperty,a=Object.prototype.propertyIsEnumerable;function o(e){if(null===e||void 0===e)throw new TypeError("Object.assign cannot be called with null or undefined");return Object(e)}e.exports=function(){try{if(!Object.assign)return!1;var e=new String("abc");if(e[5]="de","5"===Object.getOwnPropertyNames(e)[0])return!1;for(var t={},n=0;n<10;n++)t["_"+String.fromCharCode(n)]=n;if("0123456789"!==Object.getOwnPropertyNames(t).map((function(e){return t[e]})).join(""))return!1;var r={};return"abcdefghijklmnopqrst".split("").forEach((function(e){r[e]=e})),"abcdefghijklmnopqrst"===Object.keys(Object.assign({},r)).join("")}catch(i){return!1}}()?Object.assign:function(e,t){for(var n,c,s=o(e),l=1;l<arguments.length;l++){for(var u in n=Object(arguments[l]))i.call(n,u)&&(s[u]=n[u]);if(r){c=r(n);for(var f=0;f<c.length;f++)a.call(n,c[f])&&(s[c[f]]=n[c[f]])}}return s}},16:function(e,t,n){"use strict";function r(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}n.d(t,"a",(function(){return r}))},19:function(e,t,n){"use strict";function r(e,t){for(var n=0;n<t.length;n++){var r=t[n];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(e,r.key,r)}}function i(e,t,n){return t&&r(e.prototype,t),n&&r(e,n),e}n.d(t,"a",(function(){return i}))},201:function(e,t,n){"use strict";n.d(t,"a",(function(){return r}));var r=function(e,t){var n;return t?null===(n=window[e])||void 0===n?void 0:n[t]:window[e]}},202:function(e,t){e.exports=window.wp.url},21:function(e,t){e.exports=window.wp.components},222:function(e,t,n){e.exports=n(336)},23:function(e,t,n){"use strict";function r(e){return(r=Object.setPrototypeOf?Object.getPrototypeOf:function(e){return e.__proto__||Object.getPrototypeOf(e)})(e)}n.d(t,"a",(function(){return r}))},27:function(e,t,n){"use strict";function r(e){if(void 0===e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return e}n.d(t,"a",(function(){return r}))},336:function(e,t,n){"use strict";n.r(t);var r,i=n(78),a=n(5),o=function(e,t,n){var r=t.filter((function(t){return null===e||void 0===e?void 0:e[t]})).map((function(t){return"".concat(t,'="').concat(e[t],'"')})),i=r.length?" "+r.join(" "):"";return"[".concat(n," ").concat(i,"]")},c=n(1),s=["widget_width","widget_height","username"],l=function(e){var t=e.attributes;return Object(c.jsx)(c.Fragment,{children:o(t,s,"wptelegram-ajax-widget")})},u=n(36),f=n(50),b=n(21),d=n(49),p=(null===(r=window.wptelegram_widget)||void 0===r?void 0:r.savedSettings)||{};Object(i.registerBlockType)("wptelegram/widget-ajax-channel-feed",{title:Object(a.a)("Telegram Channel Ajax Feed"),icon:"format-aside",category:"wptelegram",attributes:{username:{type:"string",default:""},widget_width:{type:"string",default:"100%"},widget_height:{type:"string",default:"600"}},edit:function(e){var t=e.attributes,n=e.setAttributes,r=e.className,i=t.widget_width,o=t.widget_height,s=t.username,j=Object(u.useCallback)((function(e){return n({widget_width:e})}),[n]),g=Object(u.useCallback)((function(e){return n({widget_height:e})}),[n]),h=Object(u.useCallback)((function(e){return n({username:null===e||void 0===e?void 0:e.replace("@","")})}),[n]);return Object(c.jsxs)(c.Fragment,{children:[Object(c.jsx)(f.InspectorControls,{children:Object(c.jsxs)(b.PanelBody,{title:Object(a.a)("Widget Options"),children:[Object(c.jsx)(b.TextControl,{label:Object(a.a)("Username"),value:s,onChange:h,help:Object(d.sprintf)("%s %s",Object(a.a)("Channel username."),Object(a.a)("Leave empty for default.")),placeholder:(null===p||void 0===p?void 0:p.username)||"WPTelegram"}),Object(c.jsx)(b.TextControl,{label:Object(a.a)("Widget Width"),value:i,onChange:j}),Object(c.jsx)(b.TextControl,{label:Object(a.a)("Widget Height"),value:o,onChange:g,type:"number"})]})}),Object(c.jsxs)("div",{className:r,children:[Object(c.jsxs)("label",{children:[Object(c.jsx)(b.Dashicon,{icon:"shortcode"}),Object(c.jsx)("span",{children:Object(a.a)("Telegram Channel Ajax Feed")})]}),Object(c.jsx)("code",{className:"widget-shortcode",children:Object(c.jsx)(l,{attributes:t})})]})]})},save:function(e){var t=e.attributes;return Object(c.jsx)("div",{children:Object(c.jsx)(l,{attributes:t})})}});var j=["author_photo","num_messages","widget_width"],g=function(e){var t=e.attributes;return Object(c.jsx)(c.Fragment,{children:o(t,j,"wptelegram-widget")})},h=[{label:"Auto",value:"auto"},{label:"Always show",value:"always_show"},{label:"Always hide",value:"always_hide"}];Object(i.registerBlockType)("wptelegram/widget-channel-feed",{title:Object(a.a)("Telegram Channel Feed"),icon:"format-aside",category:"wptelegram",attributes:{widget_width:{type:"string",default:"100"},author_photo:{type:"string",default:"auto"},num_messages:{type:"string",default:"5"}},edit:function(e){var t=e.attributes,n=e.setAttributes,r=e.className,i=t.widget_width,o=t.author_photo,s=t.num_messages,l=Object(u.useCallback)((function(e){return n({author_photo:e})}),[n]),d=Object(u.useCallback)((function(e){return n({widget_width:e})}),[n]),p=Object(u.useCallback)((function(e){return n({num_messages:e})}),[n]);return Object(c.jsxs)(c.Fragment,{children:[Object(c.jsx)(f.InspectorControls,{children:Object(c.jsxs)(b.PanelBody,{title:Object(a.a)("Widget Options"),children:[Object(c.jsx)(b.TextControl,{label:Object(a.a)("Widget Width"),value:i,onChange:d,type:"number",min:"10",max:"100"}),Object(c.jsx)(b.RadioControl,{label:Object(a.a)("Author Photo"),selected:o,onChange:l,options:h}),Object(c.jsx)(b.TextControl,{label:Object(a.a)("Number of Messages"),value:s,onChange:p,type:"number",min:"1",max:"50"})]})}),Object(c.jsxs)("div",{className:r,children:[Object(c.jsxs)("label",{children:[Object(c.jsx)(b.Dashicon,{icon:"shortcode"}),Object(c.jsx)("span",{children:Object(a.a)("Telegram Channel Feed")})]}),Object(c.jsx)("code",{className:"widget-shortcode",children:Object(c.jsx)(g,{attributes:t})})]},"shortcode")]})},save:function(e){var t=e.attributes;return Object(c.jsx)("div",{children:Object(c.jsx)(g,{attributes:t})})}});var O=n(4),m=function(e){var t=e.fill,n=void 0===t?"#ffffff":t;return Object(c.jsx)(b.SVG,{width:"19px",height:"16px",viewBox:"0 0 19 16",children:Object(c.jsx)(b.G,{fill:"none",children:Object(c.jsx)(b.Path,{fill:n,d:"M0.465,6.638 L17.511,0.073 C18.078,-0.145 18.714,0.137 18.932,0.704 C19.009,0.903 19.026,1.121 18.981,1.33 L16.042,15.001 C15.896,15.679 15.228,16.111 14.549,15.965 C14.375,15.928 14.211,15.854 14.068,15.748 L8.223,11.443 C7.874,11.185 7.799,10.694 8.057,10.345 C8.082,10.311 8.109,10.279 8.139,10.249 L14.191,4.322 C14.315,4.201 14.317,4.002 14.195,3.878 C14.091,3.771 13.926,3.753 13.8,3.834 L5.602,9.138 C5.112,9.456 4.502,9.528 3.952,9.333 L0.486,8.112 C0.077,7.967 -0.138,7.519 0.007,7.11 C0.083,6.893 0.25,6.721 0.465,6.638 Z"})})})},v=function(e){var t=e.link,n=e.text,r=e.isEditing;return Object(c.jsx)(b.Button,{isLarge:!0,href:t,className:"join-link",icon:Object(c.jsx)(m,{}),target:r?"_blank":null,rel:"noopener noreferrer",children:n})},w=n(201),y=function(e){var t,n=e.setAttributes,r=e.attributes,i=r.alignment,o=r.link,s=r.text,l=(t="uiData",Object(w.a)("wptelegram_widget",t)),d=l.join_link_text,p=l.join_link_url;Object(u.useEffect)((function(){o||n({link:p}),s||n({text:d})}),[]);var j=Object(u.useCallback)((function(e){return n({link:e})}),[n]),g=Object(u.useCallback)((function(e){return n({text:e})}),[n]),h=Object(u.useCallback)((function(e){return n({alignment:e})}),[n]);return Object(c.jsxs)(u.Fragment,{children:[Object(c.jsx)(f.InspectorControls,{children:Object(c.jsxs)(b.PanelBody,{title:Object(a.a)("Button details"),children:[Object(c.jsx)(b.TextControl,{label:Object(a.a)("Channel Link"),value:o||"",onChange:j,type:"url"}),Object(c.jsx)(b.TextControl,{label:Object(a.a)("Button text"),value:s||"",onChange:g})]})},"controls"),Object(c.jsx)(f.BlockControls,{children:Object(c.jsx)(f.BlockAlignmentToolbar,{value:i,onChange:h})})]})};Object(i.registerBlockType)("wptelegram/widget-join-channel",{title:Object(a.a)("Join Telegram Channel"),icon:Object(c.jsx)(m,{fill:"#555d66"}),category:"wptelegram",attributes:{link:{type:"string"},text:{type:"string"},alignment:{type:"string",default:"center"}},getEditWrapperProps:function(e){var t=e.alignment;if(["left","center","right","wide","full"].includes(t))return{"data-align":t}},edit:function(e){var t=e.attributes,n=e.setAttributes,r=e.className;return Object(c.jsxs)(c.Fragment,{children:[Object(c.jsx)(y,{attributes:t,setAttributes:n}),Object(c.jsx)("div",{className:r,children:Object(c.jsx)(v,Object(O.a)(Object(O.a)({},t),{},{isEditing:!0}))})]})},save:function(e){var t=e.attributes,n=t.alignment;return Object(c.jsx)("div",{className:"wp-block-wptelegram-widget-join-channel align"+n,children:Object(c.jsx)(v,Object(O.a)({},t))})}});var x={url:{type:"string",default:""},iframe_src:{type:"string",default:""},userpic:{type:"boolean",default:!0},alignment:{type:"string",default:"center"}},_=n(40),C=n(101),k=(n(23),n(88),n(34));function S(e,t){S=function(e,t){return new a(e,void 0,t)};var n=Object(C.a)(RegExp),r=RegExp.prototype,i=new WeakMap;function a(e,t,r){var a=n.call(this,e,t);return i.set(a,r||i.get(e)),a}function o(e,t){var n=i.get(t);return Object.keys(n).reduce((function(t,r){return t[r]=e[n[r]],t}),Object.create(null))}return Object(k.a)(a,n),a.prototype.exec=function(e){var t=r.exec.call(this,e);return t&&(t.groups=o(t,this)),t},a.prototype[Symbol.replace]=function(e,t){if("string"===typeof t){var n=i.get(this);return r[Symbol.replace].call(this,e,t.replace(/\$<([^>]+)>/g,(function(e,t){return"$"+n[t]})))}if("function"===typeof t){var a=this;return r[Symbol.replace].call(this,e,(function(){var e=[];return e.push.apply(e,arguments),"object"!==Object(_.a)(e[e.length-1])&&e.push(o(e,a)),t.apply(this,e)}))}return r[Symbol.replace].call(this,e,t)},S.apply(this,arguments)}var P=n(16),T=n(19),L=n(27),R=n(37),B=n(202),U={border:"2px solid #f71717"},E=function(e){var t=e.error,n=e.label,r=e.onChangeURL,i=e.onSubmit,o=e.url,s=t?U:null;return Object(c.jsx)(b.Placeholder,{icon:"wordpress-alt",label:n,className:"wp-block-embed-telegram",children:Object(c.jsxs)("form",{onSubmit:i,children:[Object(c.jsx)("input",{"aria-label":n,className:"components-placeholder__input",onChange:r,placeholder:"https://t.me/WPTelegram/102",style:s,type:"url",value:o||""}),Object(c.jsx)(b.Button,{isLarge:!0,type:"submit",children:Object(a.a)("Embed")})]})})},N=function(e){var t=e.userpic,n=e.toggleUserPic,r=e.showEditButton,i=e.switchBackToURLInput,o=e.alignment,s=e.changeAlignment;return Object(c.jsxs)(u.Fragment,{children:[Object(c.jsx)(f.InspectorControls,{children:Object(c.jsx)(b.PanelBody,{title:Object(a.a)("Options"),children:Object(c.jsx)(b.ToggleControl,{label:Object(a.a)("Author Photo"),checked:t,onChange:n})})}),Object(c.jsxs)(f.BlockControls,{children:[Object(c.jsx)(f.BlockAlignmentToolbar,{value:o,onChange:s}),Object(c.jsx)(b.ToolbarGroup,{children:r&&Object(c.jsx)(b.ToolbarButton,{className:"components-toolbar__control",title:Object(a.a)("Edit URL"),icon:"edit",onClick:i})})]})]})},I=window.wptelegram_widget.assets.message_view_url,A=function(e){Object(k.a)(n,e);var t=Object(R.a)(n);function n(e){var r;return Object(P.a)(this,n),(r=t.call(this,e)).iframe_ref=void 0,r.iframe_ref=Object(u.createRef)(),r.switchBackToURLInput=r.switchBackToURLInput.bind(Object(L.a)(r)),r.getIframeSrc=r.getIframeSrc.bind(Object(L.a)(r)),r.toggleUserPic=r.toggleUserPic.bind(Object(L.a)(r)),r.resizeIframe=r.resizeIframe.bind(Object(L.a)(r)),r.setUrl=r.setUrl.bind(Object(L.a)(r)),r.handleOnChangeURL=r.handleOnChangeURL.bind(Object(L.a)(r)),r.handleOnChangeAlign=r.handleOnChangeAlign.bind(Object(L.a)(r)),r.onLoad=r.onLoad.bind(Object(L.a)(r)),r.state={loading:!0,editingURL:!1,error:!1,url:r.props.attributes.url,userpic:r.props.attributes.userpic,iframe_height:null},r}return Object(T.a)(n,[{key:"toggleUserPic",value:function(){var e=!this.state.userpic,t=this.props.attributes.iframe_src;t=Object(B.addQueryArgs)(t,{userpic:e}),this.setState({userpic:e,loading:!0}),this.props.setAttributes({userpic:e,iframe_src:t})}},{key:"setUrl",value:function(e){e&&e.preventDefault();var t=this.state.url,n=S(/^(?:https?:\/\/)?t\.me\/([a-z][0-9_a-z]{3,30}[0-9a-z])\/([0-9]+)$/i,{username:1,message_id:2}),r=t.match(n);if(null===r)this.setState({error:!0});else{var i=this.getIframeSrc(r.groups),a=this.props.setAttributes;this.setState({loading:!0,editingURL:!1,error:!1}),a({url:t,iframe_src:i})}}},{key:"getIframeSrc",value:function(e){return I.replace("%username%",e.username).replace("%message_id%",e.message_id).replace("%userpic%","".concat(this.state.userpic))}},{key:"switchBackToURLInput",value:function(){this.setState({editingURL:!0})}},{key:"componentDidMount",value:function(){window.addEventListener("resize",this.resizeIframe)}},{key:"componentWillUnmount",value:function(){window.removeEventListener("resize",this.resizeIframe)}},{key:"resizeIframe",value:function(){if(null!==this.iframe_ref.current&&"undefined"!==typeof this.iframe_ref.current.contentWindow){var e=this.iframe_ref.current.contentWindow.document.body.scrollHeight;e!==this.state.iframe_height&&this.setState({iframe_height:e})}}},{key:"handleOnChangeURL",value:function(e){this.setState({url:e.target.value})}},{key:"handleOnChangeAlign",value:function(e){this.resizeIframe(),this.props.setAttributes({alignment:e})}},{key:"onLoad",value:function(){this.setState({loading:!1}),this.resizeIframe()}},{key:"render",value:function(){var e=this.state,t=e.loading,n=e.editingURL,r=e.url,i=e.error,o=e.userpic,s=this.props.className,l=this.props.attributes,f=l.alignment,d=l.iframe_src,p=Object(a.a)("Telegram post URL");if(n||!d)return Object(c.jsx)(E,{label:p,error:i,url:r,onChangeURL:this.handleOnChangeURL,onSubmit:this.setUrl});var j=t?0:this.state.iframe_height;return Object(c.jsxs)(u.Fragment,{children:[Object(c.jsx)(N,{userpic:o,toggleUserPic:this.toggleUserPic,showEditButton:!0,switchBackToURLInput:this.switchBackToURLInput,alignment:f,changeAlignment:this.handleOnChangeAlign}),t&&Object(c.jsxs)("div",{className:"wp-block-embed is-loading",children:[Object(c.jsx)(b.Spinner,{}),Object(c.jsx)("p",{children:Object(a.a)("Loading\u2026")})]}),Object(c.jsx)("div",{className:s+" wptelegram-widget-message",children:Object(c.jsx)("div",{className:"wp-block-embed__content-wrapper",children:Object(c.jsx)(b.FocusableIframe,{iframeRef:this.iframe_ref,frameBorder:"0",scrolling:"no",src:d,onLoad:this.onLoad,height:j,children:"Your Browser Does Not Support iframes!"})})})]})}}]),n}(u.Component);Object(i.registerBlockType)("wptelegram/widget-single-post",{title:Object(a.a)("Telegram Single Post"),icon:"format-aside",category:"wptelegram",getEditWrapperProps:function(e){var t=e.alignment;if(["left","center","right","wide","full"].includes(t))return{"data-align":t}},attributes:x,edit:A,save:function(e){var t=e.attributes,n=t.alignment,r=t.iframe_src;return Object(c.jsx)("div",{className:"wp-block-wptelegram-widget-single-post wptelegram-widget-message align"+n,children:Object(c.jsx)("iframe",{title:"Telegram post",frameBorder:"0",scrolling:"no",src:r,children:"Your Browser Does Not Support iframes!"})})},deprecated:[{attributes:x,save:function(e){var t=e.attributes,n=t.alignment,r=t.iframe_src;return Object(c.jsx)("div",{className:"wp-block-wptelegram-widget-single-post wptelegram-widget-message align"+n,children:Object(c.jsx)("iframe",{frameBorder:"0",scrolling:"no",src:r,children:"Your Browser Does Not Support iframes!"})})}}]})},34:function(e,t,n){"use strict";n.d(t,"a",(function(){return i}));var r=n(46);function i(e,t){if("function"!==typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function");e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,writable:!0,configurable:!0}}),t&&Object(r.a)(e,t)}},36:function(e,t){e.exports=window.wp.element},37:function(e,t,n){"use strict";n.d(t,"a",(function(){return o}));var r=n(23),i=n(71),a=n(88);function o(e){var t=Object(i.a)();return function(){var n,i=Object(r.a)(e);if(t){var o=Object(r.a)(this).constructor;n=Reflect.construct(i,arguments,o)}else n=i.apply(this,arguments);return Object(a.a)(this,n)}}},4:function(e,t,n){"use strict";n.d(t,"a",(function(){return a}));var r=n(7);function i(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(e);t&&(r=r.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,r)}return n}function a(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?i(Object(n),!0).forEach((function(t){Object(r.a)(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):i(Object(n)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}},40:function(e,t,n){"use strict";function r(e){return(r="function"===typeof Symbol&&"symbol"===typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"===typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e})(e)}n.d(t,"a",(function(){return r}))},46:function(e,t,n){"use strict";function r(e,t){return(r=Object.setPrototypeOf||function(e,t){return e.__proto__=t,e})(e,t)}n.d(t,"a",(function(){return r}))},49:function(e,t){e.exports=window.wp.i18n},5:function(e,t,n){"use strict";n.d(t,"c",(function(){return c})),n.d(t,"a",(function(){return s})),n.d(t,"b",(function(){return l}));var r=n(49),i="",a=r.createI18n,o=(null===a||void 0===a?void 0:a())||r,c=function(e,t){i=t,o.setLocaleData(e,t)},s=function(e){return o.__(e,i)},l=function(){return"rtl"===document.documentElement.dir}},50:function(e,t){e.exports=window.wp.blockEditor},7:function(e,t,n){"use strict";function r(e,t,n){return t in e?Object.defineProperty(e,t,{value:n,enumerable:!0,configurable:!0,writable:!0}):e[t]=n,e}n.d(t,"a",(function(){return r}))},71:function(e,t,n){"use strict";function r(){if("undefined"===typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"===typeof Proxy)return!0;try{return Date.prototype.toString.call(Reflect.construct(Date,[],(function(){}))),!0}catch(e){return!1}}n.d(t,"a",(function(){return r}))},78:function(e,t){e.exports=window.wp.blocks},88:function(e,t,n){"use strict";n.d(t,"a",(function(){return a}));var r=n(40),i=n(27);function a(e,t){return!t||"object"!==Object(r.a)(t)&&"function"!==typeof t?Object(i.a)(e):t}}});
//# sourceMappingURL=wptelegram-widget--blocks.d10537fb.js.map