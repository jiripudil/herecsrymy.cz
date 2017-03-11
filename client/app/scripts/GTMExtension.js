export default class GTMExtension {
	constructor(naja, {id}) {
		naja.addEventListener('init', () => {
			window['dataLayer'] = window['dataLayer'] || [];
			window['dataLayer'].push({
				'gtm.start': new Date().getTime(),
				event: 'gtm.js',
			});

			const script = document.createElement('script');
			script.async = true;
			script.src = `https://www.googletagmanager.com/gtm.js?id=${id}`;

			const firstScript = document.getElementsByTagName('script')[0];
			firstScript.parentNode.insertBefore(script, firstScript);
		});
	}
}
