import {setGetParameter, setSecurityHeader} from "../config";
import {wpFetchPosts} from "../sources/posts";
import {ejectRequest, useRequest} from "@palasthotel/wp-fetch";

describe("Change config", () => {

    let id = 0;
    afterEach(()=>{
        ejectRequest(id);
    })

    it("Should should have default params", async () => {
        let params = undefined;
        id = useRequest((config) => {
            params = {...config.params};
            return config;
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
        id = useRequest((config) => {
            params = {...config.params};
            return config;
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
        id = useRequest((config) => {
            headers = config.headers?.common;
            return config;
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