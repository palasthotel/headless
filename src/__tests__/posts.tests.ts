import {wpFetchPosts} from "../sources/posts";
import {HeadlessRequestConfig, useRequest} from "@palasthotel/wp-fetch";

type AxiosTestInterceptor = (config: HeadlessRequestConfig) => void

let interceptor: AxiosTestInterceptor|null = null
const setInterceptor = (fn: AxiosTestInterceptor) => {
    interceptor = fn;
}
const resetInterceptor = () => {
    interceptor = null;
}

useRequest((config) => {
    interceptor?.(config);
    return config;
});

describe('wpFetchPosts', function () {

    beforeEach(() => {
        resetInterceptor();
    });

    describe("when API call is successful", () => {

        const url = "https://digitale-pracht.de";
        it("Should should have headless params", async () => {

            let requestUrl = ""
            let params = {};
            setInterceptor((config) => {
                requestUrl = config.url ?? "";
                params = config.params;
            });
            try{
                await wpFetchPosts(url);
            } catch (e) {
                // ignore
            }

            expect(requestUrl).toBe(`${url}/wp-json/wp/v2/posts`);
            expect(Object.keys(params).indexOf("headless")).toBeGreaterThan(-1);
        });
    });

});