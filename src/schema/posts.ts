import {z} from "zod";
import {postResponseSchema} from "@palasthotel/wp-rest";
import {imageSizeSchema} from "./parts.ts";
import {postContentSchema} from "./content.ts";

export const postWithBlocksResponseSchema = postResponseSchema
    .extend({
        content: postContentSchema,
        featured_media_url: z.string().or(z.literal(false)),
        featured_media_src: imageSizeSchema.or(z.literal(false)),
        featured_media_sizes: imageSizeSchema.array(),
        featured_media_caption: z.string().or(z.literal(false)),
        featured_media_description: z.string().or(z.literal(false)),
        featured_media_alt: z.string().or(z.literal(false)),
    });