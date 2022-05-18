import {wpFetchPostById as _wpFetchPostById} from "@palasthotel/wp-fetch";
import {HeadlessPostResponse} from "./@types/post";
import axios from "axios";

let headlessGetParam = "headless";
let headlessGetValue = "true";

axios.interceptors.request.use((config) => {
    config.params = {...config.params, [headlessGetParam]: headlessGetValue};
    return config;
});

export const setGetParameter = (param: string, value: string) => {
    headlessGetParam = param;
    headlessGetValue = value;
}

export const setSecurityHeader = (key: string, value: string) => {
    axios.defaults.headers.common[key] = value;
}

export const wpFetchPostById = async (url: string, id:number, type: string = "posts") => {
    return await _wpFetchPostById<HeadlessPostResponse>(url, {
        id,
        type,
    });

}