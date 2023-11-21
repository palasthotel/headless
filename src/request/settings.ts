import {BaseRequestArgs, trimEndSlash} from "@palasthotel/wp-rest";
import {asHeadlessRequest} from "./headless.ts";
import {HeadlessParam} from "../@types";

export const getSettingsRequest = (args: BaseRequestArgs, param: HeadlessParam = {})=> {
    return asHeadlessRequest(
        new URL(`${trimEndSlash(args.baseUrl)}/wp-json/headless/v1/settings`),
        param
    );
}