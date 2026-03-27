declare global {
    interface Window {
        HeadlessAdmin: {
			revalidate_is_active: boolean
			preview_is_active: boolean
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
export const isRevalidateActive =() => window.HeadlessAdmin.revalidate_is_active;
export const isPreviewActive = () => window.HeadlessAdmin.preview_is_active;
