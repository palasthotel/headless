import {AuthorId, PostId} from "@palasthotel/wp-fetch";

export type MenuSlug = string

export type GetMenuRequestArgs = {
    slug: MenuSlug
}

export type MenusResponse = {
    [slug: MenuSlug]: MenusResponse[]
}

export type MenuItemResponse = {
    ID: PostId
    post_author: AuthorId
    post_date: string,
    post_date_gmt: string
    post_content: string
    post_title: string
    post_excerpt: string
    post_status: string
    comment_status: string
    ping_status: string
    post_password: string
    post_name: string
    to_ping: string
    pinged: string
    post_modified: string
    post_modified_gmt: string
    post_content_filtered: string
    post_parent: number
    guid: string
    menu_order: number
    post_type: string
    post_mime_type: string
    comment_count: string
    filter: string
    db_id: number
    menu_item_parent: string
    object_id: number
    object: string
    type: string,
    type_label: string
    url: string
    title: string
    target: string
    attr_title: string
    description: string
    classes: string[]
    xfn: string
}
