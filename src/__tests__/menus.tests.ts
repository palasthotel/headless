import {isMenuItemResponse, isMenusResponse} from "../type-guard";
import * as fs from "fs";
import {wpFetchMenus} from "../sources/menus";

describe('Type-Guard', () => {

    const menu = JSON.parse(
        fs.readFileSync(__dirname+"/data/menu.json", 'utf8')
    );

    it('Should be no menu item response', ()=>{
        expect(isMenuItemResponse({})).toBeFalsy();
    });

    it("Should be a menu item response", ()=>{
        expect(Array.isArray(menu)).toBeTruthy()
        menu.forEach( (item: any) => {
            expect(isMenuItemResponse(item)).toBeTruthy();
        });
    });

});


describe('Fetch menus', () => {

    const wordpressTestUrl = "http://local.wp.palasthotel.de:8080/";

    it('Should find a valid response', async ()=>{
        const menus = await wpFetchMenus(wordpressTestUrl);
        expect(isMenusResponse(menus)).toBeTruthy();
    });

    it('Should not find a response', async ()=>{
        const menus = await wpFetchMenus(wordpressTestUrl+"test");
        expect(menus).toBeNull();
    });

});