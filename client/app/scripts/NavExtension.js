export default class NavExtension {
	constructor(naja) {
		naja.addEventListener('init', () => {
			this.toggle = document.getElementById('menu-toggle');
		});

		naja.addEventListener('complete', () => {
			this.toggle.checked = false;
		});
	}
}
