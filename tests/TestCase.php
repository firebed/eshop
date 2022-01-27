<?php

namespace Eshop\Tests;

use Eshop\EshopServiceProvider;
use Firebed\Components\BootstrapServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Facade;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        Facade::setFacadeApplication(app());
    }

    public function makeACleanSlate()
    {
        Artisan::call('view:clear');
    }

    protected function getEnvironmentSetUp($app)
    {
//        $app['config']->set('view.paths', [
//            __DIR__ . '/../resources/views',
//            resource_path('views'),
//        ]);
//
//        $app['config']->set('app.key', 'base64:Hupx3yAySikrM2/edkZQNQHslgDWYfiBfCuSThJ5SK8=');

        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver'         => 'mysql',
            'host'           => env('DB_HOST', '127.0.0.1'),
            'port'           => env('DB_PORT', '3306'),
            'database'       => 'eshop_testing',
            'username'       => env('DB_USERNAME', 'forge'),
            'password'       => env('DB_PASSWORD', ''),
            'charset'        => 'utf8mb4',
            'collation'      => 'utf8mb4_unicode_ci',
            'prefix'         => '',
            'prefix_indexes' => true,
            'strict'         => true,
            'engine'         => null,
        ]);
    }

    protected function getPackageProviders($app): array
    {
        return [EshopServiceProvider::class,
                LivewireServiceProvider::class,
                BootstrapServiceProvider::class
        ];
    }
}
