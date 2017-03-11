<?php

namespace Herecsrymy\FrontModule\Components\Disqus;

use Herecsrymy\Application\UI\TBaseControl;
use Nette\Application\UI\Control;


class DisqusControl extends Control
{

	use TBaseControl;


	/** @var int */
	private $identifier;

	/** @var string */
	private $title;

	/** @var string */
	private $url;


	public function __construct(int $identifier, string $title, string $url)
	{
		parent::__construct();
		$this->identifier = $identifier;
		$this->title = $title;
		$this->url = $url;
	}


	public function render()
	{
		$this->template->identifier = $this->identifier;
		$this->template->title = $this->title;
		$this->template->url = $this->url;
		$this->template->render(__DIR__ . '/DisqusControl.latte');
	}

}
