<?php

namespace JiriHraje\DI;

use Nette;


class JiriHrajeExtension extends Nette\DI\CompilerExtension
{

	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		$services = $this->loadFromFile(__DIR__ . '/jirihraje.neon');
		$this->compiler->parseServices($builder, $services);
	}

}
