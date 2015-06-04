<?php

namespace Herecsrymy\FrontModule\Presenters;

use Herecsrymy\Entities\File;
use Herecsrymy\Files\FileUploader;
use Nette\Application\Responses\FileResponse;
use Nette\Application\UI\Presenter;


class FilePresenter extends Presenter
{

	/** @var FileUploader */
	private $uploader;


	public function __construct(FileUploader $uploader)
	{
		$this->uploader = $uploader;
	}


	public function actionDefault(File $file)
	{
		$path = $this->uploader->getRootPath()
			. DIRECTORY_SEPARATOR . $file->attachment->getDirectoryName()
			. DIRECTORY_SEPARATOR . $file->fileName;

		if ( ! file_exists($path)) {
			$this->error("File '$path' does not exist.");
		}

		$this->sendResponse(new FileResponse($path, $file->fileName));
	}

}
