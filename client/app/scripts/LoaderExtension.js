export default class LoaderExtension {
	constructor(naja, {selector}) {
		naja.addEventListener('init', () => {
			this.loader = document.querySelector(selector);
		});

		naja.addEventListener('start', this.showLoader.bind(this));
		naja.addEventListener('complete', this.hideLoader.bind(this));
	}

	showLoader() {
		this.loader.style.display = 'block';
	}

	hideLoader() {
		this.loader.style.display = 'none';
	}
}
