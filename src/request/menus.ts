import {BaseRequestArgs, trimEndSlash} from "@palasthotel/wp-rest";
import {searchParamsAddHeadless} from "./headless.ts";
import {GetMenuRequestArgs} from "../@types";

export const getMenusRequest = (args: BaseRequestArgs) => {
    const url = new URL(`${trimEndSlash(args.baseUrl)}/wp-json/headless/v1/menus`)
    searchParamsAddHeadless(url.searchParams);
    return url;
}

export const getMenuRequest = (args: GetMenuRequestArgs) => {
    const url = new URL(`${trimEndSlash(args.baseUrl)}/wp-json/headless/v1/menus/${args.slug}`)
    searchParamsAddHeadless(url.searchParams);
    return url;
}