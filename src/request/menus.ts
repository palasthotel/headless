import {BaseRequestArgs, trimEndSlash} from "@palasthotel/wp-rest";
import {withHeadlessParam} from "./headless.ts";
import {GetMenuRequestArgs, HeadlessParam} from "../@types";

export const getMenusRequest = (args: BaseRequestArgs, param: HeadlessParam = {}) => {
    const url = new URL(`${trimEndSlash(args.baseUrl)}/wp-json/headless/v1/menus`)
    return withHeadlessParam(url, param);
}

export const getMenuRequest = (args: GetMenuRequestArgs, param: HeadlessParam = {}) => {
    const url = new URL(`${trimEndSlash(args.baseUrl)}/wp-json/headless/v1/menus/${args.slug}`)
    return withHeadlessParam(url, param);
}