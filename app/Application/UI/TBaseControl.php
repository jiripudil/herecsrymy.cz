<?php

namespace Herecsrymy\Application\UI;

use Kdyby\Autowired\AutowireComponentFactories;
use Nette\Application\UI\Component;
use Nextras\Application\UI\SecuredLinksControlTrait;


/**
 * @method void onAttached(Component $self, Component $parent)
 */
trait TBaseControl
{

	use AutowireComponentFactories;
	use SecuredLinksControlTrait;


	/** @var callable[] */
	public $onAttached;


	protected function attached($parent)
	{
		parent::attached($parent);
		$this->onAttached($this, $parent);
	}

}
