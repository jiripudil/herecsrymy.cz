export default class FacebookExtension {
	constructor(naja, {appId, locale, version}) {
		naja.addEventListener('load', () => {
			document.querySelectorAll('[class^="fb-"]').forEach(el => {
				this.reload(el);
			});
		});

		this.appId = appId;
		this.locale = locale;
		this.version = version;
	}

	/** @see https://developers.facebook.com/docs/reference/javascript/FB.XFBML.parse */
	reload(el) {
		if ( ! window.FB) {
			return this.load();
		}

		window.FB.XFBML.parse(el.parentNode);
	}

	load() {
		const tagName = 'script';
		const id = 'facebook-jssdk';

		let js, fjs = document.getElementsByTagName(tagName)[0];
		if (document.getElementById(id)) return;
		js = document.createElement(tagName);
		js.src = `//connect.facebook.net/${this.locale}/sdk.js#xfbml=1&appId=${this.appId}&version=v${this.version}`;
		js.id = id;

		fjs.parentNode.insertBefore(js, fjs);
	}
}
