<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_the_home_page_is_public(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Dashboard luaran');
    }
}
