<?php

namespace Herecsrymy\AdminModule\Presenters;

use Herecsrymy\AdminModule\Components\FlashMessages\IFlashMessagesControlFactory;
use Kdyby\Autowired\AutowireComponentFactories;
use Nextras\Application\UI\SecuredLinksPresenterTrait;


trait TAdminPresenter
{

	use AutowireComponentFactories;
	use SecuredLinksPresenterTrait;


	protected function createComponentFlashes(IFlashMessagesControlFactory $factory)
	{
		return $factory->create();
	}

}
