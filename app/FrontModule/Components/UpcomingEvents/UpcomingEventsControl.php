<?php

namespace Herecsrymy\FrontModule\Components\UpcomingEvents;

use Herecsrymy\Application\UI\TBaseControl;
use Herecsrymy\Entities\Event;
use Herecsrymy\Entities\Queries\EventQuery;
use Kdyby\Doctrine\EntityManager;
use Nette\Application\UI\Control;


class UpcomingEventsControl extends Control
{

	use TBaseControl;


	/** @var Event[] */
	private $events;


	public function __construct(EntityManager $em)
	{
		$query = (new EventQuery())->upcoming();
		$this->events = $em->getRepository(Event::class)->fetch($query)->applyPaging(0, 3);
	}


	public function render()
	{
		$this->template->events = $this->events;
		$this->template->render(__DIR__ . '/UpcomingEventsControl.latte');
	}

}
