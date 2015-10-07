<?php

namespace Herecsrymy\AdminModule\Components\ListLocations;


interface IListLocationsControlFactory
{
	/**
	 * @return ListLocationsControl
	 */
	function create();
}
