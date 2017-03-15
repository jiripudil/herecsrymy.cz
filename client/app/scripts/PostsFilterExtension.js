export default class PostsFilterExtension {
	constructor(naja) {
		const listener = evt => evt.target.form.dispatchEvent(new Event('submit', {
			bubbles: true,
			cancelable: true,
		}));

		naja.addEventListener('load', () => {
			const elements = document.querySelectorAll('.category-filter');
			for (let i = 0; i < elements.length; i++) {
				const el = elements.item(i);
				el.removeEventListener('change', listener);
				el.addEventListener('change', listener);
			}
		});
	}
}
