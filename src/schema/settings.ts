import {z} from "zod";
import {postIdSchema} from "@palasthotel/wp-rest";

export const settingsResponseSchema = z.object({
    front_page: z.string(),
    page_on_front: postIdSchema,
    home_url: z.string()
})