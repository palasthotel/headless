import {z} from "zod";
import {GetPostsRequestArgs} from '@palasthotel/wp-rest';
import {postContentSchema} from "../schema";
import {postWithBlocksResponseSchema} from "../schema";

export type Block = {
    blockName: string|null
    innerBlocks?: Block[]
    innerHTML?: string
    innerContent?: (string|null)[]
    attrs: {[key: string]: any}
}

export type Content = z.infer<typeof postContentSchema>

export type CompareParam = "eq" | "neq" | "like";
export type CompareArg = "=" | "!=" | "like";

export type GetHeadlessPostsRequestArgs = GetPostsRequestArgs & {
    hl_meta_query?: {
        key: string
        value: string | number
        compare: CompareArg
    }[]

    hl_meta_keys?: string[]
    hl_meta_values?: (string | number)[]
    hl_meta_compares?: CompareParam[]

    hl_meta_exists?: string
    hl_meta_not_exists?: string
    hl_post_type?: string[]
}

export type HeadlessPostResponse = z.infer<typeof postWithBlocksResponseSchema>