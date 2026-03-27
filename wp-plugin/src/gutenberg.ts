import {registerPlugin} from "@wordpress/plugins";
import ReloadPanel from "./components/ReloadPanel";
import {getPreviewUrl} from "./store/window";
import {dispatch, select, subscribe} from "@wordpress/data";
import {writeInterstitialMessage} from "./preview";

import "./styles.css"
import {isPreviewActive, isRevalidateActive} from "./store/admin-window";

if(isRevalidateActive()){
	registerPlugin('headless-plugin', {
		icon: () => null,
		render: ReloadPanel,
	});
}

document.addEventListener("DOMContentLoaded", function () {

	if(!isPreviewActive()){
		return;
	}

    const coreEditorSelect = select("core/editor");
    const getCurrentPostId = coreEditorSelect.getCurrentPostId;
    const isSavingPost = coreEditorSelect.isSavingPost;
    const isDraft = ()=> {
        const status = coreEditorSelect.getCurrentPost().status;
        return status == "draft" || status == "auto-draft";
    }

    const coreEditorDispatch = dispatch("core/editor") as {
        autosave: () => Promise<void>
        savePost: () => Promise<void>
    };
    const autosave = coreEditorDispatch.autosave;
    const savePost = coreEditorDispatch.savePost;

    // --------------------------------------------------------
    // create replacement for preview link
    // --------------------------------------------------------
    const a = document.createElement("a");
    a.className = "components-button";

    a.addEventListener('click', (e)=>{
        e.preventDefault();
        if(isSavingPost()){
            return;
        }
        const ref = window.open('about:blank', a.target);
		if(ref) {
			writeInterstitialMessage(ref.document);

			const saveFn = (isDraft()) ? savePost : autosave;
			saveFn().then(() => {
				ref.location = a.href;
			});
		}

    });

    subscribe(()=>{
        if(isSavingPost()){
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
        const previewUrl = getPreviewUrl(postId);

        // replace all preview links
        const editorPreviewLink = document.querySelectorAll("[target^=wp-preview-]");
        if(editorPreviewLink && editorPreviewLink.length){
            editorPreviewLink.forEach(link => {
                link.setAttribute("href", previewUrl);
            });
        }

        // replace notice link
        const notices =  document.querySelectorAll<HTMLAnchorElement>(".components-snackbar-list .components-snackbar__content a.components-button");
        notices.forEach((notice) => {
            if(
                notice.href.includes("?post="+postId) // custom post types
                ||
                notice.href.includes("?page_id="+postId) // pages
                ||
                notice.href.includes("?p="+postId) // posts
            ){
                notice.href = previewUrl;
                notice.target = "wp-preview-"+postId;
            }
        });

		// replace this special preview link
		const externalPreviewGroup = Array.from(
			document.querySelectorAll<HTMLElement>(".components-menu-group")
		).find(group => group.querySelector(".editor-preview-dropdown__button-external"));


		if(!externalPreviewGroup){
            return;
        }

        const id = "headless-preview-link";
        if(externalPreviewGroup.querySelector("#"+id)){
            return;
        }

        // is hidden via styles.css
        const gutenbergLink = externalPreviewGroup.querySelector<HTMLAnchorElement>(".editor-preview-dropdown__button-external");
		if(!gutenbergLink){return}

        const svg = gutenbergLink.querySelector("svg");
        const target = gutenbergLink.getAttribute("target") ?? "";
        a.text = gutenbergLink.textContent ?? "";
		if (svg) a.append(svg);
        a.target = target;
        a.href = previewUrl;
        a.id = id;
        gutenbergLink.style.display = "none";
        externalPreviewGroup.querySelector('[role="group"]')?.append(a);
    }
});
