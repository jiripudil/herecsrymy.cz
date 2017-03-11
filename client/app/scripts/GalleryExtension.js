export default class GalleryExtension {
	constructor(naja) {
		const listener = evt => {
			evt.preventDefault();
			const url = evt.currentTarget.href;
			const description = evt.currentTarget.querySelector('img').alt;
			this.open(url, description);
		};

		naja.addEventListener('load', () => {
			document.querySelectorAll('.gallery a').forEach(el => {
				el.removeEventListener('click', listener);
				el.addEventListener('click', listener);
			});
		});
	}

	open(url, description) {
		const overlay = document.createElement('div');
		overlay.classList.add('ltbox-overlay');

		const image = document.createElement('div');
		image.classList.add('ltbox-image');
		overlay.appendChild(image);

		const img = document.createElement('img');
		img.src = url;
		img.alt = description;
		image.appendChild(img);

		if (description) {
			const descriptionEl = document.createElement('div');
			descriptionEl.classList.add('ltbox-description');
			descriptionEl.innerHTML = description;
			image.appendChild(descriptionEl);
		}

		overlay.keydownHandler = this.handleKeyDown.bind(this);
		overlay.addEventListener('click', this.close.bind(this));
		document.addEventListener('keydown', overlay.keydownHandler);

		this.instance = document.body.appendChild(overlay);
	}

	close() {
		document.removeEventListener('keydown', this.instance.keydownHandler);
		document.body.removeChild(this.instance);

		this.instance = null;
	}

	handleKeyDown(evt) {
		if (evt.key === 'Escape' && !(evt.ctrlKey || evt.shiftKey || evt.altKey || evt.metaKey)) {
			this.close();
		}
	}
}
