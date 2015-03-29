<?php

namespace Slovotepec\FrontModule\Presenters;

use Nette\Application\UI\Presenter;
use Slovotepec\Application\UI\TBasePresenter;
use Slovotepec\FrontModule\Components\Head\HeadControl;
use Slovotepec\FrontModule\Components\Header\IHeaderControlFactory;


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
