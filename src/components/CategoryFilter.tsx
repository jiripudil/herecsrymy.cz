import {FontAwesomeIcon} from '@fortawesome/react-fontawesome';
import {categories} from '../data/categories';
import type {Category} from '../data/categories';
import styles from './CategoryFilter.module.css';

interface Props {
	readonly value: Category[];
	readonly onChange: (value: Category[]) => void;
}

export default function CategoryFilter({value, onChange}: Props) {
	return (
		<div>
			{Array.from(categories.values()).map((category) => (
				<label key={category.id} className={styles.filter}>
					<input
						type="checkbox"
						checked={value.includes(category.id)}
						onChange={(event) => {
							const {checked} = event.currentTarget;
							const newSelectedCategories = checked
								? [...value, category.id]
								: value.filter((queriedCategory) => queriedCategory !== category.id);

							onChange(newSelectedCategories);
						}}
					/>
					{' '}
					<FontAwesomeIcon icon={category.icon} />
					{' '}
					{category.name}
				</label>
			))}
		</div>
	);
}