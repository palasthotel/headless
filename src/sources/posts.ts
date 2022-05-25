import {
    GetPostByIdRequestArgs, WordPressUrl,
    wpFetchPostById as _wpFetchPostById,
    wpFetchPosts as _wpFetchPosts
} from "@palasthotel/wp-fetch";

import {Block, HeadlessGetPostsRequestArgs, HeadlessPostResponse} from "../@types";
import {init} from "../config";

export const wpFetchPosts = async <T extends HeadlessPostResponse<B>, B extends Block>(
    url: WordPressUrl,
    args: HeadlessGetPostsRequestArgs = {}
) => {
    init();
    return _wpFetchPosts<T>(url, args);
}

export const wpFetchPostById = async <T extends HeadlessPostResponse<B>, B extends Block>(
    url: WordPressUrl,
    args: GetPostByIdRequestArgs
) => {
    init();
    return _wpFetchPostById<T>(url, args);
}
