<?php

namespace Herecsrymy\AdminModule\Components\EditLocation;

use Herecsrymy\Entities\Location;


interface IEditLocationControlFactory
{
	/**
	 * @param Location $location
	 * @return EditLocationControl
	 */
	function create(Location $location);
}
