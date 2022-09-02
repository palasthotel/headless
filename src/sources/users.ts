import {
    GetUsersRequestArgs,
    UserId,
    UserResponse,
    WordPressUrl,
    wpFetchUser as _wpFetchUser,
    wpFetchUsers as _wpFetchUsers,
} from "@palasthotel/wp-fetch";
import {init} from "../config";

export const wpFetchUser = <U extends UserResponse>(
    url: WordPressUrl,
    id: UserId
) => {
    init();
    return _wpFetchUser<U>(url, id);
}

export const wpFetchUsers = <U extends UserResponse>(
    url: WordPressUrl,
    args: GetUsersRequestArgs
) => {
    init();
    return _wpFetchUsers<U>(url, args);
}
