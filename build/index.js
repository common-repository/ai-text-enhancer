!function(){var t={184:function(t,e){var r;!function(){"use strict";var n={}.hasOwnProperty;function o(){for(var t=[],e=0;e<arguments.length;e++){var r=arguments[e];if(r){var a=typeof r;if("string"===a||"number"===a)t.push(r);else if(Array.isArray(r)){if(r.length){var i=o.apply(null,r);i&&t.push(i)}}else if("object"===a){if(r.toString!==Object.prototype.toString&&!r.toString.toString().includes("[native code]")){t.push(r.toString());continue}for(var s in r)n.call(r,s)&&r[s]&&t.push(s)}}}return t.join(" ")}t.exports?(o.default=o,t.exports=o):void 0===(r=function(){return o}.apply(e,[]))||(t.exports=r)}()}},e={};function r(n){var o=e[n];if(void 0!==o)return o.exports;var a=e[n]={exports:{}};return t[n](a,a.exports,r),a.exports}r.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return r.d(e,{a:e}),e},r.d=function(t,e){for(var n in e)r.o(e,n)&&!r.o(t,n)&&Object.defineProperty(t,n,{enumerable:!0,get:e[n]})},r.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},function(){"use strict";function t(){return t=Object.assign?Object.assign.bind():function(t){for(var e=1;e<arguments.length;e++){var r=arguments[e];for(var n in r)Object.prototype.hasOwnProperty.call(r,n)&&(t[n]=r[n])}return t},t.apply(this,arguments)}var e=window.wp.element,n=r(184),o=r.n(n);const a=["core/paragraph"],{__:__}=wp.i18n,{createHigherOrderComponent:i}=wp.compose,{Fragment:s}=wp.element,{BlockControls:c}=wp.blockEditor,{ToolbarGroup:l,ToolbarButton:u}=wp.components;wp.hooks.addFilter("blocks.registerBlockType","ai-text-enhancer/set-toolbar-button-attribute",((t,e)=>a.includes(e)?Object.assign({},t,{attributes:Object.assign({},t.attributes,{isTransformedByAI:{type:"string"},origContent:{type:"string"}})}):t));const p=i((t=>r=>{if(!a.includes(r.name))return(0,e.createElement)(t,r);const{attributes:n,setAttributes:o}=r,{content:i,origContent:p,isTransformedByAI:d}=n;return(0,e.createElement)(s,null,(0,e.createElement)(c,{group:"block"},(0,e.createElement)(l,null,(0,e.createElement)(u,{icon:(0,e.createElement)("img",{src:`${phpvars.pluginurl}../src/attributes/icon3.svg`}),label:__("AI Text Enhancer","aite"),onClick:()=>{o({origContent:i});let t=wp.data.select("core/block-editor").getSelectionStart().offset,e=wp.data.select("core/block-editor").getSelectionEnd().offset;(async t=>{o({isTransformedByAI:"loading"});const e=new FormData;e.append("action","ai_request"),e.append("nonce",phpvars.nonce),e.append("text",t);const r=await fetch(phpvars.ajaxurl,{method:"POST",body:e}),n=await r.json();if("Failure"===n.status&&alert(n.error),"Success"===n.status){let e=n.rewrites.slice(-1),r=i.replace(t,e[0]);o({content:r}),o({isTransformedByAI:"custom"})}if(n.id){let e=n.choices[0].message.content,r=i.replace(t,e);o({content:r}),o({isTransformedByAI:"custom"})}})(t<e?i.substring(t,e):i)}}))),(0,e.createElement)(t,r))}),"withToolbarButton");wp.hooks.addFilter("editor.BlockEdit","ai-text-enhancer/with-toolbar-button",p);const d=i((r=>n=>{if(!a.includes(n.name))return(0,e.createElement)(r,n);const{attributes:o}=n,{isTransformedByAI:i}=o;return i&&"custom"===i?(0,e.createElement)(r,t({},n,{className:"ai-enhanced-text"})):i&&"loading"===i?(0,e.createElement)(r,t({},n,{className:"ai-loading"})):(0,e.createElement)(r,n)}),"withToolbarButtonProp");wp.hooks.addFilter("editor.BlockListBlock","ai-text-enhancer/with-toolbar-button-prop",d),wp.hooks.addFilter("blocks.getSaveContent.extraProps","ai-text-enhancer/save-toolbar-button-attribute",((t,e,r)=>{if(a.includes(e.name)){const{isTransformedByAI:e}=r;e&&"custom"===e&&(t.className=o()(t.className,"ai-enhanced-text"))}return t}))}()}();