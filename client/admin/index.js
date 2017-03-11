import '../common';

import 'jquery';
import '../../vendor/vojtech-dobes/nette-forms-gpspicker/client/nette.gpsPicker.js';
import Nette from '../../vendor/nette/forms/src/assets/netteForms';
import './styles/admin.less';

Nette.initOnLoad();

document.addEventListener('DOMContentLoaded', () => {
	// confirm message
	document.querySelectorAll('[data-confirm]').forEach(element => element.addEventListener('click', evt => {
		const message = evt.target.getAttribute('data-confirm');
		if ( ! window.confirm(message)) {
			evt.preventDefault();
		}
	}));

	// location gpspicker
	window.jQuery('[data-nette-gpspicker]').gpspicker();
});
