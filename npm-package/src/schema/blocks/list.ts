import {blockSchema, groupBlockSchema} from "./base";
import {z} from "zod";

export const coreListBlockSchema = groupBlockSchema.extend({
    blockName: z.literal("core/list"),
    innerBlocks: z.array(
        blockSchema.extend({
            blockName: z.literal("core/list-item"),
            innerHTML: z.string(),
        })
    ),
});
