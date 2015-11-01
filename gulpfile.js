// Sass configuration
var gulp = require('gulp');
var sass = require('gulp-sass');

gulp.task('sass', function() {
    gulp.src('./resources/scss/styles.scss')
        .pipe(sass())
        .pipe(gulp.dest('./'));
});

gulp.task('default', function() {
    gulp.watch('resources/scss/**/*.scss', ['sass']);
})