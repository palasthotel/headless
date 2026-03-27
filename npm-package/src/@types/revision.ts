import {z} from "zod";
import {revisionWithBlocksResponseSchema} from "../schema";

export type HeadlessRevisionResponse = z.infer<typeof revisionWithBlocksResponseSchema>