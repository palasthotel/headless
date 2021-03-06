import {wpFetchPosts} from "../sources/posts";
import {setOnHeadlessRequest} from "../config";
import {HeadlessGetPostsRequestArgs} from "../@types";


describe('wpFetchPosts', function () {

    afterEach(() => {
        setOnHeadlessRequest(null);
    });

    const url = "https://digitale-pracht.de";
    describe("when API call is successful", () => {

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

    describe("API call with meta_query", () => {

        it("Should should meta_query parameters", async () => {

            let requestUrl = ""
            let params = {};
            setOnHeadlessRequest(config => {
                requestUrl = config.url ?? "";
                params = config.params;
            });
            try{
                await wpFetchPosts(url,{
                    hl_meta_query: [
                        {
                            key: "my_key",
                            value: "my_value",
                            compare: "=",
                        }
                    ]
                });
            } catch (e) {
                // ignore
            }

            expect(requestUrl).toBe(`${url}/wp-json/wp/v2/posts`);
            expect(Object.keys(params).indexOf("headless")).toBeGreaterThan(-1);
            expect(params).toHaveProperty("hl_meta_keys", ["my_key"]);
            expect(params).toHaveProperty("hl_meta_values", ["my_value"]);
            expect(params).toHaveProperty("hl_meta_compares", ["eq"]);
        });

        it("Should should throw error", async () => {
            await expect(wpFetchPosts(url,{
                hl_meta_keys: ["meta_key"],
            })).rejects.toThrow("hl_meta_ fields need have the same length");
        });

    });

    it("Should fetch with tax query", async () => {
        const response = await  wpFetchPosts(url,{
            categories: { operator: 'AND', terms: 1 },
            page: 1,
            per_page: 20,
            hl_meta_exists: '_thumbnail_id',
            tags: { operator: 'AND', terms: 55 },
            type: 'posts'
        });

        expect(response.posts.length).toBeGreaterThan(0);
    });

});