<?php

namespace Herecsrymy\Presenters;

use Nette\Application\BadRequestException;
use Nette\Application\UI\Presenter;
use Herecsrymy\Application\UI\TBasePresenter;
use Herecsrymy\FrontModule\Components\Header\IHeaderControlFactory;
use Tracy\Debugger;


class ErrorPresenter extends Presenter
{

	use TBasePresenter;


	public function renderDefault(\Exception $exception)
	{
		if ($this->isAjax()) {
			$this->payload->error = TRUE;
			$this->terminate();

		} elseif ($exception instanceof BadRequestException) {
			$code = $exception->getCode();
			$this->setView(in_array($code, [403, 404, 500]) ? $code : '4xx');

			$this['head']->setTitle('Chyba - Jiří Pudil');

			Debugger::log("HTTP code $code: {$exception->getMessage()} in {$exception->getFile()}:{$exception->getLine()}", 'access');

		} else {
			$this->setView('500');

			$this['head']->addMeta('robots', 'noindex');
			$this['head']->setTitle('Chyba - Jiří Pudil');

			Debugger::log($exception, Debugger::ERROR);
		}
	}


	protected function createComponentHeader(IHeaderControlFactory $factory)
	{
		return $factory->create('small');
	}

}
