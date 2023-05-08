export const playtime = (seconds: number) => `${Math.floor(seconds / 60)}:${('00' + Math.floor(seconds % 60)).slice(-2)}`;
