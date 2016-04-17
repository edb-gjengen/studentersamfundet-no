/* global require */
'use strict';
var gulp = require('gulp');
var $ = require('gulp-load-plugins')();
var bowerFiles = require('main-bower-files');
var del = require('del');
var browserSync = require('browser-sync').create();
var reload = browserSync.reload;

gulp.task('styles', function () {
    gulp.src('app/styles/*.scss')
        .pipe($.sass({
            includePaths: ['bower_components/foundation/scss']
        }).on('error', $.sass.logError))
        .pipe($.autoprefixer(['last 1 version']))
        .pipe(gulp.dest('dist/styles'))
        .pipe($.size());
});

gulp.task('diststyles', function () {
    return gulp.src('app/styles/*.scss')
        .pipe($.sass({
            includePaths: ['bower_components/foundation/scss']
        }))
        .pipe($.autoprefixer('last 1 version'))
        .pipe($.csso())
        .pipe(gulp.dest('dist/styles'))
        .pipe($.size());
});

gulp.task('vendorscripts', function () {
    var js_files = bowerFiles();
    //console.log(js_files);
    return gulp.src(js_files)
        .pipe($.filter('**/*.js'))
        //.pipe($.uglify())
        .pipe($.concat('vendor.js'))
        .pipe(gulp.dest('dist/scripts'))
        .pipe($.size());
});

gulp.task('scripts', function () {
    return gulp.src('app/scripts/**/*.js')
        .pipe($.jshint())
        .pipe($.jshint.reporter(require('jshint-stylish')))
        .pipe(gulp.dest('dist/scripts'))
        .pipe($.size());
});

gulp.task('distscripts', function () {
    return gulp.src('app/scripts/**/*.js')
        .pipe($.jshint())
        .pipe($.jshint.reporter(require('jshint-stylish')))
        .pipe($.uglify())
        .pipe(gulp.dest('dist/scripts'))
        .pipe($.size());
});

gulp.task('images', function () {
    return gulp.src('app/images/**/*')
        .pipe($.imagemin({
            optimizationLevel: 3,
            progressive: true,
            interlaced: true
        }))
        .pipe(gulp.dest('dist/images'))
        .pipe($.size());
});

gulp.task('fonts', function () {
    return gulp.src(bowerFiles())
        .pipe($.filter('**/*.{eot,svg,ttf,woff,woff2}'))
        .pipe($.flatten())
        .pipe(gulp.dest('dist/fonts'))
        .pipe($.size());
});

gulp.task('extras', function () {
    return gulp.src(['app/*.*', '!app/*.php'], { dot: true })
        .pipe(gulp.dest('dist'));
});

gulp.task('clean', function (cb) {
   return del(['.tmp', 'dist'], cb);
});

gulp.task('build', ['vendorscripts', 'distscripts', 'diststyles', 'images', 'fonts', 'extras']);

gulp.task('default', ['clean'], function () {
    gulp.start('build');
});

gulp.task('serve', ['styles'], function () {
    //require('opn')('http://neuf-web.dev');
    browserSync.init({
        proxy: 's-no.dev',  // your virtualhost
        host: 'localhost',
        notify: 'false'
    });
});

gulp.task('watch', ['serve'], function () {
    // watch for changes
    gulp.watch([
        '*.php',
        'dist/styles/*.css',
        'dist/scripts/**/*.js',
        'dist/images/**/*'
    ]).on('change', reload);

    gulp.watch('app/styles/**/*.scss', ['styles']);
    gulp.watch('app/scripts/**/*.js', ['scripts']);
    gulp.watch('app/images/**/*', ['images']);
    gulp.watch('bower.json', ['vendorscripts']);
});
