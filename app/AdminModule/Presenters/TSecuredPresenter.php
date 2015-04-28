<?php

namespace Herecsrymy\AdminModule\Presenters;


trait TSecuredPresenter
{

	protected function startup()
	{
		parent::startup();

		if ( ! $this->getUser()->isLoggedIn()) {
			$this['flashes']->flashMessage('You need to sign in to proceed with this action.', 'error');
			$this->redirect(':Admin:Sign:in');
		}
	}

}
