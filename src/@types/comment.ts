import {CommentResponse} from "@palasthotel/wp-fetch";

type AuthorUser = {
    display_name: string
    nickname: string
}

export type HeadlessCommentResponse = CommentResponse & {
    author_user: null | AuthorUser
}