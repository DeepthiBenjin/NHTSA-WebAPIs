<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class VehicleGetApiTest
 * @package Tests\Feature
 */
class VehicleGetApiTest extends TestCase
{
    /**
     * test get request with /vehicles/2015/Audi/A3
     * @return void
     */
    public function testGetRequest()
    {
        $response = $this->json('GET', '/vehicles/2015/Audi/A3', [], []);
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');

        $this->assertArrayHasKey('Count', $response->original);
        $this->assertArrayHasKey('Results', $response->original);

        $this->assertArrayHasKey('Description', $response->original['Results'][0]);
        $this->assertArrayHasKey('VehicleId', $response->original['Results'][0]);
        $this->assertArrayNotHasKey('CrashRating', $response->original['Results'][0]);
    }

    /**
     * test get request with /vehicles/2015/Audi/A3?withRating=true
     * @return void
     */
    public function testGetRequestWithRatingEqualToTrue()
    {
        $response = $this->json('GET', '/vehicles/2015/Audi/A3?withRating=true', [], []);
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');

        $this->assertArrayHasKey('Count', $response->original);
        $this->assertArrayHasKey('Results', $response->original);

        $this->assertArrayHasKey('Description', $response->original['Results'][0]);
        $this->assertArrayHasKey('VehicleId', $response->original['Results'][0]);
        $this->assertArrayHasKey('CrashRating', $response->original['Results'][0]);
    }

    /**
     * test get request with /vehicles/2015/Audi/A3?withRating=false
     * @return void
     */
    public function testGetRequestWithRatingEqualToFalse()
    {
        $response = $this->json('GET', '/vehicles/2015/Audi/A3?withRating=false', [], []);
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');

        $this->assertArrayHasKey('Count', $response->original);
        $this->assertArrayHasKey('Results', $response->original);

        $this->assertArrayHasKey('Description', $response->original['Results'][0]);
        $this->assertArrayHasKey('VehicleId', $response->original['Results'][0]);
        $this->assertArrayNotHasKey('CrashRating', $response->original['Results'][0]);
    }

    /**
     * test get request with /vehicles/2015/Audi/A3?withRating=bananas, random characters
     * @return void
     */
    public function testGetRequestWithRatingEqualToAnyString()
    {
        $response = $this->json('GET', '/vehicles/2015/Audi/A3?withRating=bananas', [], []);
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');

        $this->assertArrayHasKey('Count', $response->original);
        $this->assertArrayHasKey('Results', $response->original);

        $this->assertArrayHasKey('Description', $response->original['Results'][0]);
        $this->assertArrayHasKey('VehicleId', $response->original['Results'][0]);
        $this->assertArrayNotHasKey('CrashRating', $response->original['Results'][0]);
    }
}
