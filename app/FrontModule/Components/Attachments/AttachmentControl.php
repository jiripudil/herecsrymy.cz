<?php

namespace Herecsrymy\FrontModule\Components\Attachments;

use Herecsrymy\Application\UI\TBaseControl;
use Herecsrymy\Entities\Attachment;
use Nette\Application\UI\Control;


class AttachmentControl extends Control
{

	use TBaseControl;


	/** @var Attachment */
	private $attachment;


	public function __construct(Attachment $attachment)
	{
		$this->attachment = $attachment;
	}


	public function render()
	{
		$this->template->attachment = $this->attachment;
		$this->template->render($this->getTemplateFile());
	}


	private function getTemplateFile()
	{
		switch ($this->attachment->type) {
			case Attachment::TYPE_AUDIO:
				return __DIR__ . '/AttachmentControl.audio.latte';

			case Attachment::TYPE_VIDEO:
				return __DIR__ . '/AttachmentControl.video.latte';

			case Attachment::TYPE_PHOTOS:
				return __DIR__ . '/AttachmentControl.gallery.latte';

			case Attachment::TYPE_DOCUMENT:
			default:
				return __DIR__ . '/AttachmentControl.document.latte';
		}
	}

}
