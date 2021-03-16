const mix = require('laravel-mix');

mix.setPublicPath('assets/dist')
    .postCss('assets/css/tailwind.css', './assets/dist/css', [
        require('tailwindcss')
    ])
    .postCss('assets/css/admin.css', './assets/dist/css', [
        require('tailwindcss'),
        require('postcss-nested')
    ]).options({
        processCssUrls: false
    })
    .postCss('assets/css/frontend.css', './assets/dist/css', [
        require('tailwindcss'),
        require('postcss-nested')
    ])
    .postCss('assets/css/dashboard.css', './assets/dist/css', [
        require('tailwindcss'),
        require('postcss-nested')
    ])
    .postCss('assets/css/editor.css', './assets/dist/css', [
        require('tailwindcss'),
        require('postcss-nested')
    ])
    .js('assets/js/editor.js', 'assets/dist/js')
    .js('assets/js/frontend.js', 'assets/dist/js')
    .js('assets/js/dashboard.jsx', 'assets/dist/js').react()
    .webpackConfig({
        externals: {
            "react": "React",
            "jquery": "jQuery"
        }
    });
