var gulp = require('gulp'),
    annotate = require('gulp-ng-annotate'),
    concat = require('gulp-concat'),
    browserify = require('browserify'),
    babelify = require('babelify'),
    source = require('vinyl-source-stream'),
    less = require('gulp-less'),
    connect = require('gulp-connect'),

    directories = {
        src: __dirname + '/src',
        public: __dirname + '/public',
        modules: __dirname + '/node_modules',
        tmp: __dirname + '/tmp'
    },
    files = {
        vendor: [
            directories.modules + '/jquery/dist/jquery.min.js',
            directories.modules + '/angular/angular.min.js',
            directories.modules + '/angular-bootstrap/ui-bootstrap.min.js',
            directories.modules + '/angular-bootstrap/ui-bootstrap-tpls.min.js',
        ],

        fonts: [
            directories.modules + '/pmsipilot-ui/node_modules/font-awesome/fonts/*',
            directories.modules + '/pmsipilot-ui/node_modules/bootstrap/fonts/*',
            directories.modules + '/pmsipilot-ui/font/**'
        ],

        images: [
            directories.modules + '/pmsipilot-ui/images/**'
        ],

        es6: {
            entry: directories.src + '/js/app.js'
        },

        less: [
            directories.src + '/less/main.less'
        ],

        html: [
            directories.src + '/index.html'
        ]
    };


gulp.task('js:vendor', function() {
    var src = gulp.src(files.vendor, { strict: true })
        .pipe(annotate())
        .pipe(concat('vendor.js'));

    return src.pipe(gulp.dest(directories.public + '/js'));
});

gulp.task('fonts:vendor', function() {
    gulp.src(files.fonts, { strict: true })
        .pipe(gulp.dest(directories.public + '/font'));
});

gulp.task('images:vendor', function() {
    gulp.src(files.images, { strict: true })
        .pipe(gulp.dest(directories.public + '/css/images'))
        .pipe(gulp.dest(directories.public + '/images'));
});

gulp.task('js:app', function(done) {
    var success = done,
        error = function(err) {
            console.log('Error: ' + err.message);

            done(err);
        };

    browserify(files.es6.entry, { debug: true })
        .add(require.resolve('babelify/polyfill'))
        .transform(babelify)
        .bundle()
        .on('error', function (err) {
            console.log('Error: ' + err.message);

            this.emit('end');
        })
        .pipe(source('app.js'))
        .pipe(annotate())
        .pipe(gulp.dest(directories.tmp))
        .on('end', function () {
            var src = gulp.src(directories.tmp + '/app.js');

            src.pipe(gulp.dest(directories.public + '/js'))
                .on('end', success)
                .on('error', error);
        })
        .on('error', error);
});

gulp.task('styles:app:less', function() {
    gulp.src(files.less, { strict: true })
        .pipe(less())
        .pipe(gulp.dest(directories.public + '/css'));
});

gulp.task('html:app', function() {
    gulp.src(files.html, { strict: true })
        .pipe(gulp.dest(directories.public));
});

gulp.task('serve', function () {
    connect.server({
        root: 'public',
        port: 8080,
        livereload: true
    })
});

gulp.task('vendor', ['js:vendor', 'fonts:vendor', 'images:vendor']);
gulp.task('app', ['js:app', 'styles:app:less', 'html:app']);

gulp.task('default', ['vendor', 'app']);