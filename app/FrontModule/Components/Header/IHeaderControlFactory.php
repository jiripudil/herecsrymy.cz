<?php

namespace Slovotepec\FrontModule\Components\Header;


interface IHeaderControlFactory
{
	/**
	 * @param string $view
	 * @return HeaderControl
	 */
	function create($view = 'default');
}
