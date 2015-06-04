<?php

namespace Herecsrymy\AdminModule\Components\EditAttachment;

use Herecsrymy\Entities\Attachment;


interface IEditAttachmentControlFactory
{
	/**
	 * @param Attachment $attachment
	 * @return EditAttachmentControl
	 */
	function create(Attachment $attachment);
}
