<?php

namespace Herecsrymy\AdminModule\Components\ListLocations;

use Herecsrymy\Application\UI\TBaseControl;
use Herecsrymy\Entities\Location;
use Herecsrymy\Entities\Queries\LocationQuery;
use Herecsrymy\FrontModule\Components\Paging\IPagingControlFactory;
use Kdyby\Doctrine\EntityManager;
use Nette\Application\UI\Control;


/**
 * @method void onDelete()
 */
class ListLocationsControl extends Control
{

	use TBaseControl;


	/** @var callable[] */
	public $onDelete = [];

	/** @var EntityManager */
	private $em;

	/** @var Location[] */
	private $locations;


	public function __construct(EntityManager $em)
	{
		$this->em = $em;

		$query = new LocationQuery();
		$this->locations = $this->em->getRepository(Location::class)->fetch($query);
	}


	/** @secured */
	public function handleDelete($id)
	{
		$location = $this->em->find(Location::class, $id);
		$this->em->remove($location)->flush();
		$this->onDelete();
	}


	public function render()
	{
		$this->template->locations = $this->locations->applyPaginator($this['paging']->getPaginator(), 10);
		$this->template->render(__DIR__ . '/ListLocationsControl.latte');
	}


	protected function createComponentPaging(IPagingControlFactory $factory)
	{
		return $factory->create();
	}

}
