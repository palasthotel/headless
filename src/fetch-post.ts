import {
    GetPostByIdRequestArgs,
    wpFetchPostById as _wpFetchPostById,
    wpFetchPosts as _wpFetchPosts
} from "@palasthotel/wp-fetch";

import {Block, HeadlessGetPostsRequestArgs, HeadlessPostResponse} from "./@types";

export const wpFetchPosts = async <T extends HeadlessPostResponse<B>, B extends Block>(
    url: string,
    args: HeadlessGetPostsRequestArgs
) => _wpFetchPosts<T>(url, args);

export const wpFetchPostById = async <T extends HeadlessPostResponse<B>, B extends Block>(
    url: string,
    args: GetPostByIdRequestArgs
) =>  _wpFetchPostById<T>(url, args);
