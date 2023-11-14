import {CompareArg, CompareParam} from "../@types";
import {getPostsRequest, GetPostsRequestArgs} from "@palasthotel/wp-rest";
import {mapQueryToParam} from "../mapping/compare.ts";
import {searchParamsAddHeadless} from "./headless.ts";

export type GetHeadlessPostsRequestArgs = GetPostsRequestArgs & {
    hl_meta_query?: {
        key: string
        value: string | number
        compare: CompareArg
    }[]

    hl_meta_keys?: string[]
    hl_meta_values?: (string | number)[]
    hl_meta_compares?: CompareParam[]

    hl_meta_exists?: string
    hl_meta_not_exists?: string
    hl_post_type?: string[]
}

export const getHeadlessPostsRequest = (args: GetHeadlessPostsRequestArgs) => {
    if (Array.isArray(args.hl_meta_query)) {
        args.hl_meta_keys = [
            ...(args.hl_meta_query.map(q => q.key)),
            ...(args.hl_meta_keys ?? [])
        ];
        args.hl_meta_values = [
            ...(args.hl_meta_query.map(q => q.value)),
            ...(args.hl_meta_values ?? [])
        ];
        args.hl_meta_compares = [
            ...(args.hl_meta_query.map(
                    q => mapQueryToParam(q.compare))
            ),
            ...(args.hl_meta_compares ?? [])
        ];
        delete args.hl_meta_query;
    }
    if(Array.isArray(args.hl_meta_keys)){
        if(
            args.hl_meta_keys.length != args.hl_meta_values?.length ||
            args.hl_meta_keys.length != args.hl_meta_compares?.length
        ) {
            console.warn("hl_meta_ fields need have the same length");
        }
    }

    const url = getPostsRequest(args);
    searchParamsAddHeadless(url.searchParams);
    return url;
}