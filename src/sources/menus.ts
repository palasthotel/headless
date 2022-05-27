import {buildHierarchy, wpFetchGet, WordPressUrl, onRequest} from "@palasthotel/wp-fetch";
import {GetMenuRequestArgs, MenuItemResponse, MenusResponse} from "../@types";
import {isArrayOfMenuItemResponse, isMenusResponse} from "../type-guard";
import {init} from "../config";

export function menuAsHierarchy(
    menu: MenuItemResponse[],
){
    return buildHierarchy<MenuItemResponse>(
        menu,
        (item) => item.ID,
        (item) => item.menu_item_parent === '0' ? false : parseInt(item.menu_item_parent),
    )
}

export const wpFetchMenus = async (
    wordpressUrl: WordPressUrl,
): Promise<MenusResponse|null> => {
    init();

    const response = await wpFetchGet<MenusResponse>({
        wordpressUrl,
        path: `/headless/v1/menus`,
    });

    if(response == null || !isMenusResponse(response.data)){
        return null;
    }

    return response.data;
}

export const wpFetchMenu = async (
    wordpressUrl: WordPressUrl,
    requestArgs: GetMenuRequestArgs
): Promise<MenuItemResponse[] | null> => {
    init();
    const {
        slug
    } = requestArgs;

    const response = await wpFetchGet<MenusResponse>({
        wordpressUrl: wordpressUrl,
        path: `/headless/v1/menus/${slug}`,
    });

    return response !== null && isArrayOfMenuItemResponse(response.data) ? response.data : null;
}