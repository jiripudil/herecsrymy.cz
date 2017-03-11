<?php

namespace Herecsrymy\AdminModule\Presenters;

use Herecsrymy\AdminModule\Components\EditLocation\IEditLocationControlFactory;
use Herecsrymy\AdminModule\Components\ListLocations\IListLocationsControlFactory;
use Herecsrymy\Entities\Location;
use Herecsrymy\Http\Csp\NonceGenerator;
use Kdyby\Doctrine\EntityManager;
use Nette\Application\UI\Presenter;


class LocationPresenter extends Presenter
{

	use TAdminPresenter;
	use TSecuredPresenter;


	/** @var string */
	private $gmapsApiKey;

	/** @var EntityManager */
	private $em;

	/** @var Location */
	private $location;


	public function __construct(string $gmapsApiKey, EntityManager $em)
	{
		$this->gmapsApiKey = $gmapsApiKey;
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


	public function renderEdit()
	{
		$this->template->gmapsApiKey = $this->gmapsApiKey;
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
