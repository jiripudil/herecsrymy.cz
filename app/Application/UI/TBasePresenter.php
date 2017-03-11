<?php

namespace Herecsrymy\Application\UI;

use Herecsrymy\FrontModule\Components\Head\HeadControl;
use Herecsrymy\FrontModule\Components\Head\IHeadControlFactory;
use Herecsrymy\FrontModule\Components\Player\IPlayerControlFactory;
use Kdyby\Autowired\AutowireComponentFactories;
use Nextras\Application\UI\SecuredLinksPresenterTrait;


trait TBasePresenter
{

	use AutowireComponentFactories;
	use SecuredLinksPresenterTrait;


	protected function createComponentPlayer(IPlayerControlFactory $factory)
	{
		return $factory->create();
	}


	protected function beforeRender()
	{
		parent::beforeRender();
		$this->redrawControl('title');
		$this->redrawControl('menu');
		$this->redrawControl('content');
	}

}
