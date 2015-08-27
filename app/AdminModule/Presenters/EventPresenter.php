<?php

namespace Herecsrymy\AdminModule\Presenters;

use Herecsrymy\AdminModule\Components\EditEvent\IEditEventControlFactory;
use Herecsrymy\Entities\Event;
use Kdyby\Doctrine\EntityManager;
use Nette\Application\UI\Presenter;


class EventPresenter extends Presenter
{

	use TAdminPresenter {
		beforeRender as baseBeforeRender;
	}
	use TSecuredPresenter;


	/** @var EntityManager */
	private $em;

	/** @var Event */
	private $event;


	public function __construct(EntityManager $em)
	{
		$this->em = $em;
	}


	public function actionEdit($id = NULL)
	{
		$this->event = $id !== NULL ? $this->em->find(Event::class, $id) : new Event('Untitled', new \DateTime());

		$this['editEvent']->onSave[] = function () {
			$this['flashes']->flashMessage('Saved.', 'success');
			$this->redirect('Dashboard:');
		};
	}


	protected function beforeRender()
	{
		$this['head']->addScript('//maps.googleapis.com/maps/api/js?libraries=places&sensor=false');
		$this->baseBeforeRender();
	}


	protected function createComponentEditEvent(IEditEventControlFactory $factory)
	{
		return $factory->create($this->event);
	}

}
