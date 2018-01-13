<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;


class SocialLoginTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasidTest()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
