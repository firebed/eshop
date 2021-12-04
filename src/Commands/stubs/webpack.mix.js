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

fs.rmSync('./public/dist', {force: true, recursive: true});
fs.rmSync('./public/images', {force: true, recursive: true});

fs.copySync('./resources/images/assets', './public/images');
fs.copySync('./resources/images/home', './public/images');

mix
    .disableNotifications()
    .js(['resources/js/customer/app.js', 'node_modules/fslightbox/index.js'], 'public/dist/app.js')
    .sass('resources/scss/customer/app.scss', 'public/dist/app.css')

    .copy('node_modules/keen-slider/keen-slider.js', 'public/dist/keen-slider.js')
    .copy('node_modules/keen-slider/keen-slider.js.map', 'public/dist/keen-slider.js.map')
    .sass('node_modules/keen-slider/keen-slider.scss', 'public/dist/keen-slider.css')

    .sourceMaps()

    .versionHash()

    .after(() => {
        const dashboard = JSON.parse(fs.fs.readFileSync('vendor/firebed/eshop/dist/mix-manifest.json', {encoding: 'utf8', flag: 'r'}))

        const js = dashboard['/dashboard.js']
        const css = dashboard['/dashboard.css']

        fs.copyFileSync('vendor/firebed/eshop/dist' + js, 'public/dist' + js)
        fs.copyFileSync('vendor/firebed/eshop/dist' + js + '.map', 'public/dist' + js + '.map')
        fs.copyFileSync('vendor/firebed/eshop/dist' + css, 'public/dist' + css)
        fs.copyFileSync('vendor/firebed/eshop/dist' + css + '.map', 'public/dist' + css + '.map')

        const manifest = 'public/mix-manifest.json';
        const customer = JSON.parse(fs.fs.readFileSync(manifest, {encoding: 'utf8', flag: 'r'}))
        customer['/dist/dashboard.js'] = '/dist' + js
        customer['/dist/dashboard.css'] = '/dist' + css

        fs.writeFileSync(manifest, JSON.stringify(customer, null, 4), 'utf-8')
    })
