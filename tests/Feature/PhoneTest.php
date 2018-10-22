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
        //$this->artisan('db:seed', ['--class' => 'UserAdminSeeder']);

        $this->requester = new Requester($this);
        $this->user = factory(User::class)->create();
        $this->user->assignRole('admin');
        $this->member = factory(Member::class)->create();
        $this->phone = factory(Phone::class)->make();
        $this->member->phones()->save($this->phone);
        //$this->withoutExceptionHandling();
    }

    public function test_it_cant_update_when_no_authenticated() {
        $this->put(route('phones.update', ['member' => $this->member, 'phone' => 1]), [])
            ->assertStatus(302);
    }

    public function test_it_can_update_a_phone_number() {
        $phone = $this->phone;
        $phone->number = '12345';
        $response = $this->requester->putRequest($this->user, route('phones.update', ['member' => $this->member, 'phone' => $phone]), $phone->toArray());
        $response->assertStatus(200);
        $this->assertDatabaseHas('phones', $phone->toArray());
        $response->assertJson(['data' => $phone->toArray()]);
    }

    public function test_it_cant_update_without_phone_number() {
        $phone = $this->phone;
        $data = $phone->toArray();
        $data['number'] = '';
        $response = $this->requester->putRequest($this->user, route('phones.update', ['member' => $this->member, 'phone' => $phone]), $data);
        $response->assertStatus(400)
            ->assertExactJson(['errors' => ['number' => ['Le numéro de téléphone est obligatoire']]]);
        $this->assertDatabaseHas('phones', $this->phone->toArray());
    }

    public function test_it_can_delete_a_phone_number() {
        $phone = $this->phone;
        $response = $this->requester->deleteRequest($this->user, route('phones.delete', ['member' => $this->member, 'phone' => $phone]), []);
        $response->assertStatus(200)
            ->assertJson(['data' => $phone->toArray(), 'message' => 'Phone deleted']);
        $this->assertDatabaseMissing('phones', $phone->toArray());
    }

    public function test_it_cant_delete_a_phone_that_doesnt_belong_to_the_member() {
        $newMember = factory(Member::class)->create();
        $newPhone = factory(Phone::class)->make();
        $newMember->phones()->save($newPhone);

        $response = $this->requester->deleteRequest($this->user, route('phones.delete', ['member' => $this->member, 'phone' => $newPhone]), []);
        $response->assertStatus(404)
            ->assertJson(['errors' => ['message' => 'The member ' . $this->member->id . ' has no phone ' . $newPhone->id]]);
        $this->assertDatabaseHas('phones', $newPhone->toArray());

    }

    public function test_it_cant_update_a_phone_that_doesnt_belong_to_the_member() {
        $newMember = factory(Member::class)->create();
        $newPhone = factory(Phone::class)->make();
        $newMember->phones()->save($newPhone);

        $data = $newPhone->toArray();
        $data['number'] = '12345';

        $response = $this->requester->putRequest($this->user, route('phones.update', ['member' => $this->member, 'phone' => $newPhone]), $data);
        $response->assertStatus(404)
            ->assertJson(['errors' => ['message' => 'The member ' . $this->member->id . ' has no phone ' . $newPhone->id]]);
        $this->assertDatabaseHas('phones', $newPhone->toArray());

    }
}
