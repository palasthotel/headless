declare global {
    interface Window {
        Headless: {
            ajax: string
            nonce: string
            frontends: string[]
            actions: {
                revalidate: string
            }
            post_id_placeholder: string
            preview_url: string
        }
    }
}

export const getAjaxUrl = ()=> window.Headless.ajax;
export const getNonce = ()=> window.Headless.nonce;
export const getReloadAjaxUrl = (frontend: number, path: string) =>
    getAjaxUrl()+`?action=${window.Headless.actions.revalidate}&frontend=${frontend}&path=${path}&_ajax_nonce=${getNonce()}`;

export const getPreviewUrl = (postId: number) => {
    return window.Headless.preview_url.replace(window.Headless.post_id_placeholder, `${postId}`)
};

export const getFrontends = () => window.Headless.frontends;
