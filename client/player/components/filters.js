export function playtime(seconds) {
	return Math.floor(seconds / 60) + ':' + ("00" + Math.floor(seconds % 60)).slice(-2);
}
