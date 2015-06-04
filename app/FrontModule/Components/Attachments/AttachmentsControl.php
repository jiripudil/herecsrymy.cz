<?php

namespace Herecsrymy\FrontModule\Components\Attachments;

use Doctrine\Common\Collections\ArrayCollection;
use Herecsrymy\Application\UI\TBaseControl;
use Herecsrymy\Entities\Attachment;
use Herecsrymy\Entities\Post;
use Nette\Application\UI\Control;
use Nette\Application\UI\Multiplier;


class AttachmentsControl extends Control
{

	use TBaseControl;


	/** @var ArrayCollection|Attachment[] */
	private $attachments;


	public function __construct(Post $post)
	{
		$this->attachments = $post->attachments;
	}


	protected function createComponentAttachment()
	{
		return new Multiplier(function ($id) {
			return new AttachmentControl($this->attachments[$id]);
		});
	}


	public function render()
	{
		foreach ($this->attachments as $attachment) {
			$this['attachment-' . $attachment->id]->render();
		}
	}

}
