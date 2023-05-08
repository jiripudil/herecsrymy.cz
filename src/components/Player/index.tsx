import {faListUl} from '@fortawesome/free-solid-svg-icons';
import {FontAwesomeIcon} from '@fortawesome/react-fontawesome';
import {Howl} from 'howler';
import {useCallback, useEffect, useMemo, useRef, useState} from 'react';
import {PlaybackStatus, type AudioMetadata} from './types';
import Controls from './Controls';
import VolumeSlider from './VolumeSlider';
import CurrentSong from './CurrentSong';
import Playlist from './Playlist';
import styles from './Player.module.scss';

interface PlayerProps {
	readonly songs: AudioMetadata[];
}

export default function Player({songs}: PlayerProps) {
	const [currentSongIndex, setCurrentSongIndex] = useState(0);
	const [playbackStatus, setPlaybackStatus] = useState(PlaybackStatus.PAUSED);
	const [volume, setVolume] = useState(1);

	const [howl, setHowl] = useState<Howl>();
	const createHowl = useCallback((song: AudioMetadata) => new Howl({
		src: song.src,
		volume,

		html5: true,
		preload: false,
		onload: () => setPlaybackStatus(PlaybackStatus.PLAYING),
		onplay: () => setPlaybackStatus(PlaybackStatus.PLAYING),
		onpause: () => setPlaybackStatus(PlaybackStatus.PAUSED),
		onend: () => playNextRef.current(),
	}), [volume]);

	useEffect(() => {
		howl?.volume(volume);
	}, [volume, howl]);

	const changeSong = useCallback((index: number) => {
		howl?.stop();

		setCurrentSongIndex(index);
		setPlaybackStatus(PlaybackStatus.LOADING);

		const newHowl = createHowl(songs[index]!);
		setHowl(newHowl);
		newHowl.load();
		newHowl.play();
	}, [songs, howl, createHowl]);

	const playPause = useCallback(() => {
		if (howl === undefined) {
			changeSong(currentSongIndex);
			return;
		}

		if (playbackStatus === PlaybackStatus.PLAYING) {
			howl.pause();
			return;
		}

		howl.play();
	}, [howl, playbackStatus, changeSong, currentSongIndex]);

	const canPlayPrevious = useMemo(() => currentSongIndex > 0, [currentSongIndex]);
	const playPrevious = useCallback(() => {
		if ( ! canPlayPrevious) { return; }
		changeSong(currentSongIndex - 1);
	}, [canPlayPrevious, currentSongIndex, changeSong]);

	const canPlayNext = useMemo(() => currentSongIndex < (songs.length - 1), [songs, currentSongIndex]);
	const playNext = useCallback(() => {
		if ( ! canPlayNext) { return; }
		changeSong(currentSongIndex + 1);
	}, [canPlayNext, currentSongIndex, changeSong]);

	const playNextRef = useRef(playNext);
	useEffect(() => { playNextRef.current = playNext; }, [playNext])

	const [showPlaylist, setShowPlaylist] = useState(false);
	const togglePlaylist = () => setShowPlaylist((value) => !value);

	return (
		<div className={styles.wrapper}>
			<div className={styles.player}>
				<Controls
					canPlayPrevious={canPlayPrevious}
					onPlayPrevious={() => playPrevious()}
					canPlayNext={canPlayNext}
					onPlayNext={() => playNext()}
					playbackStatus={playbackStatus}
					onPlayPause={() => playPause()}
				/>
				<VolumeSlider volume={volume} onChange={setVolume} />
				<CurrentSong currentSong={songs[currentSongIndex]!} howl={howl!} />
				<div className={styles.playlistToggle}>
					<FontAwesomeIcon
						icon={faListUl}
						className={showPlaylist ? styles.activePlaylistToggle! : ''}
						onClick={togglePlaylist}
					/>
				</div>
			</div>

			<Playlist
				expanded={showPlaylist}
				songs={songs}
				currentSongIndex={currentSongIndex}
				onSelectSong={changeSong}
			/>
		</div>
	);
};
