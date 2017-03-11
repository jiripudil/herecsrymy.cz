// playlist control

export const TOGGLE_PLAYLIST = 'TOGGLE_PLAYLIST';
export const togglePlaylist = () => ({type: TOGGLE_PLAYLIST});

export const CHANGE_SONG = 'CHANGE_SONG';
export function changeSong(song) {
	return {
		type: CHANGE_SONG,
		song,
	}
}


// playback control

export const PLAYBACK = 'PLAYBACK';
export const play = () => ({type: PLAYBACK, loading: false, playing: true});
export const pause = () => ({type: PLAYBACK, loading: false, playing: false});
export const loadSong = () => ({type: PLAYBACK, loading: true, playing: false});
export const markLoadedSong = () => ({type: PLAYBACK, loading: false, playing: true});

export const UPDATE_SEEK = 'UPDATE_SEEK';
export const updateSeek = seek => ({type: UPDATE_SEEK, seek});

export const CHANGE_VOLUME = 'CHANGE_VOLUME';
export function changeVolume(volume) {
	return {
		type: CHANGE_VOLUME,
		volume,
	};
}
