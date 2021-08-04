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
    .js('resources/js/customer/app.js', 'public/js/customer/app.js')
    .js('resources/js/dashboard/app.js', 'public/js/dashboard/app.js')
    .js('node_modules/fslightbox/index.js', 'public/js/fslightbox.js')
    .sass('resources/scss/customer/app.scss', 'public/css/customer/app.css')
    .sass('resources/scss/dashboard/app.scss', 'public/css/dashboard/app.css')
    .sourceMaps();

if (mix.inProduction()) {
    // mix.version();
}
