<?php

namespace Herecsrymy\AdminModule\Presenters;

use Herecsrymy\AdminModule\Components\FlashMessages\IFlashMessagesControlFactory;
use Herecsrymy\FrontModule\Components\Head\HeadControl;
use Herecsrymy\FrontModule\Components\Head\IHeadControlFactory;
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


	protected function createComponentHead(IHeadControlFactory $factory)
	{
		return $factory->create();
	}


	protected function beforeRender()
	{
		parent::beforeRender();

		/** @var HeadControl $head */
		$head = $this['head'];

		$head->setTitle('Herec s rýmy');
		$head->addTitlePart('Admin');
		$head->setTitleReversed(TRUE);
		$head->addStyle('static/css/admin.css');
		$head->addScript('static/js/admin.js');

		$head->setFavicon('favicon.ico');

		$head->addMeta('http-equiv', 'X-UA-Compatible', 'IE=edge,chrome=1');
		$head->addMeta('viewport', 'width=device-width');
		$head->addMeta('robots', 'noindex,nofollow');
	}

}
