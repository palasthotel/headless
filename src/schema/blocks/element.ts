import {blockSchema} from "./base.ts";

export const coreMoreBlockSchema = blockSchema.pick({
    blockName: true,
})