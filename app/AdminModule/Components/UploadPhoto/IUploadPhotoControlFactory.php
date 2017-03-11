<?php

declare(strict_types = 1);

namespace Herecsrymy\AdminModule\Components\UploadPhoto;


interface IUploadPhotoControlFactory
{

	public function create(): UploadPhotoControl;

}
