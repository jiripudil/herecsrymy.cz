<?php

namespace Herecsrymy\FrontModule\Components\MainMenu;

use Kdyby\Doctrine\EntityManager;
use Nette\Application\UI\Control;
use Herecsrymy\Application\UI\TBaseControl;
use Herecsrymy\Entities\Category;


class MainMenuControl extends Control
{

	use TBaseControl;


	/** @var EntityManager */
	private $em;

	/** @var Category|NULL */
	private $currentCategory;


	public function __construct(EntityManager $em)
	{
		$this->em = $em;
	}


	public function setCurrentCategory(Category $currentCategory)
	{
		$this->currentCategory = $currentCategory;
	}


	public function render()
	{
		$this->template->currentCategory = $this->currentCategory;
		$this->template->categories = $this->em->getRepository(Category::class)->findBy([
			'published' => TRUE,
		], [
			'sort' => 'ASC',
			'title' => 'ASC'
		]);

		$this->template->render(__DIR__ . '/MainMenuControl.latte');
	}

}
