<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Member;
use App\User;
use App\Phone;

class PhoneTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp()
    {
        /**
         * This disables the exception handling to display the stacktrace on the console
         * the same way as it shown on the browser
         */
        parent::setUp();
        $this->dbType = env('DB_CONNECTION', 'sqlite');
        // We need to seed the database to be able to simulate that we are an admin
        $this->artisan('db:seed', ['--class' => 'RolesAndPermissionsSeeder']);
        $this->artisan('db:seed', ['--class' => 'UserAdminSeeder']);

        $this->requester = new Requester($this);
        $this->user = factory(User::class)->create();
        $this->user->assignRole('admin');
        $this->member = factory(Member::class)->create();
        $this->phone = factory(Phone::class)->make();
        $this->member->phones()->save($this->phone);
        //$this->withoutExceptionHandling();
    }

    public function test_it_cant_update_when_no_authenticated() {
        $this->put('/api/phones/update/1', [])
            ->assertStatus(302);
    }

    public function test_it_can_update_a_phone_number() {
        $phone = $this->phone;
        $phone->number = '12345';
        $response = $this->requester->putRequest($this->user, 'api/phones/update/' . $phone->id, $phone->toArray());
        $response->assertStatus(200);
        $this->assertDatabaseHas('phones', $phone->toArray());
        $response->assertJson(['data' => $phone->toArray()]);
    }

    public function test_it_cant_update_without_phone_number() {
        $phone = $this->phone;
        $data = $phone->toArray();
        $data['number'] = '';
        $response = $this->requester->putRequest($this->user, 'api/phones/update/' . $phone->id, $data);
        $response->assertStatus(400)
            ->assertExactJson(['errors' => ['number' => ['Le numéro de téléphone est obligatoire']]]);
        $this->assertDatabaseHas('phones', $this->phone->toArray());
    }

    public function test_it_can_delete_a_phone_number() {
        $phone = $this->phone;
        $response = $this->requester->deleteRequest($this->user, 'api/phones/delete/' . $phone->id, []);
        $response->assertStatus(200)
            ->assertJson(['data' => $phone->toArray(), 'message' => 'Phone deleted']);
        $this->assertDatabaseMissing('phones', $phone->toArray());
    }
}
