declare global {
    interface Window {
        HeadlessAdmin: {
            preview_path: string
            frontends: string[]
        }
    }
}

export const getPreviewPath = ()=> window.HeadlessAdmin.preview_path;
export const getPreviewUrls = () => {
    return window.HeadlessAdmin.frontends.map(frontend => {
        return frontend+getPreviewPath();
    })
}
