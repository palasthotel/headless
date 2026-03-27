<?php

namespace Palasthotel\WordPress\Headless\Extensions;

use Palasthotel\WordPress\Headless\Interfaces\ICommentRouteExtension;
use WP_REST_Request;
use WP_REST_Response;

/**
 * Extends the comment REST response with the author's user data.
 *
 * Adds an "author_user" field containing display_name and nickname
 * if the comment has an associated WordPress user account.
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
				"nickname" => $user->nickname,
			];
		}  else {
			$data["author_user"] = null;
		}

		$response->set_data($data);

		return $response;
	}
}