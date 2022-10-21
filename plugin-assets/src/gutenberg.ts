import {registerPlugin} from "@wordpress/plugins";
import ReloadPanel from "./components/ReloadPanel";
import {getPreviewUrl} from "./store/window";

registerPlugin('headless-plugin', {
    icon: () => null,
    render: ReloadPanel,
});

// workaround script until there's an official solution for https://github.com/WordPress/gutenberg/issues/13998
const previewUrl = getPreviewUrl();
if(previewUrl != null){
    document.addEventListener("DOMContentLoaded", function () {
        setInterval(checkPreview, 1000);
        function checkPreview() {
            const editorPreviewLink = document.querySelectorAll("[target^=wp-preview-]");
            if(editorPreviewLink && editorPreviewLink.length){
                editorPreviewLink.forEach(link => {
                    link.setAttribute("href", previewUrl);
                });
            }
        }
    });
}
