import {z} from "zod";
import {blockSchema} from "./base.ts";
import {imageSizeSchema} from "../parts.ts";

export const coreImageBlockSchema = blockSchema
    .omit({
        innerContent: true,
        innerHTML: true,
    })
    .extend({
        attrs: z.object({
            id: z.number(),
            src: imageSizeSchema,
            sizes: z.array(imageSizeSchema),
            alt: z.string().default(""),
            caption: z.string().default(""),
        })
    })