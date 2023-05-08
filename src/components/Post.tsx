import {faCalendarDay} from '@fortawesome/free-solid-svg-icons';
import {FontAwesomeIcon} from '@fortawesome/react-fontawesome';
import type {CollectionEntry} from 'astro:content';
import {getCategory} from '../data/categories';
import FormattedDate from './FormattedDate';
import styles from './Post.module.css';

interface Props {
	readonly post: CollectionEntry<'posts'>;
}

export default function Post({post}: Props) {
	const category = getCategory(post.data.category);
	return (
		<div className={styles.post}>
			<div className={styles.postTitle}>
				<a href={`/tvorba/${post.slug}`}>
					{post.data.title}
				</a>
			</div>
			<div>{post.data.tagline}</div>
			<div className={styles.postCategory}>
				<FontAwesomeIcon icon={category.icon} />
				{category.name}
			</div>
			<div className={styles.postPublished}>
				<FontAwesomeIcon icon={faCalendarDay} />
				<FormattedDate date={post.data.publishedAt} />
			</div>
		</div>
	);
}
