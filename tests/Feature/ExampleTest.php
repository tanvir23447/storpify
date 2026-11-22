<?php

namespace Tests\Feature;

use Database\Seeders\HomepageCmsSeeder;
use Database\Seeders\MainStoreSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_application_returns_a_successful_response(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);
        $this->seed(MainStoreSeeder::class);
        $this->seed(HomepageCmsSeeder::class);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('STORPIFY');
    }
}
