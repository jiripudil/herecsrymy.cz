<?php

namespace Herecsrymy\FrontModule\Components\Paging;

use Nette\Application\UI\Control;
use Nette\Application\UI\Presenter;
use Nette\Utils\Paginator;
use Herecsrymy\Application\UI\TBaseControl;


class PagingControl extends Control
{

	use TBaseControl;


	/** @var int @persistent */
	public $page = 1;

	/** @var Paginator */
	private $paginator;


	public function __construct()
	{
		$this->paginator = new Paginator();
	}


	protected function attached($presenter)
	{
		parent::attached($presenter);

		if ($presenter instanceof Presenter) {
			$this->paginator->setPage($this->page);
		}
	}


	public function reset()
	{
		$this->page = 1;
		$this->paginator->setPage(1);
	}


	/**
	 * @return Paginator
	 */
	public function getPaginator()
	{
		return $this->paginator;
	}


	public function render()
	{
		$this->template->paginator = $this->paginator;
		$this->template->page = $this->page;
		$this->template->render(__DIR__ . '/PagingControl.latte');
	}

}
