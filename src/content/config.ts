import { defineCollection, z } from 'astro:content';
import {Category} from '../data/categories';

const posts = defineCollection({
	schema: z.object({
		title: z.string(),
		tagline: z.string(),
		publishedAt: z.string().transform((val) => new Date(val)),
		category: z.nativeEnum(Category),
		audio: z.boolean().default(false),
	}),
});

export const collections = { posts };
