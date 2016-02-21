var gulp = require('gulp');
var htmlmin = require('gulp-htmlmin');
var elixir = require('laravel-elixir');

gulp.task('compress', function() {
    var opts = {
        collapseWhitespace: true,
        removeAttributeQuotes: true,
        removeComments: true,
        minifyJS: true
    };

    return gulp.src('./storage/framework/views/*')
               .pipe(htmlmin(opts))
               .pipe(gulp.dest('./storage/framework/views/'));
});

gulp.task("copyfiles", function() {

  gulp.src("resources/vendor/jquery/dist/jquery.js")
    .pipe(gulp.dest("resources/assets/js/vendor/"));

  gulp.src("resources/vendor/bootstrap/less/**")
    .pipe(gulp.dest("resources/assets/less/vendor/bootstrap"));

  gulp.src("resources/vendor/bootstrap/dist/js/bootstrap.js")
    .pipe(gulp.dest("resources/assets/js/vendor/"));

  gulp.src("resources/vendor/bootstrap/dist/fonts/**")
    .pipe(gulp.dest("public/assets/fonts"));

  gulp.src("resources/vendor/selectize/dist/js/standalone/selectize.js")
    .pipe(gulp.dest("resources/assets/js/vendor/"));

  gulp.src("resources/vendor/datepair.js/dist/datepair.js")
    .pipe(gulp.dest("resources/assets/js/vendor/"));

  gulp.src("resources/vendor/datepair.js/dist/jquery.datepair.js")
    .pipe(gulp.dest("resources/assets/js/vendor/"));

  gulp.src("resources/vendor/selectize/dist/less/**")
    .pipe(gulp.dest("resources/assets/less/vendor/selectize"));

  gulp.src("resources/vendor/jquery.inputmask/dist/inputmask/inputmask.js")
    .pipe(gulp.dest("resources/assets/js/vendor/"));

  gulp.src("resources/vendor/jquery.inputmask/dist/inputmask/inputmask.date.extensions.js")
    .pipe(gulp.dest("resources/assets/js/vendor/"));

  gulp.src("resources/vendor/jquery.inputmask/dist/inputmask/jquery.inputmask.js")
    .pipe(gulp.dest("resources/assets/js/vendor/"));

  gulp.src("resources/vendor/jquery-auto-grow-input/jquery.auto-grow-input.js")
    .pipe(gulp.dest("resources/assets/js/vendor/"));

  gulp.src("resources/vendor/history.js/scripts/bundled-uncompressed/html4+html5/jquery.history.js")
    .pipe(gulp.dest("resources/assets/js/vendor/"));

  gulp.src("resources/vendor/spin.js/spin.js")
    .pipe(gulp.dest("resources/assets/js/vendor/"));

  gulp.src("resources/vendor/spin.js/jquery.spin.js")
    .pipe(gulp.dest("resources/assets/js/vendor/"));

  gulp.src("resources/vendor/jquery-highlight/jquery.highlight.js")
    .pipe(gulp.dest("resources/assets/js/vendor/"));

  gulp.src("resources/vendor/jquery-sticky/jquery.sticky.js")
    .pipe(gulp.dest("resources/assets/js/vendor/"));


});

elixir(function(mix) {

  mix.scripts([
      'js/vendor/jquery.js',
      'js/vendor/bootstrap.js',
      'js/libs/bootstrap-datepicker.js',
      'js/libs/jquery.timepicker.js',
      'js/vendor/jquery.history.js',
      'js/vendor/inputmask.js',
      'js/vendor/inputmask.date.extensions.js',
      'js/vendor/jquery.inputmask.js',
      'js/vendor/jquery.auto-grow-input.js',
      'js/vendor/spin.js',
      'js/vendor/jquery.spin.js',
      'js/vendor/jquery.highlight.js',
      'js/vendor/jquery.sticky.js',
      'js/libs/selectize.js',
      'js/blocks/**/*.js',
      'js/app.js'
    ],
    'public/assets/js/app.js',
    'resources/assets'
  );

	  mix.styles([
	    "libs/bootstrap-datepicker.standalone.css",
	    "app.css",
	  ],'public/assets/css/site.css');

	mix.less([
		'vendor/selectize/selectize.default.less',
		'app.less'
	], 'public/assets/css/app.css');
});