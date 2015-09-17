<?php

namespace Herecsrymy\AdminModule\Components\ListCategories;

use Herecsrymy\Application\UI\TBaseControl;
use Herecsrymy\Entities\Category;
use Herecsrymy\Entities\Queries\CategoryQuery;
use Kdyby\Doctrine\EntityManager;
use Nette\Application\UI\Control;


/**
 * @method void onDelete()
 */
class ListCategoriesControl extends Control
{

	use TBaseControl;


	/** @var callable[] */
	public $onDelete = [];

	/** @var EntityManager */
	private $em;

	/** @var Category[] */
	private $categories;


	public function __construct(EntityManager $em)
	{
		$this->em = $em;

		$query = (new CategoryQuery())
			->countPosts();
		$this->categories = $this->em->getRepository(Category::class)->fetch($query);
	}


	/** @secured */
	public function handleDelete($id)
	{
		$category = $this->em->find(Category::class, $id);
		$this->em->remove($category)->flush();
		$this->onDelete();
	}


	public function render()
	{
		$this->template->categories = $this->categories;
		$this->template->postsLink = $this->presenter->lazyLink('Post:default');
		$this->template->render(__DIR__ . '/ListCategoriesControl.latte');
	}

}
