<?php

namespace Tests\Feature;

use Tests\TestCase;

class TaniLinkRoutesTest extends TestCase
{
    public function test_all_tanilink_routes_render_successfully(): void
    {
        $routes = ['/', '/petani', '/horeka', '/kurir'];

        foreach ($routes as $route) {
            $response = $this->get($route);
            $response->assertStatus(200);
        }
    }
}
