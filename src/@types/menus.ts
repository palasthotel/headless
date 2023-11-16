import {z} from "zod";
import {BaseRequestArgs} from "@palasthotel/wp-rest"
import {menuResponseSchema, menusResponseSchema} from "../schema";

export type MenuSlug = string

export type GetMenuRequestArgs = BaseRequestArgs & {
    slug: MenuSlug
}

export type MenusResponse = z.infer<typeof menusResponseSchema>

export type MenuResponse = z.infer<typeof menuResponseSchema>
