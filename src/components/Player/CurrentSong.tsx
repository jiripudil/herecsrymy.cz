import type {Howl} from 'howler';
import {useEffect, useRef, useState} from 'react';
import type {AudioMetadata} from './types';
import {playtime} from './utils';
import styles from './Player.module.scss';

interface CurrentSongProps {
	readonly currentSong: AudioMetadata;
	readonly howl: Howl;
}

export default function CurrentSong({currentSong, howl}: CurrentSongProps) {
	const [seek, setSeek] = useState(0);
	const currentSongRef = useRef(currentSong);
	useEffect(() => {
		currentSongRef.current = currentSong;
	}, [currentSong]);

	useEffect(() => {
		let lastFrame = +new Date();
		const updateSeek = () => {
			handle = requestAnimationFrame(updateSeek);

			const now = +new Date();
			const delta = now - lastFrame;

			if (delta > 200) {
				setSeek(howl?.seek() ?? 0);
				lastFrame = now;
			}
		};

		let handle = requestAnimationFrame(updateSeek);
		return () => cancelAnimationFrame(handle);
	}, [howl]);

	return (
		<div className={styles.currentSong}>
			<span className={styles.songName}>{currentSong.title}</span>
			<span className={styles.songDuration}>
				<span className={styles.playback}>{playtime(seek)}</span>
				{playtime(currentSong.playtime)}
			</span>
		</div>
	);
};
