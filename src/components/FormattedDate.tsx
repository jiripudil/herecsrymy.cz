interface Props {
	readonly date: Date;
}

export default function FormattedDate({date}: Props) {
	return (
		<time dateTime={date.toISOString()}>
			{date.toLocaleDateString('cs-cz', {
				year: 'numeric',
				month: 'long',
				day: 'numeric',
			})}
		</time>
	);
}
