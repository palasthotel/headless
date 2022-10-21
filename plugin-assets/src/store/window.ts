
declare global {
    interface Window {
        Headless: {
            ajax: string
            actions: {
                reload: string
            }
            preview_url: string
        }
    }
}

export const getAjaxUrl = ()=> window.Headless.ajax;
export const getReloadAjaxUrl = (post: number) =>
    getAjaxUrl()+"?action="+window.Headless.actions.reload+"&post="+post;

export const getPreviewUrl = () => window.Headless.preview_url;
