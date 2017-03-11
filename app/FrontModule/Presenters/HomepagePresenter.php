<?php

namespace Herecsrymy\FrontModule\Presenters;

use Herecsrymy\FrontModule\Components\RecentPosts\IRecentPostsControlFactory;
use Herecsrymy\FrontModule\Components\UpcomingEvents\IUpcomingEventsControlFactory;
use Nette\Application\UI\Presenter;
use Herecsrymy\Application\UI\TBasePresenter;


class HomepagePresenter extends Presenter
{

	use TBasePresenter;


	protected function createComponentRecentPosts(IRecentPostsControlFactory $factory)
	{
		return $factory->create(3);
	}


	protected function createComponentUpcomingEvents(IUpcomingEventsControlFactory $factory)
	{
		return $factory->create();
	}

}
