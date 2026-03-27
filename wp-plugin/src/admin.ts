import {getPreviewUrls, isPreviewActive} from "./store/admin-window";

window.addEventListener("DOMContentLoaded", async ()=>{
	if(!isPreviewActive()) return;

    await Promise.all(getPreviewUrls().map(url => fetch(
        url, {credentials: "include"}).then(res => res.json())));

});

