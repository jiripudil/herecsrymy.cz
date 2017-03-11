export default class CookiesExtension {
	constructor(naja) {
		naja.addEventListener('init', () => {
			document.body.addEventListener('click', evt => {
				if (evt.target.classList.contains('cookieLaw-ok')) {
					const date = new Date();
					date.setFullYear(date.getFullYear() + 10);
					document.cookie = 'cookies-allowed=1;path=/;expires=' + date.toUTCString() + ';secure';

					evt.target.parentNode.style.display = 'none';
					evt.preventDefault();
				}
			});
		});
	}
}
