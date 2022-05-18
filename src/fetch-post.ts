import {GetPostByIdRequestArgs, wpFetchPostById as _wpFetchPostById} from "@palasthotel/wp-fetch";
import {Block, HeadlessPostResponse} from "./@types";

export const wpFetchPostById = async <T extends HeadlessPostResponse<B>, B extends Block>(
    url: string,
    args: GetPostByIdRequestArgs
) => {
    return await _wpFetchPostById<T>(url, args);
}
