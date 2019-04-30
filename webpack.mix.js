let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/assets/js/app.js', 'public/js')
    .sass('resources/assets/sass/app.scss', 'public/css')
    // .browserSync('localhost:8000');
;

mix.sass('resources/assets/vendor/magicsuggest/magicsuggest.scss', 'public/css');
mix.js('resources/assets/vendor/magicsuggest/magicsuggest.js', 'public/js');

// File hash suffix in production (to bust old caches)
if (mix.inProduction()) {
    mix.version();
}