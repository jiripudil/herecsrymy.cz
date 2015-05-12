<?php

namespace Herecsrymy\AdminModule\Presenters;

use Herecsrymy\AdminModule\Components\ListCategories\IListCategoriesControlFactory;
use Herecsrymy\AdminModule\Components\ListEvents\IListEventsControlFactory;
use Herecsrymy\AdminModule\Components\ListPosts\IListPostsControlFactory;
use Nette\Application\UI\Presenter;


class DashboardPresenter extends Presenter
{

	use TAdminPresenter;
	use TSecuredPresenter;


	protected function createComponentListPosts(IListPostsControlFactory $factory)
	{
		$control = $factory->create();
		$control->onDelete[] = function () {
			$this['flashes']->flashMessage('The post has been deleted.', 'success');
			$this->redirect('this');
		};

		return $control;
	}


	protected function createComponentListCategories(IListCategoriesControlFactory $factory)
	{
		$control = $factory->create();
		$control->onDelete[] = function () {
			$this['flashes']->flashMessage('The category has been deleted.', 'success');
			$this->redirect('this');
		};

		return $control;
	}


	protected function createComponentListEvents(IListEventsControlFactory $factory)
	{
		$control = $factory->create();
		$control->onDelete[] = function () {
			$this['flashes']->flashMessage('The event has been deleted.', 'success');
			$this->redirect('this');
		};

		return $control;
	}

}
