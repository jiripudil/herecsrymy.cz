<?php

namespace Herecsrymy\AdminModule\Components\EditLocation;

use Herecsrymy\Application\UI\TBaseControl;
use Herecsrymy\Entities\Location;
use Kdyby\Doctrine\EntityManager;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
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


	public function __construct(Location $location, EntityManager $em)
	{
		parent::__construct();
		$this->location = $location;
		$this->em = $em;
	}


	protected function createComponentForm()
	{
		$form = new Form();

		$form->addText('name', 'Name');
		$form->addText('address', 'Address')
			->setRequired('Please enter address.');
		$form['point'] = (new GpsPositionPicker('Coords'))
			->setRequired('Please enter coordinates.');

		$form->addProtection();
		$form->addSubmit('save', 'Save');

		$form->setDefaults([
			'name' => $this->location->name,
			'address' => $this->location->address,
			'point' => $this->location->point,
		]);

		$form->onSuccess[] = function (Form $form, $values) {
			$this->location->name = $values->name;
			$this->location->address = $values->address;
			$this->location->point = $values->point;

			$this->em->persist($this->location);
			$this->em->flush();
			$this->onSave($this->location);
		};

		return $form;
	}


	public function render()
	{
		$this->template->render(__DIR__ . '/EditLocationControl.latte');
	}

}
