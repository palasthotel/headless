import {z} from "zod";

const settingsForPostsSchema = z.object({
    front_page: z.literal("posts"),
    page_on_front: z.literal(0),
    home_url: z.string()
});

const settingsForPageSchema = z.object({
    front_page: z.literal("page"),
    page_on_front: z.coerce.number().gt(0),
    home_url: z.string()
});

export const settingsResponseSchema = z.discriminatedUnion("front_page",[
    settingsForPageSchema,
    settingsForPostsSchema,
])
