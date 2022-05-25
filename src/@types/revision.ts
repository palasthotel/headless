import {RevisionResponse} from "@palasthotel/wp-fetch";
import {Block, BlockContent} from "./post";

export type HeadlessRevisionResponse<B extends Block> = RevisionResponse & {
    content: BlockContent<B>
}