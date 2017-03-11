export default class CalendarExtension {
	constructor(naja) {
		naja.addEventListener('load', this.loadCalendar.bind(this));
	}

	loadCalendar() {
		const el = document.getElementById('calendar');
		if ( ! el) {
			return;
		}

		if ( ! window.loadCalendar) {
			this.installCalendar(el);

		} else {
			window.loadCalendar();
		}
	}

	installCalendar(el) {
		// stylesheet
		const link = document.createElement('link');
		link.rel = 'stylesheet';
		link.href = el.getAttribute('data-style-href');
		document.head.appendChild(link);

		// script
		const script = document.createElement('script');
		script.src = el.getAttribute('data-script-src');
		script.id = 'calendar-js';
		document.head.appendChild(script);
	}
}
