import {settingsResponseSchema} from "../src";
import {testPageSettings, testPostsSettings} from "../__test_data__/settings";


describe('Settings schema validation', () => {
	test("Should parse posts settings", ()=>{
		const posts = settingsResponseSchema.parse(testPostsSettings);
		expect(posts.front_page).toBe("posts");
		expect(posts.page_on_front).toBe(0);
	});

	test("Should parse page settings", ()=>{
		const page = settingsResponseSchema.parse(testPageSettings);
		expect(page.front_page).toBe("page");
		expect(page.page_on_front).toBeGreaterThan(0);
	});
});
