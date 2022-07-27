
declare global {
    interface Window {
        Headless: {
            ajax: string,
            actions: {
                reload: string
            }
        }
    }
}

export const getAjaxUrl = ()=> window.Headless.ajax;
export const getReloadAjaxUrl = (post: number) =>
    getAjaxUrl()+"?action="+window.Headless.actions.reload+"&post="+post;