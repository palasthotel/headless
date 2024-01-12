import {BaseRequestArgs, trimEndSlash} from "@palasthotel/wp-rest";
import {withHeadlessParam} from "./headless.ts";
import {HeadlessParam} from "../@types";

export const getSettingsRequest = (args: BaseRequestArgs, param: HeadlessParam = {})=> {
    return withHeadlessParam(
        new URL(`${trimEndSlash(args.baseUrl)}/wp-json/headless/v1/settings`),
        param
    );
}