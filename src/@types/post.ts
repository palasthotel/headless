import {PostResponse} from '@palasthotel/wp-fetch';

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