<?php

namespace Herecsrymy\AdminModule\Components\EditPost;

use Herecsrymy\AdminModule\Components\ListAttachments\IListAttachmentsControlFactory;
use Herecsrymy\Application\UI\TBaseControl;
use Herecsrymy\Entities\Category;
use Herecsrymy\Entities\Post;
use Herecsrymy\Forms\Controls\DateTimeInput;
use Kdyby\Doctrine\EntityManager;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;


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


	public function __construct(Post $post, EntityManager $em)
	{
		parent::__construct();
		$this->post = $post;
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
		$form->addTextArea('text', 'Text')
			->setRequired('Please enter text.');

		$form->addCheckbox('containsChords', 'Show chords controls');

		$categoryItems = $this->em->getRepository(Category::class)->findPairs([], 'title', ['title' => 'ASC'], 'id');
		$form->addSelect('category', 'Category', $categoryItems)
			->setRequired('Please select category.');

		$dateInput = (new DateTimeInput('Published on'))
			->setRequired('Please enter publication date.');
		$form->addComponent($dateInput, 'publishedOn');
		$form->addCheckbox('published', 'Published');

		$form->addProtection();
		$form->addSubmit('save', 'Save');

		$form->setDefaults([
			'title' => $this->post->title,
			'slug' => $this->post->slug,
			'description' => $this->post->description,
			'text' => $this->post->text,
			'containsChords' => $this->post->containsChords,
			'category' => $this->post->category,
			'publishedOn' => $this->post->publishedOn,
			'published' => $this->post->published,
		]);

		$form->onSuccess[] = function (Form $form, $values) {
			$this->post->title = $values->title;
			$this->post->slug = $values->slug;
			$this->post->description = $values->description;
			$this->post->text = $values->text;
			$this->post->containsChords = $values->containsChords;
			$this->post->category = $values->category;
			$this->post->publishedOn = $values->publishedOn;
			$this->post->published = $values->published;

			$this->em->persist($this->post);
			$this->em->flush();
			$this->onSave($this->post);
		};

		return $form;
	}


	protected function createComponentListAttachments(IListAttachmentsControlFactory $factory)
	{
		$control = $factory->create($this->post);
		$control->onAdd[] = $control->onDelete[] = function () {
			$this->redirect('this');
		};

		return $control;
	}


	public function render()
	{
		$this->template->render(__DIR__ . '/EditPostControl.latte');
	}

}
