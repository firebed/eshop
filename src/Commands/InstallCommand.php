<?php

namespace Eshop\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;
use RuntimeException;

class InstallCommand extends Command
{
    protected $signature = 'eshop
                    { action : (install) }
                    { --option=* : Pass an option to the command }';

    protected $description = 'Eshop action command';

    protected static function updatePackages($dev = true): void
    {
        if (!file_exists(base_path('package.json'))) {
            return;
        }

        $configurationKey = $dev ? 'devDependencies' : 'dependencies';

        $packages = json_decode(file_get_contents(base_path('package.json')), true);

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
                "@popperjs/core"          => "^2.9.2",
                "bootstrap"               => "^5.1.3",
                "clean-webpack-plugin"    => "^4.0.0",
                "fslightbox"              => "^3.2.3",
                "laravel-mix-clean"       => "^0.1.0",
                "laravel-mix-versionhash" => "^2.0.1",
                "resolve-url-loader"      => "^3.1.2",
                "sass"                    => "^1.37.5",
                "sass-loader"             => "^11.0.1",
                "slim-select"             => "^1.27.0",
                "slugify"                 => "^1.6.0",
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
        copy(
            __DIR__ . '/../../stubs/migrations/2014_10_12_000000_create_users_table.php',
            base_path('database/migrations/2014_10_12_000000_create_users_table.php')
        );

        copy(
            __DIR__ . '/../../stubs/routes/routes.php',
            base_path('routes/web.php'),
        );

        if (!is_dir($directory = resource_path('lang/el')) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $directory));
        }

        copy(
            __DIR__ . '/../../stubs/resources/lang/el/company.php',
            resource_path('lang/el/company.php'),
        );

        copy(
            __DIR__ . '/../../stubs/resources/lang/en/company.php',
            resource_path('lang/en/company.php'),
        );

        copy(
            __DIR__ . '/../../stubs/routes/routes.php',
            base_path('routes/web.php'),
        );

        if (!is_dir($directory = public_path('storage/images/flags')) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $directory));
        }

        copy(__DIR__ . '/../../assets/flags/Greece.png', public_path('storage/images/flags/Greece.png'));
        copy(__DIR__ . '/../../assets/flags/UnitedKingdom.png', public_path('storage/images/flags/UnitedKingdom.png'));
        copy(__DIR__ . '/../../assets/new-ribbon.png', public_path('storage/images/new-ribbon.png'));

        copy(
            __DIR__ . '/../../stubs/fortify/fortify.php',
            config_path('fortify.php'),
        );

        copy(
            __DIR__ . '/../../stubs/fortify/FortifyServiceProvider.php',
            app_path('Providers/FortifyServiceProvider.php'),
        );

        copy(
            __DIR__ . '/../../stubs/models/User.php',
            app_path('Models/User.php'),
        );

        if (!is_dir($directory = app_path('Actions/Fortify')) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $directory));
        }
        copy(__DIR__ . '/../../stubs/fortify/Actions/CreateNewUser.php', app_path('Actions/Fortify/CreateNewUser.php'));
        copy(__DIR__ . '/../../stubs/fortify/Actions/PasswordResetResponse.php', app_path('Actions/Fortify/PasswordResetResponse.php'));
        copy(__DIR__ . '/../../stubs/fortify/Actions/PasswordValidationRules.php', app_path('Actions/Fortify/PasswordValidationRules.php'));
        copy(__DIR__ . '/../../stubs/fortify/Actions/ResetUserPassword.php', app_path('Actions/Fortify/ResetUserPassword.php'));
        copy(__DIR__ . '/../../stubs/fortify/Actions/UpdateUserPassword.php', app_path('Actions/Fortify/UpdateUserPassword.php'));
        copy(__DIR__ . '/../../stubs/fortify/Actions/UpdateUserProfileInformation.php', app_path('Actions/Fortify/UpdateUserProfileInformation.php'));

        if (!is_dir($directory = app_path('Http/Requests')) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $directory));
        }
        copy(__DIR__ . '/../../stubs/requests/CategoryRequest.php', app_path('Http/Requests/CategoryRequest.php'));
        copy(__DIR__ . '/../../stubs/requests/CheckoutDetailsRequest.php', app_path('Http/Requests/CheckoutDetailsRequest.php'));
        copy(__DIR__ . '/../../stubs/requests/CheckoutPaymentRequest.php', app_path('Http/Requests/CheckoutPaymentRequest.php'));
        copy(__DIR__ . '/../../stubs/requests/ProductOfferRequest.php', app_path('Http/Requests/ProductOfferRequest.php'));
        copy(__DIR__ . '/../../stubs/requests/ProductSearchRequest.php', app_path('Http/Requests/ProductSearchRequest.php'));
        copy(__DIR__ . '/../../stubs/requests/UserAddressRequest.php', app_path('Http/Requests/UserAddressRequest.php'));
        copy(__DIR__ . '/../../stubs/requests/UserCompanyRequest.php', app_path('Http/Requests/UserCompanyRequest.php'));

        copy(__DIR__ . '/../../stubs/config/filesystems.php', config_path('filesystems.php'));

        Artisan::call('vendor:publish', ['--tag' => 'eshop-setup', '--force' => 'default']);
        Artisan::call('optimize:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');

        static::updatePackages();
        static::updateWebpackConfiguration();
        static::updateSass();
        static::updateBootstrapping();
//        static::removeNodeModules();

        $this->info('Eshop installed successfully');
        $this->comment('Please run "npm install && npm run dev" to compile your fresh scaffolding.');
    }
}