import {setGetParameter, setOnHeadlessRequest, setSecurityHeader} from "../config";
import {wpFetchPosts} from "../sources/posts";

describe("Change config", () => {

    afterEach(()=>{
        setOnHeadlessRequest(null);
    })

    it("Should should have default params", async () => {
        let params = undefined;
        setOnHeadlessRequest((config) => {
            params = {...config.params};
        });
        try{
            await wpFetchPosts("some-url");
        } catch (e) {
            // ignore
        }
        expect(params?.["headless"]).toBe("true");
    });

    it("Should change the headless param", async () => {
        setGetParameter("my-param", "cool");
        let params = undefined;
        setOnHeadlessRequest((config) => {
            params = config.params;
        });
        try{
            await wpFetchPosts("some-url");
        } catch (e) {
            // ignore
        }
        expect(params?.["my-param"]).toBe("cool");
    });

    it("Should change security header", async () => {
        setSecurityHeader("my-security-header", "yes");
        let headers = undefined;
        setOnHeadlessRequest((config) => {
            headers = config.headers?.common;
        });
        try{
            await wpFetchPosts("some-url");
        } catch (e) {
            // ignore
        }
        expect(headers).not.toBeUndefined();
        expect(headers?.["my-security-header"]).toBe("yes");
    });

})