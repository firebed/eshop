<?php

namespace Eshop\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class InstallCommand extends Command
{
    protected $signature = 'eshop
                    { action : (install) }
                    { --option=* : Pass an option to the command }';

    protected $description = 'Eshop action command';

    public function handle(): void
    {
        if ($this->argument('action') !== 'install') {
            $this->error("Invalid action");
            return;
        }

        $this->{$this->argument('action')}();
    }

    protected function install(): void
    {
        static::updatePackages();
        static::updateWebpackConfiguration();
        static::updateSass();
        static::updateBootstrapping();
//        static::removeNodeModules();

        $this->info('Eshop installed successfully');
        $this->comment('Please run "npm install && npm run dev" to compile your fresh scaffolding.');
    }

    protected static function updatePackages($dev = TRUE): void
    {
        if (!file_exists(base_path('package.json'))) {
            return;
        }

        $configurationKey = $dev ? 'devDependencies' : 'dependencies';

        $packages = json_decode(file_get_contents(base_path('package.json')), TRUE);

        $packages[$configurationKey] = static::updatePackageArray(
            array_key_exists($configurationKey, $packages) ? $packages[$configurationKey] : [],
        );

        ksort($packages[$configurationKey]);

        file_put_contents(
            base_path('package.json'),
            json_encode($packages, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . PHP_EOL
        );
    }

    protected static function updatePackageArray(array $packages): array
    {
        return [
                "fslightbox"         => "^3.2.3",
                "@popperjs/core"     => "^2.9.2",
                "bootstrap"          => "^5.1.0",
                "slim-select"        => "^1.27.0",
                "slugify"            => "^1.6.0",
                "postcss"            => "^8.1.14",
                "resolve-url-loader" => "^3.1.2",
                "sass"               => "^1.37.5",
                "sass-loader"        => "^11.0.1",
            ] + $packages;
    }

    protected static function updateWebpackConfiguration(): void
    {
        copy(__DIR__ . '/stubs/webpack.mix.js', base_path('webpack.mix.js'));
    }

    protected static function updateSass(): void
    {
        (new Filesystem)->ensureDirectoryExists(resource_path('scss'));
        (new Filesystem)->ensureDirectoryExists(resource_path('scss/dashboard'));
        (new Filesystem)->ensureDirectoryExists(resource_path('scss/customer'));
        (new Filesystem)->ensureDirectoryExists(resource_path('js/dashboard'));
        (new Filesystem)->ensureDirectoryExists(resource_path('js/customer'));

        copy(__DIR__ . '/stubs/scss/_buttons.scss', resource_path('scss/_buttons.scss'));
        copy(__DIR__ . '/stubs/scss/_color-wheel.scss', resource_path('scss/_color-wheel.scss'));
        copy(__DIR__ . '/stubs/scss/_colors.scss', resource_path('scss/_colors.scss'));
        copy(__DIR__ . '/stubs/scss/_loader.scss', resource_path('scss/_loader.scss'));
        copy(__DIR__ . '/stubs/scss/_scrollbar.scss', resource_path('scss/_scrollbar.scss'));
        copy(__DIR__ . '/stubs/scss/_utilities.scss', resource_path('scss/_utilities.scss'));

        copy(__DIR__ . '/stubs/scss/dashboard/_navigation.scss', resource_path('scss/dashboard/_navigation.scss'));
        copy(__DIR__ . '/stubs/scss/dashboard/_tree.scss', resource_path('scss/dashboard/_tree.scss'));
        copy(__DIR__ . '/stubs/scss/dashboard/_variables.scss', resource_path('scss/dashboard/_variables.scss'));
        copy(__DIR__ . '/stubs/scss/dashboard/app.scss', resource_path('scss/dashboard/app.scss'));

        copy(__DIR__ . '/stubs/scss/customer/_cart-button.scss', resource_path('scss/customer/_cart-button.scss'));
        copy(__DIR__ . '/stubs/scss/customer/_filters.scss', resource_path('scss/customer/_filters.scss'));
        copy(__DIR__ . '/stubs/scss/customer/_logo.scss', resource_path('scss/customer/_logo.scss'));
        copy(__DIR__ . '/stubs/scss/customer/_navbar.scss', resource_path('scss/customer/_navbar.scss'));
        copy(__DIR__ . '/stubs/scss/customer/_variables.scss', resource_path('scss/customer/_variables.scss'));
        copy(__DIR__ . '/stubs/scss/customer/app.scss', resource_path('scss/customer/app.scss'));
    }

    protected static function updateBootstrapping(): void
    {
        copy(__DIR__ . '/stubs/js/dashboard/app.js', resource_path('js/dashboard/app.js'));
        copy(__DIR__ . '/stubs/js/customer/app.js', resource_path('js/customer/app.js'));
    }

    protected static function removeNodeModules(): void
    {
        tap(new Filesystem, static function ($files) {
            $files->deleteDirectory(base_path('node_modules'));

            $files->delete(base_path('yarn.lock'));
        });
    }
}