import type {IconDefinition} from '@fortawesome/fontawesome-svg-core';
import {faMusic, faScroll, faStream} from '@fortawesome/free-solid-svg-icons';

export enum Category {
	poem = 'poem',
	song = 'song',
	essay = 'essay',
}

export interface CategoryDefinition {
	readonly id: Category;
	readonly name: string;
	readonly icon: IconDefinition;
}

export const categories: Map<Category, CategoryDefinition> = new Map([
	[Category.poem, {
		id: Category.poem,
		name: 'Básničky',
		icon: faStream,
	}],
	[Category.song, {
		id: Category.song,
		name: 'Písničky',
		icon: faMusic,
	}],
	[Category.essay, {
		id: Category.essay,
		name: 'Eseje',
		icon: faScroll,
	}],
]);

export const getCategory = (id: Category) => categories.get(id)!;
