/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/components/ReloadPanel.tsx":
/*!****************************************!*\
  !*** ./src/components/ReloadPanel.tsx ***!
  \****************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ ReloadPanel; }
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_edit_post__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/edit-post */ "@wordpress/edit-post");
/* harmony import */ var _wordpress_edit_post__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_edit_post__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _hooks_use_reload__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../hooks/use-reload */ "./src/hooks/use-reload.ts");
/* harmony import */ var _store_window__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../store/window */ "./src/store/window.ts");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_5__);






const buildController = () => {
  const fns = new Map();
  return {
    add: (frontend, fn) => {
      fns.set(frontend, fn);
    },
    run: () => {
      fns.forEach(fn => {
        fn();
      });
    }
  };
};
const FrontendItem = ({
  index,
  baseUrl,
  controller,
  onStateChanged
}) => {
  const {
    state,
    reload
  } = (0,_hooks_use_reload__WEBPACK_IMPORTED_MODULE_3__.useReload)(index);
  const post = (0,_hooks_use_reload__WEBPACK_IMPORTED_MODULE_3__.usePost)();
  const url = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.useMemo)(() => {
    const link = post.link;
    const url = new URL(link);
    ;
    return baseUrl.replace(/^\/|\/$/g, '') + url.pathname;
  }, [post.link]);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.useEffect)(() => {
    controller.add(index, reload);
  }, [index]);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.useEffect)(() => {
    onStateChanged(index, state);
  }, [state]);
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    title: url
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    href: url,
    target: "_blank"
  }, "Frontend ", index), state == "loading" && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(react__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, " \uD83E\uDDF9"), state == "success" && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(react__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, " \u2705"), state == "error" && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(react__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, " \uD83D\uDEA8"));
};
function ReloadPanel() {
  const frontends = (0,_store_window__WEBPACK_IMPORTED_MODULE_4__.getFrontends)();
  const controller = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.useMemo)(() => buildController(), []);
  const [loadingState, setLoadingState] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.useState)({});
  const canRevalidate = (0,_hooks_use_reload__WEBPACK_IMPORTED_MODULE_3__.useCanRevalidate)();
  const handleClick = () => {
    controller.run();
  };
  const isLoading = Object.values(loadingState).find(value => value == true);
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_edit_post__WEBPACK_IMPORTED_MODULE_1__.PluginDocumentSettingPanel, {
    title: "Headless"
  }, canRevalidate ? (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(react__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("ol", null, frontends.map((frontend, index) => {
    return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("li", {
      key: index
    }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(FrontendItem, {
      baseUrl: frontend,
      index: index,
      controller: controller,
      onStateChanged: (index, state) => {
        setLoadingState(prev => {
          const copy = {
            ...prev
          };
          copy[index] = state == "loading";
          return copy;
        });
      }
    }));
  })), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Button, {
    variant: "secondary",
    disabled: isLoading || !canRevalidate,
    onClick: handleClick
  }, "Revalidate cache")) : (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", {
    className: "description"
  }, "Only published contents can be revalidated."));
}

/***/ }),

/***/ "./src/hooks/use-reload.ts":
/*!*********************************!*\
  !*** ./src/hooks/use-reload.ts ***!
  \*********************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   useCanRevalidate: function() { return /* binding */ useCanRevalidate; },
/* harmony export */   usePost: function() { return /* binding */ usePost; },
/* harmony export */   useReload: function() { return /* binding */ useReload; }
/* harmony export */ });
/* harmony import */ var _store_window__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../store/window */ "./src/store/window.ts");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_2__);



const usePost = () => {
  return (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_1__.useSelect)(select => select("core/editor").getCurrentPost(), []);
};
const useCanRevalidate = () => {
  return usePost().status == "publish";
};
const useReload = frontend => {
  const [state, setState] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.useState)("idle");
  const post = usePost();
  const path = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.useMemo)(() => {
    if (post?.link) {
      const url = new URL(post?.link);
      return url.pathname;
    } else {
      return "";
    }
  }, [post.link]);
  return {
    state,
    reload: () => {
      setState("loading");
      (async () => {
        try {
          const response = await fetch((0,_store_window__WEBPACK_IMPORTED_MODULE_0__.getReloadAjaxUrl)(frontend, path));
          const json = await response.json();
          if (json.success) {
            setState("success");
          } else {
            console.error(json);
            setState("error");
          }
        } catch (e) {
          setState("error");
        }
      })();
    }
  };
};

/***/ }),

/***/ "./src/preview.ts":
/*!************************!*\
  !*** ./src/preview.ts ***!
  \************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   writeInterstitialMessage: function() { return /* binding */ writeInterstitialMessage; }
/* harmony export */ });
function writeInterstitialMessage(targetDocument) {
  let markup = '';
  markup += `
		<style>
			body {
				margin: 0;
			}
			.editor-post-preview-button__interstitial-message {
				display: flex;
				flex-direction: column;
				align-items: center;
				justify-content: center;
				height: 100vh;
				width: 100vw;
			}
			@-webkit-keyframes paint {
				0% {
					stroke-dashoffset: 0;
				}
			}
			@-moz-keyframes paint {
				0% {
					stroke-dashoffset: 0;
				}
			}
			@-o-keyframes paint {
				0% {
					stroke-dashoffset: 0;
				}
			}
			@keyframes paint {
				0% {
					stroke-dashoffset: 0;
				}
			}
			.editor-post-preview-button__interstitial-message svg {
				width: 192px;
				height: 192px;
				stroke: #555d66;
				stroke-width: 0.75;
			}
			.editor-post-preview-button__interstitial-message svg .outer,
			.editor-post-preview-button__interstitial-message svg .inner {
				stroke-dasharray: 280;
				stroke-dashoffset: 280;
				-webkit-animation: paint 1.5s ease infinite alternate;
				-moz-animation: paint 1.5s ease infinite alternate;
				-o-animation: paint 1.5s ease infinite alternate;
				animation: paint 1.5s ease infinite alternate;
			}
			p {
				text-align: center;
				font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
			}
		</style>
	`;
  markup += `
        <div class="editor-post-preview-button__interstitial-message">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 96 96">
                <path class="outer" d="M48 12c19.9 0 36 16.1 36 36S67.9 84 48 84 12 67.9 12 48s16.1-36 36-36" fill="none" />
                <path class="inner" d="M69.5 46.4c0-3.9-1.4-6.7-2.6-8.8-1.6-2.6-3.1-4.9-3.1-7.5 0-2.9 2.2-5.7 5.4-5.7h.4C63.9 19.2 56.4 16 48 16c-11.2 0-21 5.7-26.7 14.4h2.1c3.3 0 8.5-.4 8.5-.4 1.7-.1 1.9 2.4.2 2.6 0 0-1.7.2-3.7.3L40 67.5l7-20.9L42 33c-1.7-.1-3.3-.3-3.3-.3-1.7-.1-1.5-2.7.2-2.6 0 0 5.3.4 8.4.4 3.3 0 8.5-.4 8.5-.4 1.7-.1 1.9 2.4.2 2.6 0 0-1.7.2-3.7.3l11.5 34.3 3.3-10.4c1.6-4.5 2.4-7.8 2.4-10.5zM16.1 48c0 12.6 7.3 23.5 18 28.7L18.8 35c-1.7 4-2.7 8.4-2.7 13zm32.5 2.8L39 78.6c2.9.8 5.9 1.3 9 1.3 3.7 0 7.3-.6 10.6-1.8-.1-.1-.2-.3-.2-.4l-9.8-26.9zM76.2 36c0 3.2-.6 6.9-2.4 11.4L64 75.6c9.5-5.5 15.9-15.8 15.9-27.6 0-5.5-1.4-10.8-3.9-15.3.1 1 .2 2.1.2 3.3z" fill="none" />
            </svg>
            <p>Generating preview…</p>
        </div>
    `;
  targetDocument.write(markup);
  targetDocument.close();
}

/***/ }),

/***/ "./src/store/window.ts":
/*!*****************************!*\
  !*** ./src/store/window.ts ***!
  \*****************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   getAjaxUrl: function() { return /* binding */ getAjaxUrl; },
/* harmony export */   getFrontends: function() { return /* binding */ getFrontends; },
/* harmony export */   getPreviewUrl: function() { return /* binding */ getPreviewUrl; },
/* harmony export */   getReloadAjaxUrl: function() { return /* binding */ getReloadAjaxUrl; }
/* harmony export */ });
const getAjaxUrl = () => window.Headless.ajax;
const getReloadAjaxUrl = (frontend, path) => getAjaxUrl() + `?action=${window.Headless.actions.revalidate}&frontend=${frontend}&path=${path}`;
const getPreviewUrl = postId => {
  return window.Headless.preview_url.replace(window.Headless.post_id_placeholder, `${postId}`);
};
const getFrontends = () => window.Headless.frontends;

/***/ }),

/***/ "./src/styles.css":
/*!************************!*\
  !*** ./src/styles.css ***!
  \************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "react":
/*!************************!*\
  !*** external "React" ***!
  \************************/
/***/ (function(module) {

module.exports = window["React"];

/***/ }),

/***/ "@wordpress/components":
/*!************************************!*\
  !*** external ["wp","components"] ***!
  \************************************/
/***/ (function(module) {

module.exports = window["wp"]["components"];

/***/ }),

/***/ "@wordpress/data":
/*!******************************!*\
  !*** external ["wp","data"] ***!
  \******************************/
/***/ (function(module) {

module.exports = window["wp"]["data"];

/***/ }),

/***/ "@wordpress/edit-post":
/*!**********************************!*\
  !*** external ["wp","editPost"] ***!
  \**********************************/
/***/ (function(module) {

module.exports = window["wp"]["editPost"];

/***/ }),

/***/ "@wordpress/element":
/*!*********************************!*\
  !*** external ["wp","element"] ***!
  \*********************************/
/***/ (function(module) {

module.exports = window["wp"]["element"];

/***/ }),

/***/ "@wordpress/plugins":
/*!*********************************!*\
  !*** external ["wp","plugins"] ***!
  \*********************************/
/***/ (function(module) {

module.exports = window["wp"]["plugins"];

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
/******/ 	!function() {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = function(module) {
/******/ 			var getter = module && module.__esModule ?
/******/ 				function() { return module['default']; } :
/******/ 				function() { return module; };
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	!function() {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = function(exports, definition) {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	!function() {
/******/ 		__webpack_require__.o = function(obj, prop) { return Object.prototype.hasOwnProperty.call(obj, prop); }
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	!function() {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = function(exports) {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	}();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
!function() {
/*!**************************!*\
  !*** ./src/gutenberg.ts ***!
  \**************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_plugins__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/plugins */ "@wordpress/plugins");
/* harmony import */ var _wordpress_plugins__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_plugins__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _components_ReloadPanel__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./components/ReloadPanel */ "./src/components/ReloadPanel.tsx");
/* harmony import */ var _store_window__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./store/window */ "./src/store/window.ts");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _preview__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./preview */ "./src/preview.ts");
/* harmony import */ var _styles_css__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./styles.css */ "./src/styles.css");






(0,_wordpress_plugins__WEBPACK_IMPORTED_MODULE_0__.registerPlugin)('headless-plugin', {
  icon: () => null,
  render: _components_ReloadPanel__WEBPACK_IMPORTED_MODULE_1__["default"]
});
document.addEventListener("DOMContentLoaded", function () {
  const coreEditorSelect = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_3__.select)("core/editor");
  const getCurrentPostId = coreEditorSelect.getCurrentPostId;
  const isSavingPost = coreEditorSelect.isSavingPost;
  const isDraft = () => {
    const status = coreEditorSelect.getCurrentPost().status;
    return status == "draft" || status == "auto-draft";
  };
  const coreEditorDispatch = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_3__.dispatch)("core/editor");
  const autosave = coreEditorDispatch.autosave;
  const savePost = coreEditorDispatch.savePost;

  // --------------------------------------------------------
  // create replacement for preview link
  // --------------------------------------------------------
  const a = document.createElement("a");
  a.className = "components-button";
  a.addEventListener('click', e => {
    e.preventDefault();
    if (isSavingPost()) {
      return;
    }
    const ref = window.open('about:blank', a.target);
    (0,_preview__WEBPACK_IMPORTED_MODULE_4__.writeInterstitialMessage)(ref.document);
    const saveFn = isDraft() ? savePost : autosave;
    saveFn().then(() => {
      ref.location = a.href;
    });
  });
  (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_3__.subscribe)(() => {
    if (isSavingPost()) {
      a.classList.add("is-disabled");
    } else {
      a.classList.remove("is-disabled");
    }
  });

  // --------------------------------------------------------
  // hack it with interval updates
  // --------------------------------------------------------
  // workaround script until there's an official solution for https://github.com/WordPress/gutenberg/issues/13998
  setInterval(checkPreview, 300);
  function checkPreview() {
    const postId = getCurrentPostId();
    const previewUrl = (0,_store_window__WEBPACK_IMPORTED_MODULE_2__.getPreviewUrl)(postId);

    // replace all preview links
    const editorPreviewLink = document.querySelectorAll("[target^=wp-preview-]");
    if (editorPreviewLink && editorPreviewLink.length) {
      editorPreviewLink.forEach(link => {
        link.setAttribute("href", previewUrl);
      });
    }

    // replace notice link
    const notices = document.querySelectorAll(".components-snackbar-list .components-snackbar__content a.components-button");
    notices.forEach(notice => {
      if (notice.href.includes("?post=" + postId) // custom post types
      || notice.href.includes("?page_id=" + postId) // pages
      || notice.href.includes("?p=" + postId) // posts
      ) {
        notice.href = previewUrl;
        notice.target = "wp-preview-" + postId;
      }
    });

    // replace this special preview link
    const previewGroups = document.querySelectorAll(".edit-post-post-preview-dropdown .components-menu-group");
    let externalPreviewGroup = null;
    previewGroups.forEach(group => {
      if (group.querySelector(".edit-post-header-preview__grouping-external")) {
        externalPreviewGroup = group;
      }
    });
    if (!externalPreviewGroup) {
      return;
    }
    const id = "headless-preview-link";
    if (externalPreviewGroup.querySelector("#" + id)) {
      return;
    }

    // is hidden via styles.css
    const gutenbergLink = externalPreviewGroup.querySelector(".edit-post-header-preview__grouping-external a");
    const target = gutenbergLink.getAttribute("target");
    a.text = gutenbergLink.textContent;
    const icon = document.createElement("span");
    icon.className = "dashicons dashicons-external";
    a.append(icon);
    a.target = target;
    a.href = previewUrl;
    a.id = id;
    externalPreviewGroup.querySelector('[role="group"]').append(a);
  }
});
}();
/******/ })()
;
//# sourceMappingURL=gutenberg.js.map