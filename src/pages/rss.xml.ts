import rss from '@astrojs/rss';
import type {APIContext} from 'astro';
import {getCollection} from 'astro:content';

export async function get(context: APIContext) {
	const posts = await getCollection('posts');
	return rss({
		title: 'Jiří Pudil, brněnský herec s rýmy',
		description: 'Tvorba',
		site: context.site!.href,
		items: posts.map((post) => ({
			title: post.data.title,
			description: post.data.tagline,
			pubDate: post.data.publishedAt,
			link: `/tvorba/${post.slug}`,
		})),
	});
}
