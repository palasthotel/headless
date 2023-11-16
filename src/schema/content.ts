import {z} from "zod";
import {zBlock} from "./blocks";

export const postContentSchema = z.object({
    headless_blocks: zBlock.array(),
    headless_attachment_ids: z.number().array(),
})