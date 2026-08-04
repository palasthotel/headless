<?php

namespace Palasthotel\WordPress\Headless\Extensions;

use Palasthotel\WordPress\Headless\Interfaces\ICommentRouteExtension;
use WP_REST_Request;
use WP_REST_Response;

/**
 * Extends the comment REST response with the author's user data.
 *
 * Adds an "author_user" field containing the display_name if the comment has an
 * associated WordPress user account. The nickname is only included for users who
 * may list users, because it defaults to the account's login name.
 */
class CommentAuthorUser implements ICommentRouteExtension {

	/**
	 * Appends author user data to the comment REST response.
	 *
	 * @param WP_REST_Response $response The current REST response.
	 * @param \WP_Comment      $comment  The comment object.
	 * @param WP_REST_Request  $request  The current REST request.
	 * @return WP_REST_Response The modified response with "author_user" field added.
	 */
	function response( WP_REST_Response $response, \WP_Comment $comment, WP_REST_Request $request ): WP_REST_Response {
		$data = $response->get_data();
		$user = get_user_by("ID",$comment->user_id);

		if($user instanceof \WP_User){
			$data["author_user"]= [
				"display_name" => $user->display_name,
			];
			// wp_insert_user() defaults the nickname to user_login, so on most sites
			// this field is the login name. Core only ever exposes that in the "edit"
			// context, and the comments endpoint is public.
			if ( current_user_can( 'list_users' ) ) {
				$data["author_user"]["nickname"] = $user->nickname;
			}
		}  else {
			$data["author_user"] = null;
		}

		$response->set_data($data);

		return $response;
	}
}