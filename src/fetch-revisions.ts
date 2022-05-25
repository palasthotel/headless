import {
    Authentication,
    GetRevisionRequestArgs,
    GetRevisionsRequestArgs,
    PostId,
    wpFetchRevision as _wpFetchRevision,
    wpFetchRevisions as _wpFetchRevisions,
} from "@palasthotel/wp-fetch";

import {Block, HeadlessRevisionResponse} from "./@types";

export const wpFetchRevisions = async <T extends HeadlessRevisionResponse<B>, B extends Block>(
    url: string,
    auth: Authentication,
    post: PostId,
    args: GetRevisionsRequestArgs
) => _wpFetchRevisions<T>(url, auth, post, args);

export const wpFetchRevision = async <T extends HeadlessRevisionResponse<B>, B extends Block>(
    url: string,
    auth: Authentication,
    args: GetRevisionRequestArgs
) =>  _wpFetchRevision<T>(url, auth, args);
