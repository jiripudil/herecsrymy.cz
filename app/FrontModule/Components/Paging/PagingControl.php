<?php

namespace Slovotepec\FrontModule\Components\Paging;

use Nette\Application\UI\Control;
use Nette\Application\UI\Presenter;
use Nette\Utils\Paginator;
use Slovotepec\Application\UI\TBaseControl;
use Slovotepec\FrontModule\Components\Head\HeadControl;


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


	/**
	 * @return Paginator
	 */
	public function getPaginator()
	{
		return $this->paginator;
	}


	public function addLinks(HeadControl $head)
	{
		if ( ! $this->paginator->first) {
			$head->addLink('prev', $this->link('this', ['page' => $this->page - 1]));
		}

		if ( ! $this->paginator->last) {
			$head->addLink('next', $this->link('this', ['page' => $this->page + 1]));
		}
	}


	public function render()
	{
		$this->template->paginator = $this->paginator;
		$this->template->page = $this->page;
		$this->template->render(__DIR__ . '/PagingControl.latte');
	}

}
