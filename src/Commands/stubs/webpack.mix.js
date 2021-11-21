const mix = require('laravel-mix');
const fs = require('file-system');
require('laravel-mix-versionhash')


/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

fs.rmSync('./public/dist', {force: true, recursive: true})
fs.rmSync('./public/images', {force: true, recursive: true})

fs.copySync('./resources/images/assets', './public/images')

mix
    .js(['resources/js/customer/app.js', 'node_modules/fslightbox/index.js'], 'public/dist/app.js')
    .sass('resources/scss/customer/app.scss', 'public/dist/app.css')

    .js('resources/js/dashboard/app.js', 'public/dist/dashboard.js')
    .sass('resources/scss/dashboard/app.scss', 'public/dist/dashboard.css')

    .sourceMaps()

    .versionHash();