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
    return src('./js/*.js')
        .pipe(minify({
            noSource: true,
            ignoreFiles: ['*.min.js'],
            ext: {
                min: '.min.js'
            }
        }))
        .pipe(dest('./js'));
}

exports.minSass = minSass;
exports.minJs = minJs;
exports.default = series(minSass, minJs);

watch('./sass/**/*.scss', minSass);
watch('./js/src/**/*.js', minJs);