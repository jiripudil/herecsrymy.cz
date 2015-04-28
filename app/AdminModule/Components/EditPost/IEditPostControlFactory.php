<?php

namespace Herecsrymy\AdminModule\Components\EditPost;

use Herecsrymy\Entities\Post;


interface IEditPostControlFactory
{
	/**
	 * @param Post $post
	 * @return EditPostControl
	 */
	function create(Post $post);
}
