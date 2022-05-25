import {GetPostsRequestArgs, PostResponse} from '@palasthotel/wp-fetch';

export type Block = {
    blockName: string
    attrs?: Record<string, string | string[] | number | number[]>
    innerHTML?: string
    innerContent?: string[]
    innerBlocks?: Block[]
}

export type BlockContent<B extends Block> =  {
    headless_blocks: B[]|false
    headless_attachment_ids: number[]
    rendered: string|false
    protected: boolean
}

export type HeadlessPostResponse<B extends Block> = PostResponse & {
    content: BlockContent<B>
}

export type HeadlessGetPostsRequestArgs = GetPostsRequestArgs & {
    hl_meta_exists?: string
    hl_meta_not_exists?: string
    hl_post_type?: string[]
}