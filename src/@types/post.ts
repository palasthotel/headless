import {GetPostsRequestArgs, PostResponse, PostsResponse} from '@palasthotel/wp-fetch';

export type Block = {
    blockName: string
    attrs?: Record<string, string | string[] | number | number[]>
    innerHTML?: string
    innerContent?: string[]
    innerBlocks?: Block[]
}

export type HeadlessPostResponse<T extends Block> = PostResponse & {
    content: {
        headless_blocks: T[]|false
        headless_attachment_ids: number[]
        rendered: string|false
        protected: boolean
    }
}

export type HeadlessGetPostsRequestArgs = GetPostsRequestArgs & {
    hl_meta_exists: string
    hl_meta_not_exists: string
    hl_post_type: string | string[]
}