import {
    GetRevisionRequestArgs,
    GetRevisionsRequestArgs,
    PostId,
    WordPressAuthenticatedUrl,
    wpFetchRevision as _wpFetchRevision,
    wpFetchRevisions as _wpFetchRevisions,
} from "@palasthotel/wp-fetch";

import {Block, HeadlessRevisionResponse} from "../@types";
import {init} from "../config";

export const wpFetchRevisions = async <T extends HeadlessRevisionResponse<B>, B extends Block>(
    url: WordPressAuthenticatedUrl,
    post: PostId,
    args: GetRevisionsRequestArgs
) => {
    init();
    return _wpFetchRevisions<T>(url, post, args);
}

export const wpFetchRevision = async <T extends HeadlessRevisionResponse<B>, B extends Block>(
    url: WordPressAuthenticatedUrl,
    args: GetRevisionRequestArgs
) => {
    init();
    return _wpFetchRevision<T>(url, args);
}
