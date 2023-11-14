import {RevisionResponse} from "@palasthotel/wp-rest";
import {Block, BlockContent} from "./post";

export type HeadlessRevisionResponse<B extends Block> = RevisionResponse & {
    content: BlockContent<B>
}