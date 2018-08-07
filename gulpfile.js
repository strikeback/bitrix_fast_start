var gulp = require('gulp'),
        watch = require('gulp-watch'),
        postcss = require('gulp-postcss'),
        autoprefixer = require('autoprefixer'),
        sass = require('gulp-sass'),
        sourcemaps = require('gulp-sourcemaps'),
        cssnano = require('gulp-cssnano'),
        imagemin = require('gulp-imagemin'),
        pngquant = require('imagemin-pngquant'),
        uglify = require('gulp-uglify'),
        concat = require('gulp-concat'),
        rename = require('gulp-rename');
var path = {
  build: {//Тут мы укажем куда складывать готовые после сборки файлы
    js: '_js/',
    css: '_css/',
    img: '_images/',
  },
  src: {//Пути откуда брать исходники
    js: ['_js/**/*.js', '!_js/**/*.min.js', '!_js/**/*-min.js'], //В стилях и скриптах нам понадобятся только main файлы
    style: '_css/**/*.scss',
    img: '_images/src/**/*.*', //Синтаксис img/**/*.* означает - взять все файлы всех расширений из папки и из вложенных каталогов
  },
  watch: {//Тут мы укажем, за изменением каких файлов мы хотим наблюдать
    js: ['_js/**/*.js', '!_js/**/*.min.js', '!_js/**/*-min.js'],
    style: '_css/**/*.scss',
    img: '_images/src/**/*.*'
  },
  clean: './build'
};
gulp.task('js:build', function () {
  gulp.src(path.src.js)
          .pipe(sourcemaps.init()) //Инициализируем sourcemap
          .pipe(uglify()).on("error", function () {}) //Сожмем наш js
          .pipe(rename(function (path) {
            if (path.extname === '.js') {
              path.basename += '.min';
            }
          }))
          .pipe(concat('all.min.js'))
//          .concat('bundle.min.js')
          .pipe(sourcemaps.write()) //Пропишем карты
          .pipe(gulp.dest(path.build.js)) //Выплюнем готовый файл в build
});
gulp.task('image:build', function () {
  gulp.src(path.src.img) //Выберем наши картинки
          .pipe(imagemin({//Сожмем их
            progressive: true,
            svgoPlugins: [{removeViewBox: false}],
            use: [pngquant()],
            interlaced: true
          }))
          .pipe(gulp.dest(path.build.img)) //И бросим в build
});
gulp.task('style:build', function () {
  gulp.src(path.src.style) //Выберем наш main.scss
          .pipe(sourcemaps.init()) //То же самое что и с js
          .pipe(sass()).on("error", function () {})  //Скомпилируем
          .pipe(postcss([autoprefixer({browsers: ['last 2 version']})]))
          .pipe(cssnano({autoprefixer: false, convertValues: false, zindex: false})) //Сожмем
          .pipe(sourcemaps.write())
          .pipe(gulp.dest(path.build.css)) //И в build
});
gulp.task('build', [
  'js:build',
  'style:build',
  'image:build'
]);

gulp.task('watch', function () {
  watch([path.watch.style], function (event, cb) {
    gulp.start('style:build');
  });
  watch(path.watch.js, function (event, cb) {/*тут уже в параметре массив*/
    gulp.start('js:build');
  });
  watch([path.watch.img], function (event, cb) {
    gulp.start('image:build');
  });
});

gulp.task('default', ['build', 'watch']); 