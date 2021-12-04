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

fs.rmSync('./dist', {force: true, recursive: true});

mix
    .disableNotifications()
    .setPublicPath('dist')

    .js('./resources/js/app.js', './dist/dashboard.js')
    .sass('./resources/scss/app.scss', './dist/dashboard.css')
    
    .sourceMaps()

    .versionHash()
