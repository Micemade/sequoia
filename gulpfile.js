'use strict'
// Inlude neccessary modules
var gulp = require('gulp'),
    browserSync = require('browser-sync'),
    sass = require('gulp-sass'), 
    autoprefixer = require('gulp-autoprefixer'),
    concat = require('gulp-concat'),
    uglify = require('gulp-uglify'),
    fsCache = require('gulp-fs-cache'),
    rename = require("gulp-rename"),
    wpPot = require('gulp-wp-pot'),
    zip = require('gulp-zip'),
    vinylPaths = require('vinyl-paths'),
    del = require('del'),
    findRemoveSync = require('find-remove'),
    runSequence = require('run-sequence'),
    dos2unix = require('gulp-dos2unix-js');



// Get project name from json file
var jsonData = require('./package.json');

// Projekct variables
var $project_name = jsonData.name,
    $project_version = jsonData.version,
    $packDest = 'C:/PROJEKTI/'+ $project_name + '-THEME-PACKAGE/',
    $packTemp = $packDest + $project_name;


// DOS2UNIX
gulp.task('eol', function () {
    return gulp.src(
    [
        //$packTemp + '/**/*.{css,js,php}',
        $packTemp + '/**/**',
    ])
    .pipe(dos2unix()) // This defaults to {feedback: false, write: false}
    .pipe(gulp.dest($packTemp))
  });
  
// EOL it in place
gulp.task('eolthis', function () {
    return gulp.src('/**')
    .pipe(dos2unix()) // This defaults to {feedback: false, write: false}
    .pipe(gulp.dest('/**'))
  });
// end dos2unix


// Configure browsersync
gulp.task('serve', function() {
    var files = [
        './style.css',
        './*.php',
        './template_parts/*.php'
    ];
    // Initialize BrowserSync with a PHP server
    browserSync.init(files, {
        proxy: 'localhost/'+ $project_name + '/',
        notify: {
            styles: {
            top: 'auto',
            bottom: '0'
            }
        }
    });
    gulp.watch('css/scss/**/*.scss',['styles']);

});

// Configure Sass task to run when the specified .scss files change.
// Browsersync will also reload browsers
gulp.task('styles', function() {
    // Process main css file(s) - style.scss and other scss files
    gulp.src('css/scss/*.scss')
    .pipe(sass({
        'outputStyle' : 'compressed'
    }))
    .pipe(autoprefixer({
        browsers: ['last 2 versions'],
        cascade: false
    }))
    .pipe(gulp.dest('./'))
    .pipe(browserSync.stream()) //Possible use in future uncomment if neccessary
    
    // Process and output woocommerce.scss
    gulp.src('css/scss/woocommerce/*.scss')
    .pipe(sass({
        'outputStyle' : 'compressed'
    }))
    .pipe(autoprefixer({
        browsers: ['last 2 versions'],
        cascade: false
    }))
    .pipe(rename({
        suffix: '.min'
        }))
    .pipe(gulp.dest('css/'))
    .pipe(browserSync.stream());
    
    // Process and output foundation.scss
    gulp.src('css/scss/foundation6/*.scss')
    .pipe(sass({
        'outputStyle' : 'compressed'
    }))
    .pipe(autoprefixer({
        browsers: ['last 2 versions'],
        cascade: false
    }))
    .pipe(rename({
        suffix: '.min'
        }))
    .pipe(gulp.dest('css/'))
    .pipe(browserSync.stream());
    
});


// Process JS files (concatenate and uglify)
gulp.task('scripts', function() {
    var jsFsCache = fsCache('.tmp/jscache'); // save cache to .tmp/jscache
    return gulp.src('js/theme_scripts/**/*.js')
      .pipe(concat('theme_scripts.js'))
        .pipe(rename({suffix: '.min'}))
        .pipe(jsFsCache)
        .pipe(uglify())
        .pipe(jsFsCache.restore)
        .pipe(gulp.dest('./js'))

        .pipe(browserSync.stream());
})

// Create default task that can be called using 'gulp'.
// The DEFAULT task will process sass, 
// run browser-sync 'serve' and start watchin for changes
gulp.task('default',['serve']);


// Process JS SCRIPTS,
// run browser-sync 'serve' and start watchin for changes
gulp.task('js',['scripts','serve'], function() {
    gulp.watch('js/theme_scripts/**/*.js',['scripts']);
});

// Generate translation .pot file from all php files
gulp.task('makepot', function () {
    return gulp.src('**/*.php')
        .pipe(wpPot( {
            domain: $project_name,
            package: $project_name,
            team: 'Micemade <alen@micemade.com>'
        } ))
        .pipe(gulp.dest('./languages/'+ $project_name +'.pot'));
});
// Copy all files to destination
gulp.task('copy', function() {
    return gulp.src([
        './**',
        '!./node_modules', 
        '!./node_modules/**',
        '!./gulpfile.js',
        '!./package.json',
        '!./package-lock.json',
        '!./images/stock', 
        '!./images/stock/**',
        '!./js/tmp', 
        '!./js/tmp/**',
        '!./micemade_theme_setup/setup-export.php',
        '!./**/*.db' // remove Windows Thumbs.db files
    ])
        .pipe(gulp.dest( $packTemp ));
})
// Zip the [$project_name] folder in pack desitnation
gulp.task('zipit', function() {
    return gulp.src( $packTemp + '**/**' )
    .pipe(zip( $project_name + '.' + $project_version +'.zip'))
    .pipe( gulp.dest( $packDest ) )
});
// Delete tempoarary folder ( copied theme folder in $packDest directory )
gulp.task('clean-temp', function () {
    del(
        $packTemp,
        { force: true }
    );
});
gulp.task('cleansrc', function () {
    findRemoveSync(
        '',
        { extensions: ['.db'] }
    );
});


// PACK EVERYTHING FOR INSTALLATION READY WP THEME ZIP
gulp.task('pack', function() {
    return runSequence(
        'makepot',
        //'styles',
        //'scripts',
        'copy',
        'eol',
        'zipit',
        'clean-temp'
    );
});


// RUN BEFORE COMMITING TO GITHUB REPO
gulp.task('beforegit', function() {
    return runSequence(
        'makepot',
        'cleansrc',
        'eolthis'
    );
});


// Additional useful tasks
// RUN CSS AND JS FILES
gulp.task('cssjs', function() {
    return runSequence(
        'styles',
        'scripts'
    );
});

