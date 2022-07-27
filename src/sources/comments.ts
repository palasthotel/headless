
import {
    wpFetchComments as _wpFetchComments,
    wpFetchComment as _wpFetchComment,
    WordPressUrl,
    GetCommentsRequestArgs, GetCommentRequestArgs,
} from "@palasthotel/wp-fetch";
import {HeadlessCommentResponse} from "../@types/comment";

export const wpFetchComments = <C extends HeadlessCommentResponse<C>>(
    url: WordPressUrl,
    args: GetCommentsRequestArgs,
) => {
    return _wpFetchComments<C>(url, args);
}

export const wpFetchComment = <C extends HeadlessCommentResponse<C>>(
    url: WordPressUrl,
    args: GetCommentRequestArgs,
) => {
    return _wpFetchComment<C>(url, args)
}