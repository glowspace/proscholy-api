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

var BrotliGzipPlugin = require('brotli-gzip-webpack-plugin');

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
            'Admin': path.resolve(__dirname, 'resources/assets/js/admin/'),
            'Public': path.resolve(__dirname, 'resources/assets/js/'),
            'Fragments': path.resolve(__dirname, 'graphql/client/'),
        },
    }
});

mix.js('resources/assets/js/login/login.js', 'public/js')
    .js('resources/assets/js/client/client.js', 'public/js')
    .js('resources/assets/js/admin/admin.js', 'public/_admin/js')
    .sass('resources/assets/sass/app.scss', 'public/css')
    .stylus('resources/assets/stylus/admin.styl', 'public/_admin/css')
    .browserSync({
        // proxy settings for docker
        // if you are using php artisan serv then change proxy to 'localhost:8000'
        proxy: 'nginx:80',
        files: [
            'public/css/app.css',
            'public/js/login.js',
            'public/_admin/js/login.js',
            'public/_admin/css/admin.css',
            'app/**/*',
            'routes/**/*',
            'resources/views/**/*',
            'resources/lang/**/*'
        ]
    });

mix.sass('resources/assets/vendor/magicsuggest/magicsuggest.scss', 'public/_admin/css');
mix.js('resources/assets/vendor/magicsuggest/magicsuggest.js', 'public/_admin/js');

if (mix.inProduction()) {
    mix
        .webpackConfig({
            plugins: [
                new BrotliGzipPlugin({
                    asset: '[path].br[query]',
                    algorithm: 'brotli',
                    test: /\.(js|css|html|svg)$/,
                    threshold: 10240,
                    minRatio: 0.8,
                    quality: 11
                }),
                new BrotliGzipPlugin({
                    asset: '[path].gz[query]',
                    algorithm: 'gzip',
                    test: /\.(js|css|html|svg)$/,
                    threshold: 10240,
                    minRatio: 0.8
                })
            ],
        })
        .version(); // File hash suffix in production (to bust old caches)
}
