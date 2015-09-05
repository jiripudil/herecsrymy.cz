<?php

namespace Herecsrymy\FrontModule\Components\Calendar;

use Herecsrymy\Application\UI\TBaseControl;
use Herecsrymy\Entities\Event;
use Herecsrymy\Entities\Queries\EventQuery;
use Kdyby\Doctrine\EntityManager;
use Nette\Application\UI\Control;


class CalendarControl extends Control
{

	use TBaseControl;


	/**
	 * @var Event[]
	 */
	private $events;


	public function __construct(EntityManager $em)
	{
		$query = (new EventQuery())->onlyPublished()->upcoming();
		$this->events = $em->getRepository(Event::class)->fetch($query)->toArray();
	}


	public function render()
	{
		if (empty($this->events)) {
			return;
		}

		$today = new \DateTime();

		$startDay = new \DateTime('last Monday');
		$endDay = clone $startDay;
		$endDay->modify('+4 weeks');

		$dates = new \DatePeriod($startDay, new \DateInterval('P1D'), $endDay);

		$this->template->dates = array_map(function (\DateTime $date) use ($today) {
			return (object) [
				'datetime' => $date,
				'hasEvent' => ! empty(array_filter($this->events, function (Event $event) use ($date) {
					return $event->datetime->format('d.m.Y') === $date->format('d.m.Y');
				})),
				'isToday' => $date->format('d.m.Y') === $today->format('d.m.Y'),
				'isPast' => $date->format('Ymd') < $today->format('Ymd'),
			];
		}, iterator_to_array($dates));

		$this->template->render(__DIR__ . '/CalendarControl.latte');
	}

}
