import {GetPostsRequestArgs, PostResponse} from '@palasthotel/wp-fetch';

export type Block = {
    blockName: string|null
    attrs?: Record<string, string | string[] | number | number[]>
    innerHTML?: string
    innerContent?: string[]
    innerBlocks?: Block[]
}

export type BlockContent<B extends Block> = {
    headless_blocks: B[] | false
    headless_attachment_ids: number[]
    rendered: string | false
    protected: boolean
}

export type HeadlessImageSrc = [url:string,width:number,height:number,isResized: boolean]

export type HeadlessPostResponse<B extends Block> = PostResponse & {
    featured_media_url: string|false
    featured_media_src: HeadlessImageSrc|false
    featured_media_caption: string|false
    featured_media_description: string|false
    featured_media_alt: string|false
    content: BlockContent<B>
}

export type CompareParam = "eq" | "neq" | "like";
export type CompareArg = "=" | "!=" | "like";

export type HeadlessGetPostsRequestArgs = GetPostsRequestArgs & {

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