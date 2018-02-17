<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VehiclePostApiTest extends TestCase
{

    /**
     * test post request with ["modelYear" => 2015, "manufacturer" => "Audi", "model" => "A3"]
     * @return void
     */
    public function testPostRequest()
    {
        $response = $this->json('POST', '/vehicles', ["modelYear" => 2015, "manufacturer" => "Audi", "model" => "A3"], []);
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');

        $this->assertArrayHasKey('Count', $response->original);
        $this->assertArrayHasKey('Results', $response->original);

        $this->assertArrayHasKey('Description', $response->original['Results'][0]);
        $this->assertArrayHasKey('VehicleId', $response->original['Results'][0]);
        $this->assertArrayNotHasKey('CrashRating', $response->original['Results'][0]);
    }

    /**
     * test post request with ["manufacturer" => "Audi", "model" => "A3"]
     * @return void
     */
    public function testPostRequestWithoutModelYear()
    {
        $response = $this->json('POST', '/vehicles', ["manufacturer" => "Audi", "model" => "A3"], []);
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');

        $this->assertArrayHasKey('Count', $response->original);
        $this->assertArrayHasKey('Results', $response->original);

        $this->assertTrue(($response->original['Count'] == 0), 'Count should be zero');
        $this->assertTrue(empty($response->original['Results']), 'Result should be empty');
    }
}
