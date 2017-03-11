export default class PostsFilterExtension {
	constructor(naja) {
		const listener = evt => evt.target.form.dispatchEvent(new Event('submit', {
			bubbles: true,
			cancelable: true,
		}));

		naja.addEventListener('load', () => {
			document.querySelectorAll('.category-filter').forEach(el => {
				el.removeEventListener('change', listener);
				el.addEventListener('change', listener);
			});
		});
	}
}
