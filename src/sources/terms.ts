import {
    GetTermRequestArgs, GetTermsRequestArgs, TermResponse, TermsResponse,
    WordPressUrl,
    wpFetchTerm as _wpFetchTerm,
    wpFetchTerms as _wpFetchTerms,
} from "@palasthotel/wp-fetch";
import {init} from "../config";

export const wpFetchTerm = <T extends TermResponse>(
    url: WordPressUrl,
    args: GetTermRequestArgs
) => {
    init();
    return _wpFetchTerm<T>(url, args);
}

export const wpFetchTerms = <T extends TermResponse>(
    url: WordPressUrl,
    args: GetTermsRequestArgs
) => {
    init();
    return _wpFetchTerms<T>(url, args);
}
