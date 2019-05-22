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

mix.webpackConfig({
    module: {
        rules: [
            // {
            //     test: /\.styl$/,
            //     loader: ['style-loader', 'css-loader', 'stylus-loader']
            // },
            {
                test: /\.(graphql|gql)$/,
                loader: 'graphql-tag/loader'
            }
        ]
    },
    resolve: {
        alias: {
            '@': __dirname
        },
    }
});

mix.js('resources/assets/js/app.js', 'public/js')
    .js('resources/assets/js/admin/app.js', 'public/_admin/js')
    .sass('resources/assets/sass/app.scss', 'public/css')
    .stylus('resources/assets/stylus/admin.styl', 'public/_admin/css')
    .browserSync('localhost:8000');

mix.sass('resources/assets/vendor/magicsuggest/magicsuggest.scss', 'public/_admin/css');
mix.js('resources/assets/vendor/magicsuggest/magicsuggest.js', 'public/_admin/js');

// File hash suffix in production (to bust old caches)
if (mix.inProduction()) {
    mix.version();
}