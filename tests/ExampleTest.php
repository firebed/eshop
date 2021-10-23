<?php

namespace Eshop\Tests;

use Eshop\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_example_from_eshop()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
