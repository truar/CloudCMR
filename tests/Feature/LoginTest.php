<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use CloudCMR\User;

class LoginTest extends TestCase
{

    protected function setUp() {
        parent::setUp();
        $this->requester = new Requester($this);
        $this->user = factory(User::class)->create();
        //$this->withoutExceptionHandling();
    }


    public function test_it_redirect_to_home() {
        $response = $this->requester->getRequest($this->user, '/login', []);
        $response->assertStatus(302);
        $response->assertLocation('/');
    }
}
