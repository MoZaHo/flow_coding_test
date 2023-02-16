<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class JsonTest extends TestCase
{

    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setup();
        $user = $this->generateUser();
        $this->user = $user['user'];
        $this->token = $user['token'];
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_results_return_json()
    {
        $response = $this->json(
            'GET',
            'api/users/test',
            ['Authorization' => 'Bearer ' . $this->token],
            ['Content-Type' => 'text/html']
        );

        $response->assertHeader('Content-Type', 'application/json');
        $response->assertJson([]);

        $response->assertStatus(200);
    }
}
