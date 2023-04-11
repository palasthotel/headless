import {getPreviewUrls} from "./store/admin-window";

window.addEventListener("DOMContentLoaded", async ()=>{
    const results = await Promise.all(getPreviewUrls().map(url => fetch(
        url, {credentials: "include"}).then(res => res.json())));

});

