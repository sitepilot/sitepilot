const mix = require('laravel-mix');
const tailwindcss = require('tailwindcss');

mix.setPublicPath('assets/dist')
    .sass('assets/scss/blocks.scss', './assets/dist/css').options({
        processCssUrls: false,
        postCss: [tailwindcss('./tailwind.config.js')]
    })
    .sass('assets/scss/editor.scss', './assets/dist/css').options({
        processCssUrls: false
    })
    .sass('assets/scss/settings.scss', './assets/dist/css').options({
        processCssUrls: false
    })
    .sass('assets/scss/dashboard.scss', './assets/dist/css').options({
        processCssUrls: false
    })
    .sass('assets/scss/admin.scss', './assets/dist/css').options({
        processCssUrls: false
    })
    .js('assets/js/blocks.js', 'assets/dist/js')
    .react('assets/js/editor.js', 'assets/dist/js')
    .react('assets/js/settings.js', 'assets/dist/js')
    .react('assets/js/dashboard.js', 'assets/dist/js')
    .webpackConfig({
        externals: {
            "react": "React",
            "jquery": "jQuery"
        }
    });
