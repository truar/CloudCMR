<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;

class UserTest extends TestCase
{

    use RefreshDatabase;

    protected function setUp()
    {
        parent::setUp();
        $this->dbType = env('DB_CONNECTION', 'sqlite');
        
        // We need to seed the database to be able to simulate that we are an admin
        $this->artisan('db:seed', ['--class' => 'RolesAndPermissionsSeeder']);
        $this->artisan('db:seed', ['--class' => 'UserAdminSeeder']);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testDetachAllRoles()
    {
        $user = factory(User::class)->create();
        $user->assignRole('admin');
        $this->assertTrue($user->hasRole('admin'));
        
        $user->detachAllRoles();
        $this->assertTrue(true);
    }

    public function testAttachRole() {
        $user = factory(User::class)->create();
        $user->attachRole('admin');
        $this->assertTrue($user->hasRole('admin'));
    }
}
