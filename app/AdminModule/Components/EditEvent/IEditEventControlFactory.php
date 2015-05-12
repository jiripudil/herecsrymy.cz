<?php

namespace Herecsrymy\AdminModule\Components\EditEvent;

use Herecsrymy\Entities\Event;


interface IEditEventControlFactory
{
	/**
	 * @param Event $event
	 * @return EditEventControl
	 */
	function create(Event $event);
}
