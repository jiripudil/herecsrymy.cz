<?php

namespace Herecsrymy\AdminModule\Presenters;

use Herecsrymy\AdminModule\Components\EditCategory\IEditCategoryControlFactory;
use Herecsrymy\Entities\Category;
use Kdyby\Doctrine\EntityManager;
use Nette\Application\UI\Presenter;


class CategoryPresenter extends Presenter
{

	use TAdminPresenter;
	use TSecuredPresenter;


	/** @var EntityManager */
	private $em;

	/** @var Category */
	private $category;


	public function __construct(EntityManager $em)
	{
		$this->em = $em;
	}


	public function actionEdit($id = NULL)
	{
		$this->category = $id !== NULL ? $this->em->find(Category::class, $id) : new Category('Untitled');

		$this['editCategory']->onSave[] = function () {
			$this['flashes']->flashMessage('Saved.', 'success');
			$this->redirect('Dashboard:');
		};
	}


	protected function createComponentEditCategory(IEditCategoryControlFactory $factory)
	{
		return $factory->create($this->category);
	}

}
