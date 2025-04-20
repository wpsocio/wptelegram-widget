import{g as j}from"./_commonjsHelpers-CqkleIqs.js";function E(r,e){for(var t=0;t<e.length;t++){const s=e[t];if(typeof s!="string"&&!Array.isArray(s)){for(const u in s)if(u!=="default"&&!(u in r)){const i=Object.getOwnPropertyDescriptor(s,u);i&&Object.defineProperty(r,u,i.get?i:{enumerable:!0,get:()=>s[u]})}}}return Object.freeze(Object.defineProperty(r,Symbol.toStringTag,{value:"Module"}))}var p={exports:{}},a={},l,w;function q(){return w||(w=1,l=React),l}/**
 * @license React
 * react-jsx-runtime.production.min.js
 *
 * Copyright (c) Facebook, Inc. and its affiliates.
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 */var O;function S(){if(O)return a;O=1;var r=q(),e=Symbol.for("react.element"),t=Symbol.for("react.fragment"),s=Object.prototype.hasOwnProperty,u=r.__SECRET_INTERNALS_DO_NOT_USE_OR_YOU_WILL_BE_FIRED.ReactCurrentOwner,i={key:!0,ref:!0,__self:!0,__source:!0};function v(_,n,x){var o,c={},f=null,y=null;x!==void 0&&(f=""+x),n.key!==void 0&&(f=""+n.key),n.ref!==void 0&&(y=n.ref);for(o in n)s.call(n,o)&&!i.hasOwnProperty(o)&&(c[o]=n[o]);if(_&&_.defaultProps)for(o in n=_.defaultProps,n)c[o]===void 0&&(c[o]=n[o]);return{$$typeof:e,type:_,key:f,ref:y,props:c,_owner:u.current}}return a.Fragment=t,a.jsx=v,a.jsxs=v,a}var g;function k(){return g||(g=1,p.exports=S()),p.exports}var $=k(),d,b;function T(){return b||(b=1,d=wp.i18n),d}var m=T();const I=j(m),J=E({__proto__:null,default:I},[m]);let h="";const R=m.createI18n,P=(R==null?void 0:R())||J,A=(r,e)=>{h=e,P.setLocaleData(r,e)},C=r=>P.__(r,h);var D=q();const L=j(D),F=E({__proto__:null,default:L},[D]),M=(r,e)=>{const t=window[r];return e?t==null?void 0:t[e]:t};export{L as R,C as _,m as a,F as b,M as g,$ as j,D as r,A as s};
//# sourceMappingURL=get-plugin-data-D3L8TS3p.js.map
