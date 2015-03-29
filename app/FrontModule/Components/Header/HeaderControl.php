<?php

namespace Slovotepec\FrontModule\Components\Header;

use Nette\Application\UI\Control;
use Slovotepec\Application\UI\TBaseControl;


class HeaderControl extends Control
{

	use TBaseControl;


	/** @var string */
	private $view;


	/**
	 * @param string $view
	 */
	public function __construct($view = 'default')
	{
		$this->view = $view;
	}


	public function render()
	{
		$this->template->render(__DIR__ . '/HeaderControl.' . $this->view . '.latte');
	}

}
