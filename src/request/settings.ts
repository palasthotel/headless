import {BaseRequestArgs, trimEndSlash} from "@palasthotel/wp-rest";
import {searchParamsAddHeadless} from "./headless.ts";

export const getSettingsRequest = (args: BaseRequestArgs)=> {
    const url = new URL(`${trimEndSlash(args.baseUrl)}/wp-json/headless/v1/settings`);
    searchParamsAddHeadless(url.searchParams);
    return url;
}