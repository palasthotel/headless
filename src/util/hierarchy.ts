import {buildHierarchy, Hierarchy} from "@palasthotel/wp-rest";
import {MenuItemResponse, MenuResponse} from "../@types";

export function menuAsHierarchy(
    menu: MenuResponse,
): Hierarchy<MenuItemResponse>[] {
    return buildHierarchy<MenuItemResponse>(
        menu,
        (item) => item.ID,
        (item) => item.menu_item_parent === '0' ? false : parseInt(item.menu_item_parent),
    )
}
