<?php

namespace Herecsrymy\FrontModule\Presenters;

use Nette\Application\UI\Presenter;
use Herecsrymy\Application\UI\TBasePresenter;
use Herecsrymy\FrontModule\Components\Head\HeadControl;
use Herecsrymy\FrontModule\Components\Header\IHeaderControlFactory;


class HomepagePresenter extends Presenter
{

	use TBasePresenter {
		beforeRender as baseBeforeRender;
	}


	protected function createComponentHeader(IHeaderControlFactory $factory)
	{
		return $factory->create();
	}


	protected function beforeRender()
	{
		$this->baseBeforeRender();

		/** @var HeadControl $head */
		$head = $this['head'];
		$head->setTitle('Jiří Pudil, brněnský herec s rýmy');
	}

}
