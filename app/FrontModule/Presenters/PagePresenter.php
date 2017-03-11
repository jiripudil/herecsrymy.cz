<?php

declare(strict_types = 1);

namespace Herecsrymy\FrontModule\Presenters;

use Herecsrymy\Application\UI\TBasePresenter;
use Herecsrymy\Entities\Photo;
use Kdyby\Doctrine\EntityManager;
use Nette\Application\UI\Presenter;


class PagePresenter extends Presenter
{

	use TBasePresenter;


	/**
	 * @var EntityManager
	 */
	private $em;


	public function __construct(EntityManager $em)
	{
		$this->em = $em;
	}


	public function renderAbout()
	{
		$this->template->photos = $this->em->getRepository(Photo::class)->findAll();
	}

}
