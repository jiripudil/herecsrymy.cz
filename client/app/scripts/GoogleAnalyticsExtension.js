/** @see https://mjau-mjau.com/blog/ajax-universal-analytics/ */
export default class GoogleAnalyticsExtension {
	constructor(naja) {
		naja.addEventListener('success', () => {
			if (window.ga) {
				window.ga('set', {page: window.location.pathname + window.location.search, title: document.title});
				window.ga('send', 'pageview');
			}
		});
	}
}
