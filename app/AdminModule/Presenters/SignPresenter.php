<?php

namespace Herecsrymy\AdminModule\Presenters;

use Herecsrymy\AdminModule\Components\LoginForm\ILoginFormControlFactory;
use Nette\Application\UI\Presenter;


class SignPresenter extends Presenter
{

	use TAdminPresenter;


	protected function createComponentLoginForm(ILoginFormControlFactory $factory)
	{
		$control = $factory->create();
		$control->onLogin[] = function () {
			$this->redirect('Dashboard:');
		};

		return $control;
	}


	public function handleOut()
	{
		$this->getUser()->logout(TRUE);
		$this->redirect('this');
	}

}
