<?php

namespace Herecsrymy\DI;

use Nette;
use Herecsrymy;


class HerecsrymyExtension extends Nette\DI\CompilerExtension
{

	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		$services = $this->loadFromFile(__DIR__ . '/herecsrymy.neon');
		$this->compiler->parseServices($builder, $services);

		$builder->addDefinition($this->prefix('latteFilters'))
			->setClass(Herecsrymy\Latte\Filters::class);

		$engine = $builder->getDefinition('nette.latteFactory');
		$engine->addSetup('addFilter', [NULL, [$this->prefix('@latteFilters'), 'loader']]);
	}

}
