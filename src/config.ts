import {getAxios} from "@palasthotel/wp-fetch";

let headlessGetParam = "headless";
let headlessGetValue = "true";

getAxios().interceptors.request.use((config) => {
    config.params = {...config.params, [headlessGetParam]: headlessGetValue};
    return config;
});

export const setGetParameter = (param: string, value: string) => {
    headlessGetParam = param;
    headlessGetValue = value;
}

export const setSecurityHeader = (key: string, value: string) => {
    getAxios().defaults.headers.common[key] = value;
}