import {z} from "zod";

export const settingsResponseSchema = z.object({
    front_page: z.string(),
    page_on_front: z.coerce.number(),
    home_url: z.string(),
})