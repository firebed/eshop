<?php

namespace Eshop\Tests;

use Eshop\EshopServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Illuminate\Support\Facades\Facade;

abstract class TestCase extends OrchestraTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Facade::setFacadeApplication(app());
    }
//
//    public function makeACleanSlate()
//    {
//        Artisan::call('view:clear');
//    }
//
//    protected function getEnvironmentSetUp($app)
//    {
//        $app['config']->set('view.paths', [
//            __DIR__.'/../resources/views',
//            resource_path('views'),
//        ]);
//        $app['config']->set('app.key', 'base64:Hupx3yAySikrM2/edkZQNQHslgDWYfiBfCuSThJ5SK8=');
//
//        $app['config']->set('database.default', 'testbench');
//        $app['config']->set('database.connections.testbench', [
//            'driver'   => 'sqlite',
//            'database' => ':memory:',
//            'prefix'   => '',
//        ]);
//    }
    
    protected function getPackageProviders($app): array
    {
        return [EshopServiceProvider::class,
                LivewireServiceProvider::class];
    }
}
