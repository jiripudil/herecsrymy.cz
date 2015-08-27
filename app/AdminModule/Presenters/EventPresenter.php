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


	public function actionEdit($id = NULL, $repeat = NULL)
	{
		if ($id !== NULL) {
			$this->event = $this->em->find(Event::class, $id);

		} elseif ($repeat !== NULL) {
			$this->event = clone $this->em->find(Event::class, $repeat);

		} else {
			$this->event = new Event('Untitled', new \DateTime());
		}

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
