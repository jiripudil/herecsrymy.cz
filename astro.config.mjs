import image from '@astrojs/image';
import react from '@astrojs/react';
import {defineConfig} from 'astro/config';
import {remarkChords} from './src/utils/remarkChords.mjs';

// https://astro.build/config
export default defineConfig({
	site: 'https://herecsrymy.cz',
	integrations: [
		image(),
		react(),
	],
	markdown: {
		remarkPlugins: [
			remarkChords,
		],
	},
});
