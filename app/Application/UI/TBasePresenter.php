<?php

namespace Herecsrymy\Application\UI;

use Herecsrymy\FrontModule\Components\GoogleAnalytics\IGoogleAnalyticsControlFactory;
use Herecsrymy\FrontModule\Components\Head\HeadControl;
use Herecsrymy\FrontModule\Components\Head\IHeadControlFactory;
use Herecsrymy\FrontModule\Components\MainMenu\IMainMenuControlFactory;
use Kdyby\Autowired\AutowireComponentFactories;
use Nextras\Application\UI\SecuredLinksPresenterTrait;


trait TBasePresenter
{

	use AutowireComponentFactories;
	use SecuredLinksPresenterTrait;


	protected function createComponentMainMenu(IMainMenuControlFactory $factory)
	{
		return $factory->create();
	}


	protected function createComponentHead(IHeadControlFactory $factory)
	{
		return $factory->create();
	}


	protected function createComponentGoogleAnalytics(IGoogleAnalyticsControlFactory $factory)
	{
		return $factory->create();
	}


	protected function beforeRender()
	{
		parent::beforeRender();

		/** @var HeadControl $head */
		$head = $this['head'];

		$head->setTitle('Jiří Pudil');
		$head->setTitleReversed(TRUE);
		$head->addStyle('static/css/new.css');
		$head->addScript('static/js/scripts.js', TRUE);
		$head->addFeed(HeadControl::FEED_RSS, $this->link(':Front:Feed:'), 'Všechny příspěvky – Jiří Pudil, herec s rýmy');

		$head->setFavicon('favicon-32.png');

		$head->addMeta('http-equiv', 'X-UA-Compatible', 'IE=edge,chrome=1');
		$head->addMeta('viewport', 'width=device-width');
		$head->addMeta('property', 'fb:admins', '1625947532');

		$baseUrl = $this->getHttpRequest()->getUrl()->getBaseUrl();
		$head->addMeta('property', 'og:image', $baseUrl . 'static/images/jiri.jpg');
	}

}
