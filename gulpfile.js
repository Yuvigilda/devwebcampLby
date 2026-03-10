const { src, dest, watch, series, paeallel } = require('gulp');


const sass = require('gulp-sass')(require('sass'));

//css
const cssnano = require('cssnano');
const sourcemaps = require('gulp-sourcemaps');

//imagenes
const avif = require('gulp-avif');
const webp = require('gulp-webp');
const imagemin = require('gulp-imagemin');
const cache = require('gulp-cache');

//javascript
const terser = require('gulp-terser-js');
const rename = require('gulp-rename');
const webpack = require('webpack-stream');



const paths = {
    scss: 'src/scss/**/*.scss',
    js: 'src/js/**/*.js',
    imagenes: 'src/img/**/*'
}

function css() {
    return src(paths.scss)
        .pipe(sourcemaps.init())
        .pipe(sass({ outputStyle: 'expanded' }))
        .pipe(sourcemaps.write('.'))
        .pipe(dest('public/build/css'));
}
function javascript() {
    return src(paths.js)
        .pipe(webpack({
            module: {
                rules: [
                    {
                        test: /\.css$/i,
                        use: ['style-loader', 'css-loader']
                    }
                ]
            },
            mode: 'production',
            watch: true,
            entry: './src/js/app.js'
        }))
        .pipe(sourcemaps.init())
        .pipe(terser())
        .pipe(sourcemaps.write('.'))
        .pipe(rename({ suffix: '.min' }))
        .pipe(dest('./public/build/js'))
}

function imagenes() {
    return src(paths.imagenes)
        .pipe(cache(imagemin({ otimizationLavel: 3 })))
        .pipe(dest('public/build/img'))
}

function versionWebp(done) {
    const opciones = {
        quality: 50
    };
    src(`${paths.imagenes}.{png,jpg}`)
        .pipe(webp(opciones))
        .pipe(dest('public/build/img'))
    done();
}

function versionAvif(done) {
    const opciones = {
        quality: 50
    };
    src(`${paths.imagenes}.{png,jpg}`)
        .pipe(avif(opciones))
        .pipe(dest('public/build/img'))
    done();
}

function dev(done) {
    watch(paths.scss, css);
    watch(paths.js, javascript);
    watch(paths.imagenes, imagenes);
    watch(paths.imagenes, versionWebp);
    watch(paths.imagenes, versionAvif);
    done();
}

exports.css = css;
exports.js = javascript;
exports.imagenes = imagenes;
exports.versionWebp = versionWebp;
exports.versionAvif = versionAvif;

exports.dev = series(css, imagenes, versionWebp, versionAvif, dev);