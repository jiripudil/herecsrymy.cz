<?php

namespace Herecsrymy\FrontModule\Presenters;

use Herecsrymy\Application\UI\TBasePresenter;
use Herecsrymy\Entities\Event;
use Herecsrymy\Entities\Queries\EventQuery;
use Kdyby\Doctrine\EntityManager;
use Nette\Application\UI\Presenter;
use Nette\Utils\Json;


class EventsPresenter extends Presenter
{

	use TBasePresenter;


	/** @var EntityManager */
	private $em;

	/** @var Event[] */
	private $events;


	public function __construct(EntityManager $em)
	{
		$this->em = $em;
	}


	public function actionDefault()
	{
		$query = (new EventQuery())->onlyPublished()->upcoming();
		$this->events = $this->em->getRepository(Event::class)->fetch($query);
	}


	public function renderDefault()
	{
		$this->template->events = $this->events;
		$this->template->eventDates = Json::encode(array_unique(array_map(function (Event $event) {
			return $event->datetime->format('Y-m-d');
		}, iterator_to_array($this->events))));
	}


	public function renderExport(Event $event)
	{
		if ( ! $event->published) {
			$this->error();
		}

		$this->template->event = $event;
	}

}
