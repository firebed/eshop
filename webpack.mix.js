const mix = require('laravel-mix');

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

mix
    .js('resources/js/customer/app.js', './assets/js/customer/app.js')
    .js('resources/js/dashboard/app.js', './assets/js/dashboard/app.js')
    .js('node_modules/fslightbox/index.js', './assets/js/fslightbox.js')
    .sass('resources/scss/customer/app.scss', './assets/css/customer/app.css')
    .sass('resources/scss/dashboard/app.scss', './assets/css/dashboard/app.css')
    .sourceMaps();

if (mix.inProduction()) {
    // mix.version();
}