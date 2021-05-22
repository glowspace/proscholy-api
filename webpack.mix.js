const mix = require('laravel-mix');
const path = require('path');

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

// var BrotliGzipPlugin = require('brotli-gzip-webpack-plugin');

mix.webpackConfig({
    module: {
        rules: [
            {
                test: /\.(graphql|gql)$/,
                loader: 'graphql-tag/loader'
            }
        ]
    },
    resolve: {
        alias: {
            '@': __dirname,
            Admin: path.resolve(__dirname, 'resources/assets/js/admin/'),
            Public: path.resolve(__dirname, 'resources/assets/js/'),
            Fragments: path.resolve(__dirname, 'graphql/client/')
        }
    }
});

mix.js('resources/assets/js/app.js', 'public/js')
    .sass('resources/assets/sass/app.scss', 'public/css')
    .copyDirectory('resources/assets/vendor/ViewerJS', 'public/js/ViewerJS')
    .browserSync({
        // proxy settings for php artisan serve
        // if you are using docker then change proxy to 'nginx:80'
        proxy: 'localhost:8000',
        files: [
            'public/css/app.css',
            'public/js/app.js',
            'public/_admin/js/app.js',
            'public/_admin/css/admin.css',
            'app/**/*',
            'routes/**/*',
            'resources/views/**/*',
            'resources/lang/**/*'
        ]
    });

/**
 * Administration
 */
mix.sass('resources/assets/sass/admin/admin-ui.scss', 'public/_admin/css')
    .js('resources/assets/js/admin/app.js', 'public/_admin/js')
    .stylus('resources/assets/stylus/admin.styl', 'public/_admin/css');

mix.sass(
    'resources/assets/vendor/magicsuggest/magicsuggest.scss',
    'public/_admin/css'
);
mix.js(
    'resources/assets/vendor/magicsuggest/magicsuggest.js',
    'public/_admin/js'
);

if (mix.inProduction()) {
    mix.version();
    // .webpackConfig({
    //     plugins: [
    //         new BrotliGzipPlugin({
    //             asset: '[path].br[query]',
    //             algorithm: 'brotli',
    //             test: /\.(js|css|html|svg)$/,
    //             threshold: 10240,
    //             minRatio: 0.8,
    //             quality: 11
    //         }),
    //         new BrotliGzipPlugin({
    //             asset: '[path].gz[query]',
    //             algorithm: 'gzip',
    //             test: /\.(js|css|html|svg)$/,
    //             threshold: 10240,
    //             minRatio: 0.8
    //         })
    //     ],
    //   })
    // .version(); // File hash suffix in production (to bust old caches)
} else {
    mix.sourceMaps(true, 'source-map');
}
