import {z} from "zod";

export const imageSizeSchema = z.tuple([
    z.string().url(),
    z.number(),
    z.number(),
    z.boolean(),
]);