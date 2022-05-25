import {useRequest, setHeader} from "@palasthotel/wp-fetch";

let headlessGetParam = "headless";
let headlessGetValue = "true";

let initialized = false;

export const init = ()=> {
    if(!initialized){
        initialized = true;
        useRequest(config => {
            console.debug(headlessGetParam, headlessGetValue);
            config.params = {...config.params, [headlessGetParam]: headlessGetValue};
            return config;
        });
    }
}

export const setGetParameter = (param: string, value: string) => {
    headlessGetParam = param;
    headlessGetValue = value;
}

export const setSecurityHeader = (key: string, value: string) => {
    setHeader(key, value);
}