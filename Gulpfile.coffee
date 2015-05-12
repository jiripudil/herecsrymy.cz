gulp = require 'gulp'
gutil = require 'gulp-util'
streamee = require 'streamee'
loadPlugins = require 'gulp-load-plugins'
plugins = loadPlugins()


gulp.task 'less', ->
	gulp.src 'www/static/css/*.less'
		.pipe plugins.plumber()
		.pipe plugins.sourcemaps.init()
		.pipe plugins.less
			paths: ['bower_components']
			cleancss: yes
		.on 'error', gutil.log
		.pipe plugins.autoprefixer
			browsers: ['last 2 versions', 'ie >= 8']
			cascade: no
		.on 'error', gutil.log
		.pipe plugins.sourcemaps.write '.'
		.pipe plugins.plumber.stop()
		.pipe gulp.dest 'www/static/css'


gulp.task 'scripts', ->
	streamee.concatenate [
		gulp.src([
			'bower_components/jquery/dist/jquery.min.js'
			'bower_components/nette-forms/src/assets/netteForms.js'
			'bower_components/nette.ajax.js/nette.ajax.js'
			'vendor/vojtech-dobes/nette-forms-gpspicker/client/nette.gpsPicker.js'
			'bower_components/html.sortable/dist/html.sortable.min.js'
		])
		gulp.src 'www/static/js/*.coffee'
			.pipe plugins.plumber()
			.pipe plugins.sourcemaps.init()
			.pipe plugins.coffee()
			.on 'error', gutil.log
			.pipe plugins.sourcemaps.write()
			.pipe plugins.plumber.stop()
	]
	.pipe plugins.sourcemaps.init {loadMaps: yes}
	.pipe plugins.concat 'scripts.js'
	.pipe plugins.uglify()
	.pipe plugins.sourcemaps.write '.'
	.pipe gulp.dest 'www/static/js'


gulp.task 'watch', ->
	gulp.watch 'www/static/css/*.less', ['less']
	gulp.watch 'www/static/js/*.coffee', ['scripts']


gulp.task 'build', ['less', 'scripts']
