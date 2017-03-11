export default class DisqusExtension {
	constructor(naja, {shortname}) {
		naja.addEventListener('load', () => {
			const thread = document.getElementById('disqus_thread');
			if (thread) {
				this.reload(thread);
			}
		});

		this.shortname = shortname;
	}

	/** @see https://help.disqus.com/customer/portal/articles/472107-using-disqus-on-ajax-sites */
	reload(el) {
		if ( ! window.DISQUS) {
			return this.load(el);
		}

		const url = el.getAttribute('data-url') || window.location.href;
		const identifier = el.getAttribute('data-id');
		const title = el.getAttribute('data-title');

		window.DISQUS.reset({
			reload: true,
			config: function () {
				this.page.url = url;
				this.page.identifier = identifier;
				this.page.title = title;
			}
		});
	}

	load(el) {
		const url = el.getAttribute('data-url') || window.location.href;
		const identifier = el.getAttribute('data-id');
		const title = el.getAttribute('data-title');

		window.disqus_config = function () {
			this.page.url = url;
			this.page.identifier = identifier;
			this.page.title = title;
		};

		const script = document.createElement('script');
		script.src = `https://${this.shortname}.disqus.com/embed.js`;
		script.setAttribute('data-timestamp', +new Date());
		(document.head || document.body).appendChild(script);
	}
}
