import type {CollectionEntry} from 'astro:content';
import {useMemo, useState} from 'react';
import {Category} from '../data/categories';
import CategoryFilter from './CategoryFilter';
import Post from './Post';
import styles from './PostsListWithFilter.module.css';

interface Props {
	readonly posts: CollectionEntry<'posts'>[];
}

export default function PostsListWithFilter({posts}: Props) {
	const [selectedCategories, setSelectedCategories] = useState([Category.poem, Category.song, Category.essay]);
	const filteredPosts = useMemo(() => posts.filter((post) => selectedCategories.includes(post.data.category)), [posts, selectedCategories]);

	return (
		<>
			<div className={styles.container}>
				<div className={styles.posts}>
					{filteredPosts.map((post) => <Post key={post.slug} post={post} />)}
				</div>

				<div className={styles.filter}>
					<h2>Zobrazit</h2>
					<CategoryFilter
						value={selectedCategories}
						onChange={setSelectedCategories}
					/>
				</div>
			</div>
		</>
	);
}
