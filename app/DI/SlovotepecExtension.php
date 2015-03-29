<?php

namespace Slovotepec\DI;

use Nette;
use Slovotepec;


class SlovotepecExtension extends Nette\DI\CompilerExtension
{

	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		$services = $this->loadFromFile(__DIR__ . '/slovotepec.neon');
		$this->compiler->parseServices($builder, $services);

		$builder->addDefinition($this->prefix('latteFilters'))
			->setClass(Slovotepec\Latte\Filters::class);

		$engine = $builder->getDefinition('nette.latteFactory');
		$engine->addSetup('addFilter', [NULL, [$this->prefix('@latteFilters'), 'loader']]);
	}

}
