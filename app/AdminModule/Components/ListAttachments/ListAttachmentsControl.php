<?php

namespace Herecsrymy\AdminModule\Components\ListAttachments;

use Doctrine\ORM\UnitOfWork;
use Herecsrymy\Application\UI\TBaseControl;
use Herecsrymy\Entities\Attachment;
use Herecsrymy\Entities\Post;
use Kdyby\Doctrine\EntityManager;
use Nette\Application\UI\Control;


/**
 * @method void onAdd(Attachment $attachment)
 * @method void onDelete()
 */
class ListAttachmentsControl extends Control
{

	use TBaseControl;


	/** @var callable[] */
	public $onAdd = [];

	/** @var callable[] */
	public $onDelete = [];

	/** @var EntityManager */
	private $em;

	/** @var Post */
	private $post;

	/** @var Attachment[]|NULL */
	private $attachments;


	public function __construct(Post $post, EntityManager $em)
	{
		$this->em = $em;
		$this->post = $post;
		$this->attachments = $em->getUnitOfWork()->getEntityState($post) !== UnitOfWork::STATE_NEW
			? $post->attachments
			: NULL;
	}


	/** @secured */
	public function handleAdd($type)
	{
		$attachment = new Attachment('Untitled', $type, $this->post);
		$this->post->addAttachment($attachment);

		$this->em->persist($attachment)->flush();
		$this->onAdd($attachment);
	}


	/** @secured */
	public function handleDelete($id)
	{
		$attachment = $this->em->find(Attachment::class, $id);
		$this->post->removeAttachment($attachment);

		$this->em->remove($attachment)->flush();
		$this->onDelete();
	}


	public function render()
	{
		$this->template->post = $this->post;
		$this->template->isPostManaged = $this->em->getUnitOfWork()->getEntityState($this->post) === UnitOfWork::STATE_MANAGED;
		$this->template->attachments = $this->attachments;
		$this->template->render(__DIR__ . '/ListAttachmentsControl.latte');
	}

}
