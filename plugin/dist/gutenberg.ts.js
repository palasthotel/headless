!function(){"use strict";var e=window.wp.plugins,t=window.wp.element,n=window.wp.editPost,o=window.wp.components;var r=window.wp.data;(0,e.registerPlugin)("headless-plugin",{icon:()=>null,render:function(){const{state:e,reload:s}=(()=>{const[e,n]=(0,t.useState)("idle"),o=(0,r.useSelect)((e=>e("core/editor").getCurrentPost()));return{state:e,reload:()=>{n("loading"),fetch((e=>window.Headless.ajax+"?action="+window.Headless.actions.reload+"&post="+e)(o.id)).then((e=>e.json())).then((e=>{e.success?n("success"):(console.error(e),n("error"))})).catch((()=>{n("error")})).finally((()=>{setTimeout((()=>{n("idle")}),4e3)}))}}})();return(0,t.createElement)(n.PluginDocumentSettingPanel,{title:"Headless"},(0,t.createElement)(o.Button,{variant:"secondary",disabled:"idle"!=e,onClick:s},"Reload cache"),"idle"==e&&(0,t.createElement)("p",{className:"description"},"Reload contents in headless frontend."),"loading"==e&&(0,t.createElement)("p",null,"Loading..."),"success"==e&&(0,t.createElement)("p",null,"🎉 Success"),"error"==e&&(0,t.createElement)("p",null,"🚨 Something went wrong"))}}),document.addEventListener("DOMContentLoaded",(function(){const e=(0,r.select)("core/editor"),t=e.getCurrentPostId,n=e.isSavingPost,o=(0,r.dispatch)("core/editor").autosave,s=document.createElement("a");s.className="components-button",s.addEventListener("click",(e=>{e.preventDefault(),n()||o().then((()=>{console.debug("open ",s.href,s.target),window.open(s.href,s.target)}))})),(0,r.subscribe)((()=>{n()?s.classList.add("is-disabled"):s.classList.remove("is-disabled")})),setInterval((function(){const e=(e=>window.Headless.preview_url.replace(window.Headless.post_id_placeholder,`${e}`))(t()),n=document.querySelectorAll("[target^=wp-preview-]");n&&n.length&&n.forEach((t=>{t.setAttribute("href",e)}));const o=a.querySelector(".edit-post-post-preview-dropdown .components-menu-group:has(.edit-post-header-preview__grouping-external)");if(!o)return;const r="headless-preview-link";if(o.querySelector("#"+r))return;const i=o.querySelector(".edit-post-header-preview__grouping-external a"),c=i.getAttribute("target");s.text=i.textContent;const l=document.createElement("span");l.className="dashicons dashicons-external",s.append(l),s.target=c,console.debug(c),s.href=e,s.id=r,o.querySelector('[role="group"]').append(s)}),300);const a=document.querySelector("#editor")}))}();