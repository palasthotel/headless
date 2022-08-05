import {WordPressUrl, wpFetchGet} from "@palasthotel/wp-fetch";
import {init} from "../config";
import {HeadlessSettingsResponse} from "../@types/settings";

export const wpFetchHeadlessSettings = async (url: WordPressUrl) => {
    init();
    return wpFetchGet<HeadlessSettingsResponse>({
        wordpressUrl: url,
        path: "/headless/v1/settings",
    });
}