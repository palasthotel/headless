import {buildHierarchy, Hierarchy} from "@palasthotel/wp-rest";
import {MenuResponse} from "../@types";

export function menuAsHierarchy(
    menu: MenuResponse[],
): Hierarchy<MenuResponse>[] {
    return buildHierarchy<MenuResponse>(
        menu,
        (item) => item.ID,
        (item) => item.menu_item_parent === '0' ? false : parseInt(item.menu_item_parent),
    )
}
