import {setHeader, onRequest, ejectRequest, WPRequestConfig} from "@palasthotel/wp-fetch";

let headlessGetParam = "headless";
let headlessGetValue = "true";

let initialized = false;
let onRequestId = 0;
type OnHeadlessRequest = (config: WPRequestConfig) => void
let onHeadlessRequest: null | OnHeadlessRequest = null;

export const init = ()=> {
    if(!initialized){
        initialized = true;
        onRequestId = onRequest(config => {
            if(config.params == undefined){
                config.params = [];
            }
            config.params = {...config?.params ?? {}, [headlessGetParam]: headlessGetValue}
            onHeadlessRequest?.(config);
            return config;
        });
    }
}

export const reset = ()=>{
    ejectRequest(onRequestId);
    initialized = false;
    init();
}

export const setOnHeadlessRequest = ( fn: null | OnHeadlessRequest ) => {
    onHeadlessRequest = fn;
}

export const setGetParameter = (param: string, value: string) => {
    headlessGetParam = param;
    headlessGetValue = value;
}

export const setSecurityHeader = (key: string, value: string) => {
    setHeader(key, value);
}