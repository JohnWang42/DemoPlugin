const { series, src, dest, watch } = require('gulp');
const sass = require('gulp-sass');
const uglifycss = require('gulp-uglifycss');
const minify = require('gulp-minify');

function minSass() {
    return src('./sass/*.scss')
        .pipe(sass())
        .pipe(uglifycss())
        .pipe(dest('./'));
}

function minJs() {
    return src('./js/src/*.js')
        .pipe(minify({
            noSource: false,
            ext: {
                src: '-debug.js',
                min: '.js'
            }
        }))
        .pipe(dest('./js'));
}

exports.minSass = minSass;
exports.minJs = minJs;
exports.default = series(minSass, minJs);

watch('./sass/**/*.scss', minSass);
watch('./js/src/**/*.js', minJs);