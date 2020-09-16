const mix = require('laravel-mix');

mix.js('assets/js/admin.js', 'assets/dist/js')
    .sass('assets/scss/admin.scss', 'assets/dist/css').options({
        processCssUrls: false
    })
    .setPublicPath('assets/dist')
    .webpackConfig({
        externals: {
            "jquery": "jQuery"
        }
    });