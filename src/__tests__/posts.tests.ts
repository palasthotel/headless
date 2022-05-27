import {wpFetchPosts} from "../sources/posts";
import {reset, setOnHeadlessRequest} from "../config";


describe('wpFetchPosts', function () {

    afterEach(() => {
        setOnHeadlessRequest(null);
    });

    describe("when API call is successful", () => {

        const url = "https://digitale-pracht.de";
        it("Should should have headless params", async () => {

            let requestUrl = ""
            let params = {};
            setOnHeadlessRequest(config => {
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