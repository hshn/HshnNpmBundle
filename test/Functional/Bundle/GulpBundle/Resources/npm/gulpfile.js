var gulp = require('gulp');
var $ = require('gulp-load-plugins')();

var source = require('vinyl-source-stream');
var buffer = require('vinyl-buffer');

var es2015 = require('babel-preset-es2015');

var browserify = require('browserify');

gulp.task('build', function() {
  var b = browserify({
    entries: '../js/app.js',
    debug: true,
    transform: [['babelify', {presets: [es2015]}]]
  });

  return b.bundle()
    .pipe(source('app.js'))
    .pipe(buffer())
    .pipe($.sourcemaps.init({loadMaps: true}))
      .pipe($.uglify())
      .on('error', $.util.log)
    .pipe($.sourcemaps.write('./'))
    .pipe(gulp.dest('../public/dist/'));
});
