import {commentResponseSchema as _commentResponseSchema} from "@palasthotel/wp-rest";
import {z} from "zod";

export const commentResponseSchema = _commentResponseSchema.extend({
    author_user: z.object({
        display_name: z.string(),
        // Only present for requests by a user who may list users: the plugin
        // withholds it otherwise, because it defaults to the account's login name.
        nickname: z.string().optional(),
    }).nullable()
})