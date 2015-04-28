<?php

namespace Herecsrymy\AdminModule\Components\FlashMessages;


interface IFlashMessagesControlFactory
{
	/** @return FlashMessagesControl */
	function create();
}
