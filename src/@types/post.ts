import {PostResponse} from '@palasthotel/wp-fetch';

type Block = {
    blockName: string
    attrs?: Record<string, string | string[] | number | number[]>
    innerHTML?: string
    innerContent?: string[]
    innerBlocks?: Block[]
}

export type HeadlessPostResponse = PostResponse & {
    content: {
        headless_blocks: Block[]|false
        headless_attachment_ids: number[]
        rendered: string|false
        protected: boolean
    }
}