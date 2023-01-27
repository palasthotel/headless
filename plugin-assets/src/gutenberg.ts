import {registerPlugin} from "@wordpress/plugins";
import ReloadPanel from "./components/ReloadPanel";
import {getPreviewUrl} from "./store/window";
import "./styles.css"

registerPlugin('headless-plugin', {
    icon: () => null,
    render: ReloadPanel,
});

// workaround script until there's an official solution for https://github.com/WordPress/gutenberg/issues/13998
document.addEventListener("DOMContentLoaded", function () {
    setInterval(checkPreview, 300);
    const editor = document.querySelector("#editor")

    function checkPreview() {
        const previewUrl = getPreviewUrl();

        const externalPreviewGroup = editor.querySelector(".edit-post-post-preview-dropdown .components-menu-group:has(.edit-post-header-preview__grouping-external)");
        if(!externalPreviewGroup){
            return;
        }

        const id = "headless-preview-link";
        if(externalPreviewGroup.querySelector("#"+id)){
            return;
        }

        // TODO: force safe first!

        // is hidden via styles.css
        const gutenbergLink = externalPreviewGroup.querySelector(".edit-post-header-preview__grouping-external");
        const target = gutenbergLink.getAttribute("target");
        const a = document.createElement("a");
        a.text = gutenbergLink.textContent;
        a.target = target;
        a.id = id;
        a.className = "components-button"
        a.href = previewUrl;
        externalPreviewGroup.querySelector('[role="group"]').append(a);
    }
});
