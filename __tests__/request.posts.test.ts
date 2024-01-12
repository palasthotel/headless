import {getPostsWithBlocksRequest} from "../src";

const baseUrl = "https://palasthotel.de/";

test("Should add hl_post_type correct", () => {
    const url = getPostsWithBlocksRequest({
        baseUrl,
        hl_post_type: ["my-post-type"],
    });

    const decodedUrl = decodeURIComponent(url.toString());
    expect(decodedUrl).toBe(`${baseUrl}wp-json/wp/v2/posts?hl_post_type[]=my-post-type&headless=true`)
})

test("Should add hl_meta(_not)_exists correct", () => {
    const url = getPostsWithBlocksRequest({
        baseUrl,
        hl_meta_not_exists: "meta-key",
        hl_meta_exists: "existing"
    });

    const decodedUrl = decodeURIComponent(url.toString());
    expect(decodedUrl).toBe(
        `${baseUrl}wp-json/wp/v2/posts?hl_meta_not_exists=meta-key&hl_meta_exists=existing&headless=true`
    )
})

test("Should add hl_meta_(keys,values,compares) correct", () => {
    const url = getPostsWithBlocksRequest({
        baseUrl,
        hl_meta_not_exists: "meta-key",
    });

    const decodedUrl = decodeURIComponent(url.toString());
    expect(decodedUrl).toBe(
        `${baseUrl}wp-json/wp/v2/posts?hl_meta_not_exists=meta-key&headless=true`
    )
})

test("Should default params working correct", () => {
    const url = getPostsWithBlocksRequest({
        baseUrl,
        categories: "123",
    });

    const decodedUrl = decodeURIComponent(url.toString());
    expect(decodedUrl).toBe(
        `${baseUrl}wp-json/wp/v2/posts?categories=123&headless=true`
    )
})

test("Should default add headless variant correct", () => {
    const url = getPostsWithBlocksRequest({
        baseUrl,
        headless_variant: "teasers",
    });

    const decodedUrl = decodeURIComponent(url.toString());
    expect(decodedUrl).toBe(
        `${baseUrl}wp-json/wp/v2/posts?headless_variant=teasers&headless=true`
    )
})