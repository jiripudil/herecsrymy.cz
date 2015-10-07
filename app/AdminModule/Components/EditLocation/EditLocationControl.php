<?php

namespace Herecsrymy\AdminModule\Components\EditLocation;

use Herecsrymy\Application\UI\TBaseControl;
use Herecsrymy\Entities\Location;
use Herecsrymy\Forms\EntityForm;
use Herecsrymy\Forms\IEntityFormFactory;
use Kdyby\Doctrine\EntityManager;
use Nette\Application\UI\Control;
use VojtechDobes\NetteForms\GpsPositionPicker;


/**
 * @method void onSave(Location $location)
 */
class EditLocationControl extends Control
{

	use TBaseControl;


	/** @var callable[] of function(Location $location) */
	public $onSave = [];

	/** @var Location */
	private $location;

	/** @var EntityManager */
	private $em;

	/** @var IEntityFormFactory */
	private $formFactory;


	public function __construct(Location $location, EntityManager $em, IEntityFormFactory $formFactory)
	{
		$this->location = $location;
		$this->em = $em;
		$this->formFactory = $formFactory;
	}


	protected function createComponentForm()
	{
		$form = $this->formFactory->create();

		$form->addText('name', 'Name');
		$form->addText('address', 'Address')
			->setRequired('Please enter address.');
		$form['point'] = (new GpsPositionPicker('Coords'))
			->setRequired('Please enter coordinates.');

		$form->addSubmit('save', 'Save');
		$form->onSuccess[] = function (EntityForm $form) {
			$this->em->persist($location = $form->getEntity())->flush();
			$this->onSave($location);
		};

		$form->bindEntity($this->location);

		return $form;
	}


	public function render()
	{
		$this->template->render(__DIR__ . '/EditLocationControl.latte');
	}

}
