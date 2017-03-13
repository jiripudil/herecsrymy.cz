import '../common';

import naja from 'naja/dist/Naja';
import WebFont from 'webfontloader';

import LoaderExtension from './scripts/LoaderExtension';
import NavExtension from './scripts/NavExtension';
import FacebookExtension from './scripts/FacebookExtension';
import TwitterExtension from './scripts/TwitterExtension';
import DisqusExtension from './scripts/DisqusExtension';
import GalleryExtension from './scripts/GalleryExtension';
import CalendarExtension from './scripts/CalendarExtension';
import PostsFilterExtension from './scripts/PostsFilterExtension';
import GoogleAnalyticsExtension from './scripts/GoogleAnalyticsExtension';
import GTMExtension from './scripts/GTMExtension';
import CookiesExtension from './scripts/CookiesExtension';

import './styles/index.scss';


naja.registerExtension(LoaderExtension, {selector: '.loader'});
naja.registerExtension(NavExtension);
naja.registerExtension(GTMExtension, {id: 'GTM-WLM5XK'});
naja.registerExtension(FacebookExtension, {appId: '273296596032549', locale: 'cs_CZ', version: '2.8'});
naja.registerExtension(TwitterExtension);
naja.registerExtension(DisqusExtension, {shortname: 'herecsrymy'});
naja.registerExtension(GalleryExtension);
naja.registerExtension(CalendarExtension);
naja.registerExtension(PostsFilterExtension);
naja.registerExtension(GoogleAnalyticsExtension);
naja.registerExtension(CookiesExtension);

document.addEventListener('DOMContentLoaded', naja.initialize.bind(naja));

WebFont.load({
	timeout: 2000,
	google: {
		families: [
			'Merriweather:400,700:latin,latin-ext',
			'Source+Sans+Pro:400,700:latin,latin-ext'
		]
	}
});
