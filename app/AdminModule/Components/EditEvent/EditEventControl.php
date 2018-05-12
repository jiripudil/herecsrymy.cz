<?php

namespace Herecsrymy\AdminModule\Components\EditEvent;

use Herecsrymy\Application\UI\TBaseControl;
use Herecsrymy\Entities\Event;
use Herecsrymy\Entities\Location;
use Herecsrymy\Forms\Controls\DateTimeInput;
use Kdyby\Doctrine\EntityManager;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;


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


	public function __construct(Event $event, EntityManager $em)
	{
		parent::__construct();
		$this->event = $event;
		$this->em = $em;
	}


	protected function createComponentForm()
	{
		$form = new Form();

		$form->addText('name', 'Name')
			->setRequired('Please enter name.');
		$form->addText('note', 'Note');

		$form['datetime'] = (new DateTimeInput('Date'))
			->setRequired('Please enter date and time.');

		$locationItems = $this->em->getRepository(Location::class)->findPairs([], 'name', ['name' => 'ASC'], 'id');
		$form->addSelect('location', 'Location', $locationItems)
			->setRequired('Please select location.');

		$form->addText('ticketsPrice', 'Tickets price')
			->setDefaultValue(0);
		$form->addText('ticketsLink', 'Tickets link');
		$form->addText('facebookUrl', 'Facebook URL');

		$form->addCheckbox('published', 'Published');

		$form->addProtection();
		$form->addSubmit('save', 'Save');

		$form->setDefaults([
			'name' => $this->event->name,
			'note' => $this->event->note,
			'datetime' => $this->event->datetime,
			'location' => $this->event->location,
			'ticketsPrice' => $this->event->ticketsPrice,
			'ticketsLink' => $this->event->ticketsLink,
			'facebookUrl' => $this->event->facebookUrl,
			'published' => $this->event->published,
		]);

		$form->onSuccess[] = function (Form $form, $values) {
			$this->event->name = $values->name;
			$this->event->note = $values->note;
			$this->event->datetime = $values->datetime;
			$this->event->location = $values->location;
			$this->event->ticketsPrice = $values->ticketsPrice;
			$this->event->ticketsLink = $values->ticketsLink;
			$this->event->facebookUrl = $values->facebookUrl;
			$this->event->published = $values->published;

			$this->em->persist($this->event);
			$this->em->flush();
			$this->onSave($this->event);
		};

		return $form;
	}


	public function render()
	{
		$this->template->render(__DIR__ . '/EditEventControl.latte');
	}

}
