<?php

namespace Herecsrymy\AdminModule\Presenters;

use Herecsrymy\AdminModule\Components\EditLocation\IEditLocationControlFactory;
use Herecsrymy\AdminModule\Components\ListLocations\IListLocationsControlFactory;
use Herecsrymy\Entities\Location;
use Kdyby\Doctrine\EntityManager;
use Nette\Application\UI\Presenter;


class LocationPresenter extends Presenter
{

	use TAdminPresenter {
		beforeRender as baseBeforeRender;
	}
	use TSecuredPresenter;


	/** @var EntityManager */
	private $em;

	/** @var Location */
	private $location;


	public function __construct(EntityManager $em)
	{
		$this->em = $em;
	}


	public function actionEdit($id = NULL)
	{
		if ($id !== NULL) {
			$this->location = $this->em->find(Location::class, $id);

		} else {
			$this->location = new Location();
		}

		$this['editLocation']->onSave[] = function () {
			$this['flashes']->flashMessage('Saved.', 'success');
			$this->redirect('default');
		};
	}


	protected function beforeRender()
	{
		$this['head']->addScript('//maps.googleapis.com/maps/api/js?libraries=places&sensor=false');
		$this->baseBeforeRender();
	}


	protected function createComponentListLocations(IListLocationsControlFactory $factory)
	{
		$control = $factory->create();
		$control->onDelete[] = function () {
			$this['flashes']->flashMessage('The location has been deleted.', 'success');
			$this->redirect('this');
		};

		return $control;
	}


	protected function createComponentEditLocation(IEditLocationControlFactory $factory)
	{
		return $factory->create($this->location);
	}

}
