var gulp = require('gulp');
var elixir = require('laravel-elixir');

gulp.task("copyfiles", function() {

  gulp.src("resources/vendor/jquery/dist/jquery.js")
    .pipe(gulp.dest("resources/assets/js/vendor/"));

  gulp.src("resources/vendor/bootstrap/less/**")
    .pipe(gulp.dest("resources/assets/less/vendor/bootstrap"));

  gulp.src("resources/vendor/bootstrap/dist/js/bootstrap.js")
    .pipe(gulp.dest("resources/assets/js/vendor/"));

  gulp.src("resources/vendor/bootstrap/dist/fonts/**")
    .pipe(gulp.dest("public/assets/fonts"));

});

elixir(function(mix) {

  mix.scripts([
      'js/vendor/jquery.js',
      'js/vendor/bootstrap.js',
      'js/app.js'
    ],
    'public/assets/js/app.js',
    'resources/assets'
  );

  mix.less('app.less', 'public/assets/css/app.css');
});