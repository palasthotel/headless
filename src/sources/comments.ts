import {
    GetCommentRequestArgs,
    GetCommentsRequestArgs,
    WordPressUrl,
    wpFetchComment as _wpFetchComment,
    wpFetchComments as _wpFetchComments,
} from "@palasthotel/wp-fetch";
import {HeadlessCommentResponse} from "../@types";
import {init} from "../config";

export const wpFetchComments = <C extends HeadlessCommentResponse>(
    url: WordPressUrl,
    args: GetCommentsRequestArgs,
) => {
    init();
    return _wpFetchComments<C>(url, args);
}

export const wpFetchComment = <C extends HeadlessCommentResponse>(
    url: WordPressUrl,
    args: GetCommentRequestArgs,
) => {
    init();
    return _wpFetchComment<C>(url, args)
}