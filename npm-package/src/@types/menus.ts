import {z} from "zod";
import {BaseRequestArgs} from "@palasthotel/wp-rest"
import {menuItemResponseSchema, menuResponseSchema, menusResponseSchema} from "../schema";

export type MenuSlug = string

export type HeadlessParam = {
    name?: string
    value?: string
}

export type GetMenuRequestArgs = BaseRequestArgs & {
    slug: MenuSlug
}

export type MenusResponse = z.infer<typeof menusResponseSchema>

export type MenuItemResponse = z.infer<typeof menuItemResponseSchema>
export type MenuResponse = z.infer<typeof menuResponseSchema>
