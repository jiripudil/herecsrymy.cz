<?php

namespace Herecsrymy\Application\UI;

use Kdyby\Autowired\AutowireComponentFactories;
use Nette\Application\UI\PresenterComponent;
use Nextras\Application\UI\SecuredLinksControlTrait;


/**
 * @method void onAttached(PresenterComponent $this, PresenterComponent $parent)
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
