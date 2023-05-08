import {findAndReplace} from 'mdast-util-find-and-replace';

const RE_CHORD = /{[A-G][b#♭♯]*(?:M|Δ|[Mm]aj|m|[Mm]in|-|–|[Dd]im|o|°|ø|[Aa]ug|\+|[Ss]usp?|[Aa]dd)?(?:1?[\d])?(?:(?:[(\/,]?(?:[-–+Δob#♭♯]|[Mm]aj|[Mm]in|[Ss]usp?)?[0-9]+\)?)*)?(?:\/[A-G][b#♭♯]*)?}/g;
export const remarkChords = () => {
	const replace = (match) => {
		const chord = match
			.substring(1, match.length - 1)
			.replace(/b/g, '♭')
			.replace(/#/g, '♯')
			.replace(/o/g, '°')
			.replace(/[-–]/g, '‑');

		return {
			type: 'ChordNode',
			value: chord,
			data: {
				hName: 'span',
				hProperties: {className: 'chord'},
				hChildren: [{type: 'text', value: chord}],
			},
		};
	};

	return (ast) => {
		findAndReplace(ast, RE_CHORD, replace);
		return ast;
	};
};
