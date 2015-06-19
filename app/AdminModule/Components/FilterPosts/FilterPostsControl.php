<?php

namespace Herecsrymy\AdminModule\Components\FilterPosts;

use Herecsrymy\Application\UI\TBaseControl;
use Herecsrymy\Entities\Category;
use Herecsrymy\Entities\Queries\PostFilter;
use Kdyby\Doctrine\EntityManager;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;


class FilterPostsControl extends Control
{

	use TBaseControl;


	const PUBLISHED_YES = '1';
	const PUBLISHED_NO = '0';
	const PUBLISHED_IGNORE = NULL;


	/** @var string @persistent */
	public $published = self::PUBLISHED_IGNORE;

	/** @var int @persistent */
	public $category;

	/** @var EntityManager */
	private $em;

	/** @var PostFilter */
	private $filter;


	public function __construct(EntityManager $em)
	{
		$this->em = $em;
	}


	protected function attached($parent)
	{
		parent::attached($parent);

		$this->filter = new PostFilter();

		if ($this->published !== NULL) {
			$this['form-published']->setDefaultValue($this->published);
			$this->filter->setPublished((bool) $this->published);
		}

		if ($this->category !== NULL) {
			$this['form-category']->setDefaultValue($this->category);
			$this->filter->setCategory($this->em->find(Category::class, $this->category));
		}
	}


	/**
	 * @return PostFilter
	 */
	public function getFilter()
	{
		return $this->filter;
	}


	protected function createComponentForm()
	{
		$form = new Form();

		$form->addSelect('published', NULL, [
			self::PUBLISHED_YES => 'Published only',
			self::PUBLISHED_NO  => 'Unpublished only',
		])->setPrompt('(do not apply)');

		$categories = $this->em->getRepository(Category::class)->findPairs([], 'title', ['sort']);
		$form->addSelect('category', NULL, $categories)
			->setPrompt('(do not apply)');

		$form->addSubmit('filter', 'Filter');
		$form->onSuccess[] = function ($_, $values) {
			$this->redirect('this', [
				'published' => $values->published,
				'category' => $values->category,
			]);
		};

		return $form;
	}

}
