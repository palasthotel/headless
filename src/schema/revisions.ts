import {revisionResponseSchema} from "@palasthotel/wp-rest";
import {postContentSchema} from "./content";

export const revisionWithBlocksResponseSchema = revisionResponseSchema.extend({
    content: postContentSchema,
});
