<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use CloudCMR\User;

class DashboardTest extends TestCase
{

    protected function setUp() {
        parent::setUp();
        $this->requester = new Requester($this);
        $this->user = factory(User::class)->create();
        //$this->withoutExceptionHandling();
    }

    /**
     * Fail to access members without authorization
     */
    public function test_it_cant_access_without_authorization() {
        $response = $this->get('/');
        $response->assertStatus(302);
    }

    public function test_it_can_access_with_auth() {
        $response = $this->requester->getRequest($this->user, '/', []);
        $response->assertOk();
    }
}
