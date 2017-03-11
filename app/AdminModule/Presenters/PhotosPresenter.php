<?php

declare(strict_types = 1);

namespace Herecsrymy\AdminModule\Presenters;

use Herecsrymy\AdminModule\Components\ListPhotos\IListPhotosControlFactory;
use Herecsrymy\AdminModule\Components\UploadPhoto\IUploadPhotoControlFactory;
use Nette\Application\UI\Presenter;


class PhotosPresenter extends Presenter
{

	use TAdminPresenter;
	use TSecuredPresenter;


	protected function createComponentListPhotos(IListPhotosControlFactory $factory)
	{
		$control = $factory->create();
		$control->onDelete[] = function () {
			$this['flashes']->flashMessage('The photo has been deleted.', 'success');
			$this->redirect('default');
		};

		return $control;
	}


	protected function createComponentUploadPhoto(IUploadPhotoControlFactory $factory)
	{
		$control = $factory->create();
		$control->onUpload[] = function () {
			$this['flashes']->flashMessage('The photo has been uploaded.', 'success');
			$this->redirect('default');
		};

		return $control;
	}

}
