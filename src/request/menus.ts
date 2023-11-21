import {BaseRequestArgs, trimEndSlash} from "@palasthotel/wp-rest";
import {asHeadlessRequest} from "./headless.ts";
import {GetMenuRequestArgs, HeadlessParam} from "../@types";

export const getMenusRequest = (args: BaseRequestArgs, param: HeadlessParam = {}) => {
    const url = new URL(`${trimEndSlash(args.baseUrl)}/wp-json/headless/v1/menus`)
    return asHeadlessRequest(url, param);
}

export const getMenuRequest = (args: GetMenuRequestArgs, param: HeadlessParam = {}) => {
    const url = new URL(`${trimEndSlash(args.baseUrl)}/wp-json/headless/v1/menus/${args.slug}`)
    return asHeadlessRequest(url, param);
}