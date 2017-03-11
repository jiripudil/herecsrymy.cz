import '../common';

import React from 'react';
import {render} from 'react-dom';

import Calendar from './components/Calendar';
import './styles/calendar.scss';


window.loadCalendar = () => {
	const mountEl = document.getElementById('calendar');
	if ( ! mountEl) {
		return;
	}

	const eventDates = JSON.parse(mountEl.getAttribute('data-eventDates'));
	render(<Calendar eventDates={eventDates.slice()} />, mountEl);
};

if (document.getElementById('calendar')) {
	window.loadCalendar();

} else {
	document.addEventListener('DOMContentLoaded', window.loadCalendar);
}
