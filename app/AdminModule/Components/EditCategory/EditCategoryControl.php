<?php

namespace Herecsrymy\AdminModule\Components\EditCategory;

use Herecsrymy\Application\UI\TBaseControl;
use Herecsrymy\Entities\Category;
use Herecsrymy\Forms\EntityForm;
use Herecsrymy\Forms\IEntityFormFactory;
use Kdyby\Doctrine\EntityManager;
use Nette\Application\UI\Control;


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

	/** @var IEntityFormFactory */
	private $formFactory;


	public function __construct(Category $category, EntityManager $em, IEntityFormFactory $formFactory)
	{
		$this->category = $category;
		$this->em = $em;
		$this->formFactory = $formFactory;
	}


	protected function createComponentForm()
	{
		$form = $this->formFactory->create();

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

		$form->addSubmit('save', 'Save');
		$form->onSuccess[] = function (EntityForm $form) {
			$this->em->persist($category = $form->getEntity())->flush();
			$this->onSave($category);
		};

		$form->bindEntity($this->category);

		return $form;
	}


	public function render()
	{
		$this->template->render(__DIR__ . '/EditCategoryControl.latte');
	}

}
