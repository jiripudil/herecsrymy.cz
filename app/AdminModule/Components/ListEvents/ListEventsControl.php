<?php

namespace Herecsrymy\AdminModule\Components\ListEvents;

use Herecsrymy\Application\UI\TBaseControl;
use Herecsrymy\Entities\Event;
use Herecsrymy\Entities\Queries\EventQuery;
use Kdyby\Doctrine\EntityManager;
use Nette\Application\UI\Control;


/**
 * @method void onDelete()
 */
class ListEventsControl extends Control
{

	use TBaseControl;


	/** @var callable[] */
	public $onDelete = [];

	/** @var EntityManager */
	private $em;

	/** @var Event[] */
	private $events;


	public function __construct(EntityManager $em)
	{
		$this->em = $em;

		$query = (new EventQuery())->upcoming();
		$this->events = $this->em->getRepository(Event::class)->fetch($query);
	}


	/** @secured */
	public function handleDelete($id)
	{
		$event = $this->em->find(Event::class, $id);
		$this->em->remove($event)->flush();
		$this->onDelete();
	}


	public function render()
	{
		$this->template->events = $this->events;
		$this->template->render(__DIR__ . '/ListEventsControl.latte');
	}

}
