import type {AudioMetadata} from './types';
import {playtime} from './utils';
import styles from './Player.module.scss';

interface PlaylistProps {
	readonly expanded: boolean;
	readonly songs: readonly AudioMetadata[];
	readonly currentSongIndex: number;
	readonly onSelectSong: (index: number) => void;
}

export default function Playlist({expanded, songs, currentSongIndex, onSelectSong}: PlaylistProps) {
	return (
		<div className={`${styles.playlist} ${!expanded ? styles.hiddenPlaylist : ''}`}>
			{songs.map((song, index) => (
				<div
					key={song.src}
					className={`${styles.song} ${currentSongIndex === index ? styles.activeSong : ''}`}
					onClick={() => onSelectSong(index)}
				>
					<span className={styles.songName}>{song.title}</span>
					<span className={styles.songDuration}>{playtime(song.playtime)}</span>
				</div>
			))}
		</div>
	);
};
