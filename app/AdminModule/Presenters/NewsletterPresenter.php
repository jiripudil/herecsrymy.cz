<?php

namespace Herecsrymy\AdminModule\Presenters;

use Herecsrymy\AdminModule\Components\ListSubscribers\IListSubscribersControlFactory;
use Herecsrymy\Entities\NewsletterSubscription;
use Nette\Application\UI\Presenter;


class NewsletterPresenter extends Presenter
{

	use TAdminPresenter;
	use TSecuredPresenter;


	protected function createComponentListSubscribers(IListSubscribersControlFactory $factory)
	{
		$control = $factory->create();
		$control->onUnsubscribe[] = function (NewsletterSubscription $subscription) {
			$this['flashes']->flashMessage('The e-mail address ' . $subscription->email . ' has been unsubscribed.', 'success');
			$this->redirect('this');
		};

		return $control;
	}

}
