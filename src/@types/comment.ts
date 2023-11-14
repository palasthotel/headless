import {CommentResponse} from "@palasthotel/wp-rest";

type AuthorUser = {
    display_name: string
    nickname: string
}

export type HeadlessCommentResponse = CommentResponse & {
    author_user: null | AuthorUser
}