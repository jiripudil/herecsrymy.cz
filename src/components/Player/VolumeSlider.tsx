import styles from './Player.module.scss';

interface VolumeSliderProps {
	readonly volume: number;
	readonly onChange: (volume: number) => void;
}

export default function VolumeSlider({volume, onChange}: VolumeSliderProps) {
	return (
		<div className={styles.volume}>
			<input
				type="range"
				min="0"
				max="1"
				step="0.05"
				title="Hlasitost"
				value={volume}
				onChange={(event) => onChange(Number(event.target.value))}
			/>
		</div>
	);
};
