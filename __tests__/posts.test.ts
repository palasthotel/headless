import {getHeadlessPostsRequest} from "../src/request/posts";

const baseUrl = "https://palasthotel.de/";

test("Should add hl_post_type correct", () => {
    const url = getHeadlessPostsRequest({
        baseUrl,
        hl_post_type: ["my-post-type"],
    });

    const decodedUrl = decodeURIComponent(url.toString());
    expect(decodedUrl).toBe(`${baseUrl}wp-json/wp/v2/posts?headless=true&hl_post_type[]=my-post-type`)
})

test("Should add hl_meta(_not)_exists correct", () => {
    const url = getHeadlessPostsRequest({
        baseUrl,
        hl_meta_not_exists: "meta-key",
        hl_meta_exists: "existing"
    });

    const decodedUrl = decodeURIComponent(url.toString());
    expect(decodedUrl).toBe(
        `${baseUrl}wp-json/wp/v2/posts?headless=true&hl_meta_not_exists=meta-key&hl_meta_exists=existing`
    )
})

test("Should add hl_meta_(keys,values,compares) correct", () => {
    const url = getHeadlessPostsRequest({
        baseUrl,
        hl_meta_not_exists: "meta-key",
    });

    const decodedUrl = decodeURIComponent(url.toString());
    expect(decodedUrl).toBe(
        `${baseUrl}wp-json/wp/v2/posts?headless=true&hl_meta_not_exists=meta-key`
    )
})