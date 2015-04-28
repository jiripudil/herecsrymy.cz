<?php

namespace Herecsrymy\AdminModule\Components\EditPost;

use Herecsrymy\Application\UI\TBaseControl;
use Herecsrymy\Entities\Post;
use Herecsrymy\Forms\Controls\DateTimeInput;
use Herecsrymy\Forms\EntityForm;
use Herecsrymy\Forms\IEntityFormFactory;
use Kdyby\Doctrine\EntityManager;
use Kdyby\DoctrineForms\IComponentMapper;
use Nette\Application\UI\Control;


/**
 * @method void onSave(Post $post)
 */
class EditPostControl extends Control
{

	use TBaseControl;


	/** @var callable[] of function(Post $post) */
	public $onSave = [];

	/** @var Post */
	private $post;

	/** @var EntityManager */
	private $em;

	/** @var IEntityFormFactory */
	private $formFactory;


	public function __construct(Post $post, EntityManager $em, IEntityFormFactory $formFactory)
	{
		$this->post = $post;
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
		$form->addTextArea('text', 'Text')
			->setRequired('Please enter text.');

		$form->addSelect('category', 'Category')
			->setRequired('Please select category.')
			->setOption(IComponentMapper::ITEMS_TITLE, 'title');

		$dateInput = (new DateTimeInput('Published on'))
			->setRequired('Please enter publication date.');
		$form->addComponent($dateInput, 'publishedOn');
		$form->addCheckbox('published', 'Published');

		$form->addSubmit('save', 'Save');
		$form->onSuccess[] = function (EntityForm $form) {
			$this->em->persist($post = $form->getEntity())->flush();
			$this->onSave($post);
		};

		$form->bindEntity($this->post);

		return $form;
	}


	public function render()
	{
		$this->template->render(__DIR__ . '/EditPostControl.latte');
	}

}
