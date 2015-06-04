<?php

namespace Herecsrymy\FrontModule\Components\Attachments;

use Herecsrymy\Entities\Post;


interface IAttachmentsControlFactory
{
	/**
	 * @param Post $post
	 * @return AttachmentsControl
	 */
	function create(Post $post);
}
