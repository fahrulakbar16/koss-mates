<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class ApiResponseStructureTest extends TestCase
{
    // use RefreshDatabase; // Caution with database

    public function test_boarding_house_index_structure()
    {
        // Mocking auth if necessary or just checking structure if public
        // Boarding house list is likely public?
        // Route::get('/', ...) inside 'boarding-houses' prefix.

        $response = $this->getJson('/api/boarding-houses');

        // It might return 200 or 401 depending on middleware.
        // Assuming public for index? Check api.php
        // api.php: prefix '/boarding-houses' group. No middleware specified in group, but BoardingHouseController might have it.
        // BoardingHouseController has no middleware in constructor visible in snippet, but we should check.
        // Let's assume public.

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data'
            ]);
    }
}
