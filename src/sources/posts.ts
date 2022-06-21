import {
    GetPostByIdRequestArgs, WordPressUrl,
    wpFetchPostById as _wpFetchPostById,
    wpFetchPosts as _wpFetchPosts
} from "@palasthotel/wp-fetch";

import {Block, HeadlessGetPostsRequestArgs, HeadlessPostResponse} from "../@types";
import {init} from "../config";
import {mapQueryToParam} from "../mapping/compare";

export const wpFetchPosts = async <T extends HeadlessPostResponse<B>, B extends Block>(
    url: WordPressUrl,
    args: HeadlessGetPostsRequestArgs = {}
) => {
    init();
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
            throw new Error("hl_meta_ fields need have the same length");
        }
    }
    return _wpFetchPosts<T>(url, args);
}

export const wpFetchPostById = async <T extends HeadlessPostResponse<B>, B extends Block>(
    url: WordPressUrl,
    args: GetPostByIdRequestArgs
) => {
    init();
    return _wpFetchPostById<T>(url, args);
}
