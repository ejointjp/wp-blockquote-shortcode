$ = require('gulp-load-plugins')()
gulp = require 'gulp'

# Config ######################

dir =
  src: 'src'
  release: 'wp-blockquote-shortcode/css'

errorMessage = 'Error: <%= error.message %>'

# Tasks #######################

# Sass
gulp.task 'sass', ->

  sassOptions =
    style: 'expanded'
    require: ['bourbon']
    sourcemap: false


  pleeeseOptions =
    autoprefixer:
      browsers: ['last 2 versions', 'android 4.1']
    minifier: false
    sourcemaps: false
    sass: false

  $.rubySass dir.src + '/**/*.scss', sassOptions

    .pipe $.pleeease pleeeseOptions
    .pipe gulp.dest dir.release
    .pipe $.pleeease {minifier: true}
    .pipe $.rename {extname: '.min.css'}
    .pipe gulp.dest dir.release

# Copy
gulp.task 'copy', ->
  gulp
    .src dir.src + '/_wp-blockquote-shortcode.scss'
    .pipe gulp.dest dir.release


#Watch
gulp.task 'default', ->
  gulp.watch dir.src + '/**/*.scss', ['sass']
