import Raven from 'raven-js';


if (process.env.NODE_ENV === 'production') {
	Raven.config('https://607b4b82954347ef93f0bd38cb087754@sentry.io/134350').install();
}
