import * as actions from './actions';

export default (state, action) => {
	if (action.type === actions.TOGGLE_PLAYLIST) {
		return Object.assign({}, state, {
			showPlaylist: !state.showPlaylist,
		});
	}

	if (action.type === actions.CHANGE_SONG) {
		return Object.assign({}, state, {
			currentSong: action.song,
		});
	}

	if (action.type === actions.PLAYBACK) {
		return Object.assign({}, state, {
			playback: Object.assign({}, state.playback, {
				loading: action.loading,
				playing: action.playing,
			}),
		});
	}

	if (action.type === actions.UPDATE_SEEK) {
		return Object.assign({}, state, {
			playback: Object.assign({}, state.playback, {
				seek: action.seek,
			}),
		});
	}

	if (action.type === actions.CHANGE_VOLUME) {
		return Object.assign({}, state, {
			volume: action.volume,
		});
	}

	return state;
};
