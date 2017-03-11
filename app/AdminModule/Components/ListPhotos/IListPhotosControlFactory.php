<?php

declare(strict_types = 1);

namespace Herecsrymy\AdminModule\Components\ListPhotos;


interface IListPhotosControlFactory
{

	public function create(): ListPhotosControl;

}
