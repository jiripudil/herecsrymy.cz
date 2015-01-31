<?php

namespace JiriHraje\Application\Routers;

use Nette;
use Nette\Application\Routers as NRouters;


class RouterFactory extends Nette\Object
{

	/**
	 * @return Nette\Application\IRouter
	 */
	public function createRouter()
	{
		$router = new NRouters\RouteList();

		$router[] = new NRouters\Route('<presenter>[/<action>[/<id>]]', 'Front:Homepage:default');

		return $router;
	}

}
