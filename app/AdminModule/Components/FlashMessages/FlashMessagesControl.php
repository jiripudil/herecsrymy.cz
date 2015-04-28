<?php

namespace Herecsrymy\AdminModule\Components\FlashMessages;

use Herecsrymy\Application\UI\TBaseControl;
use Nette\Application\UI\Control;


class FlashMessagesControl extends Control
{

	use TBaseControl;


	const SUCCESS = 'success';
	const ERROR = 'error';
	const INFO = 'info';


	public function render()
	{
		$this->template->render(__DIR__ . '/FlashMessagesControl.latte');
	}

}
