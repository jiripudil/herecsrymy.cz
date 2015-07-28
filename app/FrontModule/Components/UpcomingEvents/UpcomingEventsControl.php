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


	/** @var Event */
	private $event;


	public function __construct(EntityManager $em)
	{
		$query = (new EventQuery())->upcoming();
		$this->event = $em->getRepository(Event::class)->fetchOne($query);
	}


	public function render()
	{
		$this->template->event = $this->event;
		$this->template->render(__DIR__ . '/UpcomingEventsControl.latte');
	}

}
