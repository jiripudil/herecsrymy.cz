<?php

declare(strict_types = 1);

namespace Herecsrymy\FrontModule\Components\PostsFilter;

use Herecsrymy\Entities\Category;
use Herecsrymy\Entities\Queries\CategoryQuery;
use Kdyby\Doctrine\EntityManager;
use Nette\Application\IPresenter;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Tracy\Debugger;


/**
 * @method void onFilter(PostsFilterControl $self, Category[] $categories)
 */
class PostsFilterControl extends Control
{

	/**
	 * @var string[]
	 * @persistent
	 */
	public $categories = [];

	/**
	 * @var callable[]
	 */
	public $onFilter = [];

	/**
	 * @var Category[]
	 */
	private $allCategories = [];

	/**
	 * @var Category[]
	 */
	private $selectedCategories = [];

	/**
	 * @var EntityManager
	 */
	private $em;


	public function __construct(EntityManager $em)
	{
		$this->em = $em;

		$categoryQuery = (new CategoryQuery())->indexById()->onlyPublished();
		$this->allCategories = iterator_to_array($this->em->getRepository(Category::class)->fetch($categoryQuery));
	}


	/**
	 * @return Category[]
	 */
	public function getSelectedCategories(): array
	{
		return $this->selectedCategories;
	}


	protected function attached($parent)
	{
		parent::attached($parent);

		Debugger::barDump($this->categories);

		if ($parent instanceof IPresenter) {
			if (empty($this->categories)) {
				$this->selectedCategories = $this->allCategories;

			} else {
				$this->selectedCategories = array_filter($this->allCategories, function (Category $category) {
					return in_array($category->slug, $this->categories, TRUE);
				});
			}

			$this['form-categories']->setDefaultValue(array_keys($this->selectedCategories));
		}
	}


	protected function createComponentForm()
	{
		$form = new Form();
		$form->addCheckboxList('categories', NULL, array_map(function (Category $category) {
			return $category->title;
		}, $this->allCategories));

		$form->addSubmit('filter', 'Filtrovat');
		$form->onSuccess[] = function ($_, $values) {
			$ids = $values->categories;
			$this->selectedCategories = array_filter($this->allCategories, function (Category $category) use ($ids) {
				return in_array($category->getId(), $ids, TRUE);
			});

			$categorySlugs = array_map(function (Category $category) {
				return $category->slug;
			}, $this->selectedCategories);

			$this->categories = count($this->selectedCategories) !== count($this->allCategories)
				? $categorySlugs
				: [];

			$this->onFilter($this, $this->selectedCategories);
		};

		return $form;
	}


	public function render()
	{
		$this->template->allCategories = $this->allCategories;
		$this->template->render(__DIR__ . '/PostsFilterControl.latte');
	}

}
