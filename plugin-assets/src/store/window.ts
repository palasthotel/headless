import {select} from "@wordpress/data";

declare global {
    interface Window {
        Headless: {
            ajax: string
            actions: {
                reload: string
            }
            post_id_placeholder: string
            preview_url: string
        }
    }
}

export const getAjaxUrl = ()=> window.Headless.ajax;
export const getReloadAjaxUrl = (post: number) =>
    getAjaxUrl()+"?action="+window.Headless.actions.reload+"&post="+post;

export const getPreviewUrl = () => {
    const postId = select("core/editor").getCurrentPostId();
    return window.Headless.preview_url.replace(window.Headless.post_id_placeholder, postId)
};
