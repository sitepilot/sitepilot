const mix = require('laravel-mix');

require('laravel-mix-imagemin');

mix.js('assets/js/admin-settings.js', 'assets/dist/js')
    .sass('assets/scss/admin-settings.scss', 'assets/dist/css')
    .sass('assets/scss/admin-menu.scss', 'assets/dist/css').options({
        processCssUrls: false
    })
    .imagemin(
        'img/**.*',
        {
            context: 'assets/'
        }
    )
    .setPublicPath('assets/dist')
    .browserSync({
        ui: false,
        proxy: 'https://sandbox.dev.sitepilot.io',
        files: [
            "*.php",
            "**/*.php",
            "assets/dist/css/*.css",
            "assets/dist/css/*.js",
            "assets/dist/img/*.*"
        ]
    })
    .webpackConfig({
        externals: {
            "jquery": "jQuery"
        }
    });