<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use App\Member;
use Lecturize\Addresses\Models\Address;

class AddresseTest extends TestCase
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
        $this->artisan("db:seed", ['--class' => 'CountriesSeeder']);
        
        $this->requester = new Requester($this);
        $this->user = factory(User::class)->create();
        $this->user->assignRole('admin');
        $this->member = factory(Member::class)->create();
        $address = factory(Address::class)->make();
        $this->member->saveAddresses([$address->toArray()]);
        $this->address = $this->member->addresses()->get()[0];
        // $this->withoutExceptionHandling();
    }

    public function test_it_cant_delete_when_no_authenticated() {
        $this->delete(route('addresses.delete', ['member' => $this->member, 'address' => 1]), [])
            ->assertStatus(302);
    }

    public function test_it_can_delete_an_address() {
        $response = $this->requester->deleteRequest($this->user, route('addresses.delete', ['member' => $this->member, 'address' => $this->address]), []);
        // We need to remove the attribute deleted_At as it is not updated and then produce an error for no reason
        // Maybe, I need to work on this part... Any idea ???
        $checkJSON = $this->address->toArray();
        unset($checkJSON['deleted_at']);
        $response->assertStatus(200)
            ->assertJson(['data' => $checkJSON, 'message' => 'Address deleted']);
        $this->assertDatabaseMissing('addresses', $this->address->toArray());
    }

    public function test_it_cant_delete_an_address_that_doesnt_belong_to_the_member() {
        $newMember = factory(Member::class)->create();

        $response = $this->requester->deleteRequest($this->user, route('addresses.delete', ['member' => $newMember, 'address' => $this->address]), []);
        $response->assertStatus(404)
            ->assertJson(['errors' => ['message' => 'The member ' . $newMember->id . ' has no address ' . $this->address->id]]);
        $this->assertDatabaseHas('addresses', $this->address->toArray());

    }
}
