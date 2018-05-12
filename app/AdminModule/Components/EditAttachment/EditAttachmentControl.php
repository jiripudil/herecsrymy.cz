<?php

namespace Herecsrymy\AdminModule\Components\EditAttachment;

use Doctrine\ORM\UnitOfWork;
use Herecsrymy\Application\UI\TBaseControl;
use Herecsrymy\Entities\Attachment;
use Herecsrymy\Entities\File;
use Herecsrymy\Files\FileUploader;
use Kdyby\Doctrine\EntityManager;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Http\FileUpload;


/**
 * @method void onSave(Attachment $attachment)
 * @method void onFileUpload()
 * @method void onFileDelete()
 */
class EditAttachmentControl extends Control
{

	use TBaseControl;


	/** @var callable[] */
	public $onSave = [];

	/** @var callable[] */
	public $onFileUpload = [];

	/** @var callable[] */
	public $onFileDelete = [];

	/** @var Attachment */
	private $attachment;

	/** @var EntityManager */
	private $em;

	/** @var FileUploader */
	private $uploader;


	public function __construct(Attachment $attachment, EntityManager $em, FileUploader $uploader)
	{
		parent::__construct();
		$this->attachment = $attachment;
		$this->em = $em;
		$this->uploader = $uploader;
	}


	protected function createComponentForm()
	{
		$form = new Form();

		$form->addText('name', 'Name')
			->setRequired('Please enter name.');
		$form->addSelect('type', 'Type', [
			Attachment::TYPE_DOCUMENT => 'Document',
			Attachment::TYPE_AUDIO => 'Audio',
			Attachment::TYPE_VIDEO => 'Video',
			Attachment::TYPE_PHOTOS => 'Gallery',
		])->setRequired('Please select type.');
		$form->addCheckbox('displayed', 'Displayed publicly');
		$form->addCheckbox('inPlayer', 'Available in audio player');

		$form->addProtection();
		$form->addSubmit('save', 'Save');

		$form->setDefaults([
			'name' => $this->attachment->name,
			'type' => $this->attachment->type,
			'displayed' => $this->attachment->displayed,
			'inPlayer' => $this->attachment->inPlayer,
		]);

		$form->onSuccess[] = function (Form $form, $values) {
			$this->attachment->name = $values->name;
			$this->attachment->type = $values->type;
			$this->attachment->displayed = $values->displayed;
			$this->attachment->inPlayer = $values->inPlayer;

			$this->em->persist($this->attachment);
			$this->em->flush();
			$this->onSave($this->attachment);
		};

		return $form;
	}


	/** @secured */
	public function handleDeleteFile($id)
	{
		$file = $this->em->getRepository(File::class)->find($id);
		$this->attachment->removeFile($file);

		$path = $this->uploader->getRootPath() . DIRECTORY_SEPARATOR . $this->attachment->getDirectoryName() . DIRECTORY_SEPARATOR . $file->fileName;
		@unlink($path);

		$this->em->remove($file)->flush();
		$this->onFileDelete();
	}


	protected function createComponentFileUploadForm()
	{
		$form = new Form();
		$form->addMultiUpload('files', 'Files');
		$form->addSubmit('upload', 'Upload');

		$form->onSuccess[] = [$this, 'processFileUploadForm'];
		return $form;
	}


	public function processFileUploadForm($_, $values)
	{
		foreach ($values->files as $file) {
			/** @var FileUpload $file */
			$file = $this->uploader->upload($file, $this->attachment);
			$this->em->persist($file);
		}

		$this->em->flush();
		$this->onFileUpload();
	}


	public function render()
	{
		$this->template->attachment = $this->attachment;
		$this->template->files = $this->attachment->files;
		$this->template->isManaged = $this->em->getUnitOfWork()->getEntityState($this->attachment) === UnitOfWork::STATE_MANAGED;
		$this->template->render(__DIR__ . '/EditAttachmentControl.latte');
	}

}
