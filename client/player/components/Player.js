import React, {Component} from 'react';
import {connect} from 'react-redux';
import {Howl} from 'howler';

import * as ui from './filters';
import * as actions from '../model/actions';
import * as selectors from '../model/selectors';


class Player extends Component {
	constructor(props) {
		super(props);

		this.howls = {};
		for (let i in props.songs) {
			const song = props.songs[i];
			this.howls[song.id] = new Howl({
				src: song.src,
				volume: props.volume,
				onend: this.playNext.bind(this),

				preload: false,
				onload: () => this.props.dispatch(actions.markLoadedSong()),
				onplay: () => this.props.dispatch(actions.play()),
				onpause: () => this.props.dispatch(actions.pause()),
				onend: () => this.playNext(),
			});
		}

		this.setupSeekTimer();
		this.setupPlayButtonHandler();
	}

	setupSeekTimer() {
		let lastFrame = +new Date;
		const updateSeek = () => {
			const now = +new Date;
			const delta = now - lastFrame;

			if (delta > 200) {
				const howl = this.howls[this.props.currentSong.id];
				if (!!howl && howl.state() === 'loaded') {
					const seek = howl.seek();
					if (seek !== this.props.playback.seek) {
						this.props.dispatch(actions.updateSeek(seek));
					}
				}

				lastFrame = now;
			}

			requestAnimationFrame(updateSeek);
		};

		requestAnimationFrame(updateSeek);
	}

	setupPlayButtonHandler() {
		document.addEventListener('click', evt => {
			if (!!evt.target.parentElement && evt.target.parentElement.classList.contains('post-song-play')) {
				const id = evt.target.parentElement.getAttribute('data-song-id');
				const song = this.props.songs.find(song => song.id == id);
				this.changeSong(song, evt);
			}
		});
	}

	componentWillUpdate(nextProps) {
		// changing volume
		if (nextProps.volume !== this.props.volume) {
			this.howls[nextProps.currentSong.id].volume(nextProps.volume);
		}
	}

	playSong(song) {
		const howl = this.howls[song.id];
		if (howl.state() === 'unloaded') {
			this.props.dispatch(actions.loadSong());
			howl.load();
		}

		howl.play();
	}

	render() {
		let playButtonClass;
		if (this.props.playback.loading) {
			playButtonClass = 'fa-circle-o-notch fa-spin';
		} else if (this.props.playback.playing) {
			playButtonClass = 'fa-pause-circle';
		} else {
			playButtonClass = 'fa-play-circle';
		}

		return (
			<div className="player-wrapper">
				<div className="player">
					<div className="player-controls">
						<span className={`previous ${!this.props.canPlayPrevious ? 'disabled' : ''} fa fa-step-backward`} onClick={this.playPrevious.bind(this)} />
						<span className={`play fa ${playButtonClass} fa-fw`} onClick={this.togglePlay.bind(this)} />
						<span className={`next ${!this.props.canPlayNext ? 'disabled' : ''} fa fa-step-forward`} onClick={this.playNext.bind(this)} />
					</div>
					<div className="player-volume">
						<input type="range" min="0" max="1" step="0.05" title="Hlasitost"
							value={this.props.volume}
							onChange={this.changeVolume.bind(this)}
							/>
					</div>
					<div className="player-current-song">
						<span className="song-name">{this.props.currentSong.title}</span>
						<span className="song-duration">
							<span className="song-playback">{ui.playtime(this.props.playback.seek)}</span>
							{ui.playtime(this.props.currentSong.playtime)}
						</span>
					</div>
					<div className="player-playlist-toggle">
						<span className={['fa', 'fa-list-ul', this.props.showPlaylist ? 'toggle-active' : ''].join(' ')}
							onClick={this.togglePlaylist.bind(this)}
							/>
					</div>
				</div>
				<div className={['player-playlist', !this.props.showPlaylist ? 'player-playlist--hidden' : ''].join(' ')}>
					{this.props.songs.map(song => <div key={song.id} className={['playlist-song', this.props.currentSong === song ? 'playlist-song--active' : ''].join(' ')}>
						<a href="#" onClick={this.changeSong.bind(this, song)}>
							<span className="song-name">{song.title}</span>
							<span className="song-duration">{ui.playtime(song.playtime)}</span>
						</a>
					</div>)}
				</div>
			</div>
		);
	}

	togglePlaylist(evt) {
		evt.preventDefault();
		this.props.dispatch(actions.togglePlaylist());
	}

	changeSong(song, evt) {
		if (evt) {
			evt.preventDefault();
		}

		this.howls[this.props.currentSong.id].stop();
		this.props.dispatch(actions.updateSeek(0));
		this.props.dispatch(actions.changeSong(song));
		this.playSong(song);
	}

	playPrevious(evt) {
		if (!this.props.canPlayPrevious) {
			return;
		}

		const index = this.props.songs.indexOf(this.props.currentSong) - 1;
		this.changeSong(this.props.songs[index], evt);
	}

	playNext(evt) {
		if (!this.props.canPlayNext) {
			return;
		}

		const index = this.props.songs.indexOf(this.props.currentSong) + 1;
		this.changeSong(this.props.songs[index], evt);
	}

	togglePlay(evt) {
		evt.preventDefault();

		if (this.props.playback.loading || this.props.playback.playing) {
			this.howls[this.props.currentSong.id].pause();

		} else {
			this.playSong(this.props.currentSong);
		}
	}

	changeVolume(evt) {
		this.props.dispatch(actions.changeVolume(evt.target.value));
	}
}


export default connect(state => ({
	volume: state.volume,
	playback: state.playback,
	songs: state.songs,
	currentSong: state.currentSong,
	canPlayPrevious: selectors.canPlayPrevious(state),
	canPlayNext: selectors.canPlayNext(state),
	showPlaylist: state.showPlaylist,
}))(Player);
