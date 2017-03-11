var webpack = require('webpack');
var ExtractTextWebpackPlugin = require('extract-text-webpack-plugin');
var WebpackManifestPlugin = require('webpack-manifest-plugin');
var WebpackCleanupPlugin = require('webpack-cleanup-plugin');
var autoprefixer = require('autoprefixer');
var path = require('path');


module.exports = function (env) {
	var isProduction = env === 'production';

	var config = {
		devtool: isProduction ? 'source-map' : 'eval',
		entry: {
			admin: [
				'babel-polyfill',
				'./client/admin/index'
			],
			app: [
				'babel-polyfill',
				'./client/app/index'
			],
			calendar: [
				'babel-polyfill',
				'./client/calendar/index'
			],
			player: [
				'babel-polyfill',
				'./client/player/index'
			]
		},
		output: {
			path: path.join(__dirname, 'www/dist'),
			publicPath: isProduction ? '/dist/' : 'http://localhost:3000/',
			filename: '[name].[hash].js'
		},
		module: {
			rules: [
				{
					test: /\.js$/,
					exclude: /node_modules/,
					loader: 'babel-loader'
				},
				{
					test: /\.css$/,
					loader: ExtractTextWebpackPlugin.extract({
						use: 'css-loader?importLoaders=1!postcss-loader'
					})
				},
				{
					test: /\.scss$/,
					loader: ExtractTextWebpackPlugin.extract({
						use: 'css-loader?importLoaders=2!postcss-loader!sass-loader'
					})
				},
				{
					test: /\.less$/,
					loader: ExtractTextWebpackPlugin.extract({
						use: 'css-loader?importLoaders=2!postcss-loader!less-loader'
					})
				},
				{
					test: /\.gif(\?.*)?$/,
					use: [
						{
							loader: 'url-loader',
							options: {
								limit: 5000,
								mimetype: 'image/gif'
							}
						}
					],
				},
				{
					test: /\.png(\?.*)?$/,
					use: [
						{
							loader: 'url-loader',
							options: {
								limit: 5000,
								mimetype: 'image/png'
							}
						}
					],
				},
				{
					test: /\.jpe?g(\?.*)?$/,
					loader: 'file-loader'
				},
				{
					test: /\.woff2?(\?.*)?$/,
					loader: 'file-loader'
				},
				{
					test: /\.(ttf|eot|svg)(\?.*)?$/,
					loader: 'file-loader'
				}
			]
		},
		plugins: [
			new webpack.DefinePlugin({
				'process.env': {
					NODE_ENV: JSON.stringify(env),
				},
			}),
			new webpack.ProvidePlugin({
				'window.jQuery': 'jquery',
				$: 'jquery'
			}),
			new ExtractTextWebpackPlugin({
				filename: '[name].[hash].css'
			}),
			new webpack.LoaderOptionsPlugin({
				options: {
					context: __dirname,
					debug: ! isProduction
				}
			}),
			new webpack.ContextReplacementPlugin(/moment[\/\\]locale$/, /cs/), // https://stackoverflow.com/questions/25384360/how-to-prevent-moment-js-from-loading-locales-with-webpack
			new WebpackManifestPlugin(),
			new WebpackCleanupPlugin({
				exclude: ['admin.js', 'admin.css', 'app.js', 'app.css', 'calendar.js', 'calendar.css', 'common.js', 'player.js', 'player.css']
			}),
			new webpack.optimize.CommonsChunkPlugin('common')
		]
	};

	if (isProduction) {
		config.plugins = config.plugins.concat(
			new webpack.optimize.UglifyJsPlugin({
				compress: {
					unused: true,
					dead_code: true,
					warnings: false,
				},
				sourceMap: true,
				comments: /$./,
			})
		);
	}

	return config;
};
