import {revisionResponseSchema} from "@palasthotel/wp-rest";
import {postContentSchema} from "./content.ts";

export const revisionWithBlocksResponseSchema = revisionResponseSchema.extend({
    content: postContentSchema,
});