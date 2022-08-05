import {PostId} from "@palasthotel/wp-fetch";

export type HeadlessSettingsResponse = {
    front_page: string
    page_on_front: PostId
    home_url: string
}