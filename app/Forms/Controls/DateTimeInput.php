<?php

namespace Herecsrymy\Forms\Controls;

use Nette\Application\UI\Form;
use Nette\Forms\Controls\BaseControl;
use Nette\Utils\DateTime;
use Nette\Utils\Html;


class DateTimeInput extends BaseControl
{

	/** @var \DateTimeInterface */
	private $date;


	public function setValue($value = NULL)
	{
		if ($value === NULL) {
			$this->date = NULL;

		} elseif ($value instanceof \DateTimeInterface) {
			$this->date = $value;

		} else {
			throw new \InvalidArgumentException("Value must be instanceof DateTimeInterface or NULL.");
		}

		return $this;
	}


	/**
	 * @return \DateTimeInterface
	 */
	public function getValue()
	{
		return $this->date;
	}


	public function loadHttpData()
	{
		$this->date = DateTime::from($this->getHttpData(Form::DATA_LINE));
	}


	/**
	 * @return Html
	 */
	public function getControl()
	{
		$control = Html::el('input', [
			'name' => $this->getHtmlName(),
			'value' => $this->date !== NULL ? $this->date->format('Y-m-d\TH:i') : NULL,
			'type' => 'datetime-local',
		]);

		if ($this->disabled) {
			$control->disabled($this->disabled);
		}

		return $control;
	}


	/**
	 * @return bool
	 */
	public function isFilled()
	{
		return $this->date !== NULL;
	}

}
