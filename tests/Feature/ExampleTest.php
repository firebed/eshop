<?php

namespace Eshop\Tests\Feature;


use Eshop\Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_example_from_eshop()
    {
//        $this->app->instance('path.public', __DIR__ . '../dist');
        $this->withoutMix();
        $response = $this->get('/');
        
        $response->assertStatus(200);
    }
}
