import type {Howl} from 'howler';

export interface AudioMetadata {
	readonly src: string;
	readonly title: string;
	readonly playtime: number;
}

export interface Song {
	readonly metadata: AudioMetadata;
	readonly howl: Howl;
}

export enum PlaybackStatus {
	PAUSED,
	LOADING,
	PLAYING,
}
