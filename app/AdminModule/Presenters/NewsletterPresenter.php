<?php

namespace Herecsrymy\AdminModule\Presenters;

use Herecsrymy\AdminModule\Components\ListSubscribers\IListSubscribersControlFactory;
use Herecsrymy\AdminModule\Components\SendNewsletter\ISendNewsletterControlFactory;
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


	protected function createComponentSendNewsletter(ISendNewsletterControlFactory $factory)
	{
		$control = $factory->create();
		$control->onSend[] = function ($subject) {
			$this['flashes']->flashMessage('The newsletter with subject ' . $subject . ' has been sent.', 'success');
			$this->redirect('this');
		};

		return $control;
	}

}
