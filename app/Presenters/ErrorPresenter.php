<?php

namespace Herecsrymy\Presenters;

use Nette\Application\BadRequestException;
use Nette\Application\UI\Presenter;
use Herecsrymy\Application\UI\TBasePresenter;
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
			$this->getHttpResponse()->setCode($code);
			$this->setView(in_array($code, [403, 404, 410, 500]) ? $code : '4xx');
			$this->template->httpCode = $code;

			Debugger::log("HTTP code $code: {$exception->getMessage()} in {$exception->getFile()}:{$exception->getLine()}", 'access');

		} else {
			$this->getHttpResponse()->setCode(500);
			$this->setView('500');

			Debugger::log($exception, Debugger::ERROR);
		}
	}

}
