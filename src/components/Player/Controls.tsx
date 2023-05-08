import {
	faCircleNotch,
	faPauseCircle,
	faPlayCircle,
	faStepBackward,
	faStepForward
} from '@fortawesome/free-solid-svg-icons';
import {FontAwesomeIcon} from '@fortawesome/react-fontawesome';
import {useMemo} from 'react';
import {PlaybackStatus} from './types';
import styles from './Player.module.scss';

interface ControlsProps {
	readonly canPlayPrevious: boolean;
	readonly onPlayPrevious: () => void;
	readonly canPlayNext: boolean;
	readonly onPlayNext: () => void;
	readonly playbackStatus: PlaybackStatus;
	readonly onPlayPause: () => void;
}

export default function Controls(props: ControlsProps) {
	const playIcon = useMemo(() => props.playbackStatus === PlaybackStatus.PAUSED
		? faPlayCircle
		: (props.playbackStatus === PlaybackStatus.PLAYING
				? faPauseCircle
				: faCircleNotch
		), [props.playbackStatus]);

	return (
		<div className={styles.controls}>
			<FontAwesomeIcon
				icon={faStepBackward}
				className={`previous ${ ! props.canPlayPrevious ? styles.disabledControl : ''}`}
				onClick={() => props.onPlayPrevious()}
			/>
			<FontAwesomeIcon
				icon={playIcon}
				spin={props.playbackStatus === PlaybackStatus.LOADING}
				className={styles.play!}
				fixedWidth={true}
				onClick={() => props.onPlayPause()}
			/>
			<FontAwesomeIcon
				icon={faStepForward}
				className={`next ${ ! props.canPlayNext ? styles.disabledControl : ''}`}
				onClick={() => props.onPlayNext()}
			/>
		</div>
	);
};
