<?php

namespace Herecsrymy\FrontModule\Presenters;

use Herecsrymy\Application\UI\TBasePresenter;
use Herecsrymy\Entities\Event;
use Herecsrymy\Entities\Queries\EventQuery;
use Herecsrymy\FrontModule\Components\Head\HeadControl;
use Herecsrymy\FrontModule\Components\Header\IHeaderControlFactory;
use Herecsrymy\FrontModule\Components\Newsletter\INewsletterControlFactory;
use Kdyby\Doctrine\EntityManager;
use Nette\Application\UI\Presenter;


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
		/** @var HeadControl $head */
		$head = $this['head'];
		$head->addTitlePart("Události");

		$this->template->events = $this->events;
	}


	public function renderExport(Event $event)
	{
		$this->template->event = $event;
	}


	protected function createComponentHeader(IHeaderControlFactory $factory)
	{
		return $factory->create('small');
	}


	protected function createComponentNewsletter(INewsletterControlFactory $factory)
	{
		$control = $factory->create();
		$control->onSubscribe[] = function () {
			$this->redirect('this');
		};

		return $control;
	}

}
