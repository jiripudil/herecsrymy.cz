export default class TwitterExtension {
	constructor(naja) {
		naja.addEventListener('load', () => {
			document.querySelectorAll('[class^="twitter-"]').forEach(el => {
				this.reload(el);
			});
		});
	}

	/** @see https://dev.twitter.com/web/javascript/initialization */
	reload(el) {
		if ( ! window.twttr) {
			return this.load();
		}

		window.twttr.widgets.load(el.parentNode);
	}

	load() {
		window.twttr = (function (d, s, id) {
			let js, fjs = d.getElementsByTagName(s)[0], t = window.twttr || {};
			if (d.getElementById(id)) return t;
			js = d.createElement(s);
			js.src = "https://platform.twitter.com/widgets.js";
			js.id = id;
			fjs.parentNode.insertBefore(js, fjs);

			t._e = [];
			t.ready = function (f) {
				t._e.push(f);
			};

			return t;
		}(document, "script", "twitter-wjs"));
	}
}
