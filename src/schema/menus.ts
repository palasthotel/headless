import {z} from "zod";

export const menuItemResponseSchema = z.object({
        ID: z.coerce.number(),
        post_author: z.coerce.number(),
        post_date: z.string(),
        post_date_gmt: z.string(),
        post_content: z.string(),
        post_title: z.string(),
        post_excerpt: z.string(),
        post_status: z.string(),
        comment_status: z.string(),
        ping_status: z.string(),
        post_password: z.string(),
        post_name: z.string(),
        to_ping: z.string(),
        pinged: z.string(),
        post_modified: z.string(),
        post_modified_gmt: z.string(),
        post_content_filtered: z.string(),
        post_parent: z.coerce.number(),
        guid: z.string(),
        menu_order: z.coerce.number(),
        post_type: z.string(),
        post_mime_type: z.string(),
        comment_count: z.string(),
        filter: z.string(),
        db_id: z.coerce.number(),
        menu_item_parent: z.string(),
        object_id: z.number(),
        object: z.string(),
        type: z.string(),
        type_label: z.string(),
        url: z.string(),
        title: z.string(),
        target: z.string(),
        attr_title: z.string(),
        description: z.string(),
        classes: z.array(z.string()),
        xfn: z.string()
})

export const menuResponseSchema = z.array(menuItemResponseSchema)

export const menusResponseSchema = z.record(menuResponseSchema)