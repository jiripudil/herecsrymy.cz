<?php

declare(strict_types = 1);

namespace Herecsrymy\AdminModule\Components\UploadPhoto;

use Herecsrymy\Application\UI\TBaseControl;
use Herecsrymy\Entities\Photo;
use Herecsrymy\Files\PhotoUploader;
use Kdyby\Doctrine\EntityManager;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;


/**
 * @method void onUpload(Photo $photo)
 */
class UploadPhotoControl extends Control
{

	use TBaseControl;


	/**
	 * @var callable[]
	 */
	public $onUpload = [];

	/**
	 * @var EntityManager
	 */
	private $em;

	/**
	 * @var PhotoUploader
	 */
	private $uploader;


	public function __construct(EntityManager $em, PhotoUploader $uploader)
	{
		$this->em = $em;
		$this->uploader = $uploader;
	}


	protected function createComponentForm()
	{
		$form = new Form();
		$form->addUpload('file', 'File')
			->setRequired('Please attach a file.')
			->addRule($form::IMAGE, 'Please upload an image file.');
		$form->addText('description', 'Description')
			->setRequired('Please enter the description.');

		$form->addSubmit('upload', 'Upload');
		$form->onSuccess[] = function ($_, $values) {
			$photo = $this->uploader->upload($values->file, $values->description);
			$this->em->persist($photo)->flush();
			$this->onUpload($photo);
		};

		return $form;
	}


	public function render()
	{
		$this->template->render(__DIR__ . '/UploadPhotoControl.latte');
	}

}
