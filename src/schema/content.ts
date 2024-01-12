import {z} from "zod";
import {zBlock} from "./blocks";

export const postContentSchema = z.object({
    rendered: z.string().optional(),
    protected: z.boolean().optional(),
    headless_blocks: zBlock.array().or(z.literal(false)).optional(),
    headless_attachment_ids: z.number().array().optional(),
})