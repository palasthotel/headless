import {MenuItemResponse, MenuSlug, MenusResponse} from "../@types";
import {isArrayOfStrings} from "@palasthotel/wp-fetch";

export const isArrayOfMenuSlugs = (data: unknown): data is MenuSlug[] => {
    return isArrayOfStrings(data);
}

export const isMenusResponse = (data: unknown): data is MenusResponse => {
    return data != null && typeof data == "object" &&
        Object.values(data).filter(isArrayOfMenuItemResponse).length == Object.keys(data).length &&
        isArrayOfMenuSlugs(Object.keys(data));

}

export const isMenuItemResponse = (data: any): data is MenuItemResponse => {
    return typeof data === "object" &&
        typeof data.ID === "number" &&
        typeof data.title === "string" &&
        typeof data.url === "string" &&
        typeof data.menu_item_parent === "string" &&
        typeof data.menu_order === "number";
}

export const isArrayOfMenuItemResponse = (data: any): data is MenuItemResponse[] => {
    return Array.isArray(data) &&
        data.filter(isMenuItemResponse).length === data.length;
}