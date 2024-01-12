import {HeadlessParam} from "../@types";


export const searchParamsAddHeadless = (searchParams: URLSearchParams, param: HeadlessParam = {}) => {
    const {
        name = "headless",
        value = "true",
    } = param;
    searchParams.append(name, value);
}

export const withHeadlessParam = (url: URL, param: HeadlessParam = {}) => {
    searchParamsAddHeadless(url.searchParams, param);
    return url;
}