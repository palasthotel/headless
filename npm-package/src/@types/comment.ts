import {z} from "zod";
import {commentResponseSchema} from "../schema";

export type HeadlessCommentResponse = z.infer<typeof commentResponseSchema>