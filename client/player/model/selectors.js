export const canPlayPrevious = state => !!state.songs && !!state.currentSong && state.songs.indexOf(state.currentSong) > 0;
export const canPlayNext = state => !!state.songs && !!state.currentSong && state.songs.indexOf(state.currentSong) < state.songs.length - 1;
