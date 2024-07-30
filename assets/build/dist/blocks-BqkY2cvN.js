import{_ as n,a as I,u as F}from"./usePluginData-BTmlva0s.js";const X=(e,t,o)=>{const s=t.filter(a=>e==null?void 0:e[a]).map(a=>`${a}="${e[a]}"`),l=s.length?` ${s.join(" ")}`:"";return`[${o} ${l}]`},U=["widget_width","widget_height","username"],A="wptelegram-ajax-widget",C=({attributes:e})=>ReactJSXRuntime.jsx(ReactJSXRuntime.Fragment,{children:X(e,U,A)});var J;const x=((J=window.wptelegram_widget)==null?void 0:J.savedSettings)||{},D=({attributes:e,setAttributes:t,className:o})=>{const{widget_width:s,widget_height:l,username:a}=e,m=wp.element.useCallback(i=>t({widget_width:i}),[t]),u=wp.element.useCallback(i=>t({widget_height:i}),[t]),g=wp.element.useCallback(i=>t({username:i==null?void 0:i.replace("@","")}),[t]);return ReactJSXRuntime.jsxs(ReactJSXRuntime.Fragment,{children:[ReactJSXRuntime.jsx(wp.blockEditor.InspectorControls,{children:ReactJSXRuntime.jsxs(wp.components.PanelBody,{title:n("Widget Options"),children:[ReactJSXRuntime.jsx(wp.components.TextControl,{label:n("Username"),value:a,onChange:g,help:I("%s %s",n("Channel username."),n("Leave empty for default.")),placeholder:(x==null?void 0:x.username)||"WPTelegram"}),ReactJSXRuntime.jsx(wp.components.TextControl,{label:n("Widget Width"),value:s,onChange:m}),ReactJSXRuntime.jsx(wp.components.TextControl,{label:n("Widget Height"),value:l,onChange:u,type:"number"})]})}),ReactJSXRuntime.jsxs("div",{className:o,children:[ReactJSXRuntime.jsxs("label",{children:[ReactJSXRuntime.jsx(wp.components.Dashicon,{icon:"shortcode"}),ReactJSXRuntime.jsx("span",{children:n("Telegram Channel Ajax Feed")})]}),ReactJSXRuntime.jsx("code",{className:"widget-shortcode",children:ReactJSXRuntime.jsx(C,{attributes:e})})]})]})},z={username:{type:"string",default:""},widget_width:{type:"string",default:"100%"},widget_height:{type:"string",default:"600"}};wp.blocks.registerBlockType("wptelegram/widget-ajax-channel-feed",{title:n("Telegram Channel Ajax Feed"),icon:"format-aside",category:"wptelegram",attributes:z,edit:D,save:({attributes:e})=>ReactJSXRuntime.jsx("div",{children:ReactJSXRuntime.jsx(C,{attributes:e})})});const O=["author_photo","num_messages","widget_width"],H="wptelegram-widget",b=({attributes:e})=>ReactJSXRuntime.jsx(ReactJSXRuntime.Fragment,{children:X(e,O,H)}),V=()=>[{label:"Auto",value:"auto"},{label:"Always show",value:"always_show"},{label:"Always hide",value:"always_hide"}],G=({attributes:e,setAttributes:t,className:o})=>{const{widget_width:s,author_photo:l,num_messages:a}=e,m=wp.element.useCallback(i=>t({author_photo:i}),[t]),u=wp.element.useCallback(i=>t({widget_width:i}),[t]),g=wp.element.useCallback(i=>t({num_messages:Number.parseInt(i)||5}),[t]);return ReactJSXRuntime.jsxs(ReactJSXRuntime.Fragment,{children:[ReactJSXRuntime.jsx(wp.blockEditor.InspectorControls,{children:ReactJSXRuntime.jsxs(wp.components.PanelBody,{title:n("Widget Options"),children:[ReactJSXRuntime.jsx(wp.components.TextControl,{label:n("Widget Width"),value:s,onChange:u,type:"number",min:"10",max:"100"}),ReactJSXRuntime.jsx(wp.components.RadioControl,{label:n("Author Photo"),selected:l,onChange:m,options:V()}),ReactJSXRuntime.jsx(wp.components.TextControl,{label:n("Number of Messages"),value:a,onChange:g,type:"number",min:"1",max:"50"})]})}),ReactJSXRuntime.jsxs("div",{className:o,children:[ReactJSXRuntime.jsxs("label",{children:[ReactJSXRuntime.jsx(wp.components.Dashicon,{icon:"shortcode"}),ReactJSXRuntime.jsx("span",{children:n("Telegram Channel Feed")})]}),ReactJSXRuntime.jsx("code",{className:"widget-shortcode",children:ReactJSXRuntime.jsx(b,{attributes:e})})]},"shortcode")]})},M={widget_width:{type:"string",default:"100"},author_photo:{type:"string",default:"auto"},num_messages:{type:"string",default:"5"}};wp.blocks.registerBlockType("wptelegram/widget-channel-feed",{title:n("Telegram Channel Feed"),icon:"format-aside",category:"wptelegram",attributes:M,edit:G,save:({attributes:e})=>ReactJSXRuntime.jsx("div",{children:ReactJSXRuntime.jsx(b,{attributes:e})})});const Y=e=>F("wptelegram_widget",e),K=({setAttributes:e,attributes:t})=>{const{alignment:o,link:s,text:l}=t,{join_link_text:a,join_link_url:m}=Y("uiData");wp.element.useEffect(()=>{s||e({link:m}),l||e({text:a})},[]);const u=wp.element.useCallback(p=>e({link:p}),[e]),g=wp.element.useCallback(p=>e({text:p}),[e]),i=wp.element.useCallback(p=>e({alignment:p}),[e]);return ReactJSXRuntime.jsxs(wp.element.Fragment,{children:[ReactJSXRuntime.jsx(wp.blockEditor.InspectorControls,{children:ReactJSXRuntime.jsxs(wp.components.PanelBody,{title:n("Button details"),children:[ReactJSXRuntime.jsx(wp.components.TextControl,{label:n("Channel Link"),value:s||"",onChange:u,type:"url"}),ReactJSXRuntime.jsx(wp.components.TextControl,{label:n("Button text"),value:l||"",onChange:g})]})},"controls"),ReactJSXRuntime.jsx(wp.blockEditor.BlockControls,{children:ReactJSXRuntime.jsx(wp.blockEditor.BlockAlignmentToolbar,{value:o,onChange:i})})]})},_=({fill:e="#ffffff"})=>ReactJSXRuntime.jsx(wp.components.SVG,{width:"19px",height:"16px",viewBox:"0 0 19 16",children:ReactJSXRuntime.jsx(wp.components.G,{fill:"none",children:ReactJSXRuntime.jsx(wp.components.Path,{fill:e,d:"M0.465,6.638 L17.511,0.073 C18.078,-0.145 18.714,0.137 18.932,0.704 C19.009,0.903 19.026,1.121 18.981,1.33 L16.042,15.001 C15.896,15.679 15.228,16.111 14.549,15.965 C14.375,15.928 14.211,15.854 14.068,15.748 L8.223,11.443 C7.874,11.185 7.799,10.694 8.057,10.345 C8.082,10.311 8.109,10.279 8.139,10.249 L14.191,4.322 C14.315,4.201 14.317,4.002 14.195,3.878 C14.091,3.771 13.926,3.753 13.8,3.834 L5.602,9.138 C5.112,9.456 4.502,9.528 3.952,9.333 L0.486,8.112 C0.077,7.967 -0.138,7.519 0.007,7.11 C0.083,6.893 0.25,6.721 0.465,6.638 Z"})})}),Q=({link:e,text:t,isEditing:o})=>ReactJSXRuntime.jsx(wp.components.Button,{href:e,className:"join-link",icon:ReactJSXRuntime.jsx(_,{}),target:o?"_blank":void 0,rel:"noopener noreferrer",children:t}),f={link:{type:"string"},text:{type:"string"},alignment:{type:"string",default:"center"}};wp.blocks.registerBlockType("wptelegram/widget-join-channel",{apiVersion:3,title:n("Join Telegram Channel"),icon:ReactJSXRuntime.jsx(_,{fill:"#555d66"}),category:"wptelegram",attributes:f,getEditWrapperProps:e=>{const{alignment:t}=e;return["left","center","right","wide","full"].includes(t)?{"data-align":t}:{"data-align":""}},edit:({attributes:e,setAttributes:t})=>{const o=wp.blockEditor.useBlockProps({className:`align${e.alignment}`});return ReactJSXRuntime.jsxs("div",{...o,children:[ReactJSXRuntime.jsx(K,{attributes:e,setAttributes:t}),ReactJSXRuntime.jsx(Q,{...e,isEditing:!0})]})},deprecated:[{attributes:f,save(){return null}}]});const Z=e=>{const{userpic:t,toggleUserPic:o,showEditButton:s,switchBackToURLInput:l,alignment:a,changeAlignment:m}=e;return ReactJSXRuntime.jsxs(wp.element.Fragment,{children:[ReactJSXRuntime.jsx(wp.blockEditor.InspectorControls,{children:ReactJSXRuntime.jsx(wp.components.PanelBody,{title:n("Options"),children:ReactJSXRuntime.jsx(wp.components.ToggleControl,{label:n("Author Photo"),checked:t,onChange:o})})}),ReactJSXRuntime.jsxs(wp.blockEditor.BlockControls,{children:[ReactJSXRuntime.jsx(wp.blockEditor.BlockAlignmentToolbar,{value:a,onChange:m}),ReactJSXRuntime.jsx(wp.components.ToolbarGroup,{children:s&&ReactJSXRuntime.jsx(wp.components.ToolbarButton,{className:"components-toolbar__control",title:n("Edit URL"),icon:"edit",onClick:l})})]})]})},q={border:"2px solid #f71717"},ee=({error:e,label:t,onChangeURL:o,onSubmit:s,url:l})=>{const a=e?q:void 0;return ReactJSXRuntime.jsx(wp.components.Placeholder,{icon:"wordpress-alt",label:t,className:"wp-block-embed-telegram",children:ReactJSXRuntime.jsxs("form",{onSubmit:s,children:[ReactJSXRuntime.jsx("input",{"aria-label":t,className:"components-placeholder__input",onChange:o,placeholder:"https://t.me/WPTelegram/102",style:a,type:"url",value:l||""}),ReactJSXRuntime.jsx(wp.components.Button,{type:"submit",children:n("Embed")})]})})},{message_view_url:te}=window.wptelegram_widget.assets;function ne(e){const[t,o]=wp.element.useState("loading"),[s,l]=wp.element.useState(!1),[a,m]=wp.element.useState(e.attributes.url||""),[u,g]=wp.element.useState(e.attributes.userpic||!0),[i,p]=wp.element.useState(0),{className:k}=e,{alignment:y,iframe_src:S}=e.attributes,v=n("Telegram post URL");function T(c){m(c.target.value)}function B(c){c&&c.preventDefault();const h=/^(?:https?:\/\/)?t\.me\/(?<username>[a-z][a-z0-9_]{3,30}[a-z0-9])\/(?<message_id>\d+)$/i,d=a.match(h);if(d===null)o("error");else{const w=E(d.groups),{setAttributes:W}=e;o("loading"),l(!1),W({url:a,iframe_src:w})}}function E(c){return te.replace("%username%",c.username).replace("%message_id%",c.message_id).replace("%userpic%",`${u}`)}function P(){g(c=>{const h=!c;o("loading");let{iframe_src:d}=e.attributes;return d=wp.url.addQueryArgs(d,{userpic:u}),e.setAttributes({userpic:h,iframe_src:d}),h})}function L(c){R(),e.setAttributes({alignment:c})}const r=wp.element.useRef(),$=wp.compose.useMergeRefs([r,wp.compose.useFocusableIframe()]);function R(){var h,d,w;if((r==null?void 0:r.current)===null||typeof((h=r==null?void 0:r.current)==null?void 0:h.contentWindow)>"u")return;const c=(w=(d=r==null?void 0:r.current)==null?void 0:d.contentWindow)==null?void 0:w.document.body.scrollHeight;c!==i&&p(c||0)}if(wp.element.useEffect(()=>(window.addEventListener("resize",R),()=>{window.removeEventListener("resize",R)}),[]),s||!S)return ReactJSXRuntime.jsx(ee,{label:v,error:t==="error",url:a,onChangeURL:T,onSubmit:B});const N=t==="loading"?0:i;return ReactJSXRuntime.jsxs(wp.element.Fragment,{children:[ReactJSXRuntime.jsx(Z,{userpic:u,toggleUserPic:P,showEditButton:!0,switchBackToURLInput:()=>l(!0),alignment:y,changeAlignment:L}),t==="loading"&&ReactJSXRuntime.jsxs("div",{className:"wp-block-embed is-loading",children:[ReactJSXRuntime.jsx(wp.components.Spinner,{}),ReactJSXRuntime.jsx("p",{children:n("Loading…")})]}),ReactJSXRuntime.jsx("div",{className:`${k} wptelegram-widget-message`,children:ReactJSXRuntime.jsx("div",{className:"wp-block-embed__content-wrapper",children:ReactJSXRuntime.jsx("iframe",{ref:$,src:S,onLoad:()=>{o("idle"),R()},height:N,title:n("Telegram post"),children:"Your Browser Does Not Support iframes!"})})})]})}const j={url:{type:"string",default:""},iframe_src:{type:"string",default:""},userpic:{type:"boolean",default:!0},alignment:{type:"string",default:"center"}};wp.blocks.registerBlockType("wptelegram/widget-single-post",{title:n("Telegram Single Post"),icon:"format-aside",category:"wptelegram",getEditWrapperProps:e=>{const{alignment:t}=e;return["left","center","right","wide","full"].includes(t)?{"data-align":t.toString()}:{"data-align":""}},attributes:j,edit:ne,save:({attributes:e})=>{const{alignment:t,iframe_src:o}=e;return ReactJSXRuntime.jsx("div",{className:`wp-block-wptelegram-widget-single-post wptelegram-widget-message align${t}`,children:ReactJSXRuntime.jsx("iframe",{title:n("Telegram post"),src:o,children:"Your Browser Does Not Support iframes!"})})},deprecated:[{attributes:j,save:({attributes:e})=>{const{alignment:t,iframe_src:o}=e;return ReactJSXRuntime.jsx("div",{className:`wp-block-wptelegram-widget-single-post wptelegram-widget-message align${t}`,children:ReactJSXRuntime.jsx("iframe",{src:o,title:n("Telegram post"),children:"Your Browser Does Not Support iframes!"})})}}]});
//# sourceMappingURL=blocks-BqkY2cvN.js.map
