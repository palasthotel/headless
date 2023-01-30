import {registerPlugin} from "@wordpress/plugins";
import ReloadPanel from "./components/ReloadPanel";
import {getPreviewUrl} from "./store/window";
import "./styles.css"
import {select, dispatch, subscribe} from "@wordpress/data";

registerPlugin('headless-plugin', {
    icon: () => null,
    render: ReloadPanel,
});

document.addEventListener("DOMContentLoaded", function () {

    const coreEditorSelect = select("core/editor");
    const getCurrentPostId = coreEditorSelect.getCurrentPostId;
    const isSavingPost = coreEditorSelect.isSavingPost;

    const coreEditorDispatch = dispatch("core/editor");
    const autosave = coreEditorDispatch.autosave;

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
        autosave().then(()=>{
            console.debug("open ", a.href, a.target)
            window.open(a.href, a.target);
        });

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
    const editor = document.querySelector("#editor");

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

        // replace this special preview link
        const externalPreviewGroup = editor.querySelector(".edit-post-post-preview-dropdown .components-menu-group:has(.edit-post-header-preview__grouping-external)");
        if(!externalPreviewGroup){
            return;
        }

        const id = "headless-preview-link";
        if(externalPreviewGroup.querySelector("#"+id)){
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
        console.debug(target);
        a.href = previewUrl;
        a.id = id;
        externalPreviewGroup.querySelector('[role="group"]').append(a);
    }
});
