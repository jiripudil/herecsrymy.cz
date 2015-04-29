<?php

namespace Herecsrymy\FrontModule\Components\GoogleAnalytics;

use Herecsrymy\Application\UI\TBaseControl;
use Nette\Application\UI\Control;


class GoogleAnalyticsControl extends Control
{

	use TBaseControl;

	/** @var string */
	private $uaKey;

	/** @var bool */
	private $enabled;


	/**
	 * @param string $uaKey
	 * @param bool $enabled
	 */
	public function __construct($uaKey, $enabled = TRUE)
	{
		$this->uaKey = $uaKey;
		$this->enabled = $enabled;
	}


	public function render()
	{
		if ($this->enabled) {
			$this->template->uaKey = $this->uaKey;
			$this->template->render(__DIR__ . '/GoogleAnalyticsControl.latte');
		}
	}

}
