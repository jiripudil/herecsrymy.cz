<?php

namespace Herecsrymy\AdminModule\Components\EditEvent;

use Herecsrymy\Application\UI\TBaseControl;
use Herecsrymy\Entities\Event;
use Herecsrymy\Forms\Controls\DateTimeInput;
use Herecsrymy\Forms\EntityForm;
use Herecsrymy\Forms\IEntityFormFactory;
use Kdyby\Doctrine\EntityManager;
use Nette\Application\UI\Control;
use VojtechDobes\NetteForms\GpsPositionPicker;


/**
 * @method void onSave(Event $event)
 */
class EditEventControl extends Control
{

	use TBaseControl;


	/** @var callable[] of function(Event $event) */
	public $onSave = [];

	/** @var Event */
	private $event;

	/** @var EntityManager */
	private $em;

	/** @var IEntityFormFactory */
	private $formFactory;


	public function __construct(Event $event, EntityManager $em, IEntityFormFactory $formFactory)
	{
		$this->event = $event;
		$this->em = $em;
		$this->formFactory = $formFactory;
	}


	protected function createComponentForm()
	{
		$form = $this->formFactory->create();

		$form->addText('name', 'Name')
			->setRequired('Please enter name.');
		$form->addText('note', 'Note');

		$form['datetime'] = (new DateTimeInput('Date'))
			->setRequired('Please enter date and time.');
		$form->addText('location', 'Location')
			->setRequired('Please enter location.');
		$form['locationPoint'] = new GpsPositionPicker('Location coords');

		$form->addText('ticketsPrice', 'Tickets price');
		$form->addText('ticketsLink', 'Tickets link');
		$form->addText('facebookUrl', 'Facebook URL');

		$form->addCheckbox('published', 'Published');

		$form->addSubmit('save', 'Save');
		$form->onSuccess[] = function (EntityForm $form) {
			$this->em->persist($event = $form->getEntity())->flush();
			$this->onSave($event);
		};

		$form->bindEntity($this->event);

		return $form;
	}


	public function render()
	{
		$this->template->render(__DIR__ . '/EditEventControl.latte');
	}

}
