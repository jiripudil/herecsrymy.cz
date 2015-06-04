<?php

namespace Herecsrymy\AdminModule\Components\ListAttachments;

use Herecsrymy\Entities\Post;


interface IListAttachmentsControlFactory
{
	/**
	 * @param Post $post
	 * @return ListAttachmentsControl
	 */
	function create(Post $post);
}
