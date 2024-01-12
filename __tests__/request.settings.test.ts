import {getSettingsRequest} from "../src";

const baseUrl = "https://palasthotel.de/";

test("Should change headless param name", () => {
    const url = getSettingsRequest({
        baseUrl,
    }, {name: "hl"});

    const decodedUrl = decodeURIComponent(url.toString());
    expect(decodedUrl).toBe(`${baseUrl}wp-json/headless/v1/settings?hl=true`);
})

test("Should change headless param value", () => {
    const url = getSettingsRequest({
        baseUrl,
    }, {value: "maybe"});

    const decodedUrl = decodeURIComponent(url.toString());
    expect(decodedUrl).toBe(`${baseUrl}wp-json/headless/v1/settings?headless=maybe`);
})
