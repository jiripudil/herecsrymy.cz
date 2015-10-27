<?php

namespace Herecsrymy\FrontModule\Presenters;

use Herecsrymy\FrontModule\Components\Newsletter\INewsletterControlFactory;
use Herecsrymy\FrontModule\Components\RecentPosts\IRecentPostsControlFactory;
use Herecsrymy\FrontModule\Components\UpcomingEvents\IUpcomingEventsControlFactory;
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


	protected function createComponentRecentPosts(IRecentPostsControlFactory $factory)
	{
		return $factory->create(3);
	}


	protected function createComponentUpcomingEvents(IUpcomingEventsControlFactory $factory)
	{
		return $factory->create();
	}


	protected function createComponentNewsletter(INewsletterControlFactory $factory)
	{
		$control = $factory->create();
		$control->onSubscribe[] = function () use ($control) {
			$this->redirect('this');
		};

		return $control;
	}

}
