// --- BROWSER SYNC --- //

var gulp        = require('gulp');
var browserSync = require('browser-sync').create();
var sass        = require('gulp-sass');

// Static Server + watching scss/html files

gulp.task('serve', ['sass'], function() {
    browserSync.init({
        proxy: "local.dashoftexas"
    });
    gulp.watch("assets/scss/**/*.scss", ['sass']);
    gulp.watch("*.html").on('change', browserSync.reload);
    gulp.watch("*.php").on('change', browserSync.reload);
});

// Compile sass into CSS & auto-inject into browsers

gulp.task('sass', function() {
    return gulp.src("./assets/scss/style.scss")
        .pipe(sass().on('error', sass.logError))
        .pipe(gulp.dest("./"))
        .pipe(browserSync.stream());
});

gulp.task('default', ['serve']);
