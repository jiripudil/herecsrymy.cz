<?php

declare(strict_types = 1);

namespace Herecsrymy\AdminModule\Components\ListPhotos;

use Herecsrymy\Application\UI\TBaseControl;
use Herecsrymy\Entities\Photo;
use Kdyby\Doctrine\EntityManager;
use Nette\Application\UI\Control;


/**
 * @method void onDelete()
 */
class ListPhotosControl extends Control
{

	use TBaseControl;


	/** @var callable[] */
	public $onDelete = [];

	/** @var EntityManager */
	private $em;

	/** @var Photo[] */
	private $photos;


	public function __construct(EntityManager $em)
	{
		$this->em = $em;
	}


	protected function attached($parent)
	{
		parent::attached($parent);
		$this->photos = $this->em->getRepository(Photo::class)->findAll();
	}


	/** @secured */
	public function handleDelete($id)
	{
		$photo = $this->em->find(Photo::class, $id);
		$this->em->remove($photo)->flush();
		$this->onDelete();
	}


	public function render()
	{
		$this->template->photos = $this->photos;
		$this->template->render(__DIR__ . '/ListPhotosControl.latte');
	}

}
