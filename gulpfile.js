const gulp = require('gulp');
const $    = require('gulp-load-plugins')();

const exec = template => $.run(template, {verbosity: 3}).exec();
const phpunit = (bin => path => exec(path ? bin + ' ' + path : bin))('./bin/phpunit');

gulp.task('test', () => phpunit());

gulp.task('watch', () => {
  $.watch('test/**/*Test.php', vinyl => phpunit(vinyl.path).on('error', () => {}));
});
