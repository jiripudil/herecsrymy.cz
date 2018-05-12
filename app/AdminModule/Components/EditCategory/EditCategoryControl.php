<?php

namespace Herecsrymy\AdminModule\Components\EditCategory;

use Herecsrymy\Application\UI\TBaseControl;
use Herecsrymy\Entities\Category;
use Kdyby\Doctrine\EntityManager;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;


/**
 * @method void onSave(Category $category)
 */
class EditCategoryControl extends Control
{

	use TBaseControl;


	/** @var callable[] of function(Category $category) */
	public $onSave = [];

	/** @var Category */
	private $category;

	/** @var EntityManager */
	private $em;


	public function __construct(Category $category, EntityManager $em)
	{
		parent::__construct();
		$this->category = $category;
		$this->em = $em;
	}


	protected function createComponentForm()
	{
		$form = new Form();

		$form->addText('title', 'Title')
			->setRequired('Please enter title.');
		$form->addText('slug', 'Slug')
			->setRequired('Please enter slug.');
		$form->addText('description', 'Description')
			->setRequired('Please enter description.');
		$form->addText('sort', 'Pořadí')
			->setType('number')
			->setRequired('Please enter sort.');
		$form->addCheckbox('published', 'Published');

		$form->addProtection();
		$form->addSubmit('save', 'Save');

		$form->setDefaults([
			'title' => $this->category->title,
			'slug' => $this->category->slug,
			'description' => $this->category->description,
			'sort' => $this->category->sort,
			'published' => $this->category->published,
		]);

		$form->onSuccess[] = function (Form $form, $values) {
			$this->category->title = $values->title;
			$this->category->slug = $values->slug;
			$this->category->description = $values->description;
			$this->category->sort = $values->sort;
			$this->category->published = $values->published;

			$this->em->persist($this->category);
			$this->em->flush();
			$this->onSave($this->category);
		};

		return $form;
	}


	public function render()
	{
		$this->template->render(__DIR__ . '/EditCategoryControl.latte');
	}

}
