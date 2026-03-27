import {commentResponseSchema as _commentResponseSchema} from "@palasthotel/wp-rest";
import {z} from "zod";

export const commentResponseSchema = _commentResponseSchema.extend({
    author_user: z.object({
        display_name: z.string(),
        nickname: z.string(),
    }).nullable()
})