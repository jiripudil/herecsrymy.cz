<?php

namespace Herecsrymy\FrontModule\Components\Disqus;

use Herecsrymy\Application\UI\TBaseControl;
use Nette\Application\UI\Control;


class DisqusControl extends Control
{

	use TBaseControl;


	/** @var string */
	private $shortname;

	/** @var string|NULL */
	private $identifier;

	/** @var string|NULL */
	private $title;

	/** @var string|NULL */
	private $url;


	public function __construct($shortname, $identifier = NULL, $title = NULL, $url = NULL)
	{
		$this->shortname = $shortname;
		$this->identifier = $identifier;
		$this->title = $title;
		$this->url = $url;
	}


	public function render()
	{
		$this->template->shortname = $this->shortname;
		$this->template->identifier = $this->identifier;
		$this->template->title = $this->title;
		$this->template->url = $this->url;
		$this->template->render(__DIR__ . '/DisqusControl.latte');
	}

}
