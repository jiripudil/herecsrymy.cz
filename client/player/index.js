import '../common';

import React from 'react';
import {render} from 'react-dom';
import {Provider} from 'react-redux';
import {createStore} from 'redux';

import Player from './components/Player';
import reducer from './model/reducer';

import './styles/player.scss';


function loadPlayer() {
	const mountEl = document.getElementById('audio-player');

	const songs = JSON.parse(mountEl.getAttribute('data-songs'));
	const initialState = {
		songs: songs.slice(),
		currentSong: songs.shift(), // {id: int, src: string[], title: string, playtime: int}
		playback: {
			loading: false,
			playing: false,
			seek: 0,
		},
		volume: 0.75,
		showPlaylist: false,
	};

	const store = createStore(reducer, initialState);

	render(
		<Provider store={store}>
			<Player />
		</Provider>,
		mountEl
	);
}

if (document.getElementById('audio-player')) {
	loadPlayer();

} else {
	document.addEventListener('DOMContentLoaded', loadPlayer);
}
