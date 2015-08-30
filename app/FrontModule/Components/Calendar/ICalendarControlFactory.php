<?php

namespace Herecsrymy\FrontModule\Components\Calendar;


interface ICalendarControlFactory
{
	/** @return CalendarControl */
	function create();
}
