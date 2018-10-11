<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;

use Webpatser\Countries\Countries;

class MemberTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp()
    {
        /**
         * This disables the exception handling to display the stacktrace on the console
         * the same way as it shown on the browser
         */
        parent::setUp();
        //$this->withoutExceptionHandling();
    }

    /**
     * Fail to access members without authorization
     */
    public function test_it_cant_access_without_authorization() {
        $response = $this->get('/members');

        $response->assertStatus(302);
    }
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_it_can_get_the_members()
    {
        $user = factory(User::class)->create();
        $response = $this->actingAs($user)
                         ->get('/members');

        $response->assertStatus(200);
    }

    /**
     * Post a new member with no errors 
     */
    public function test_it_can_post_a_new_member() {
        $member = factory(\App\Member::class)->make();
        $phones = factory(\App\Phone::class, 10)->make();

        $response = $this->postNewMember($member, $phones)
                        ->assertStatus(302);
        
        $this->assertDatabaseHas('members', $member->attributesToArray());
        foreach($phones as $phone) {
            $this->assertDatabaseHas('phones', $phone->attributesToArray());
        }
    }

    public function test_it_can_post_a_new_member_with_dash_and_spaces() {
        $member = factory(\App\Member::class)->states('space-and-dash')->make();

        $response = $this->postNewMember($member)
                        ->assertStatus(302);
        
        $this->assertDatabaseHas('members', $member->attributesToArray());
    }

    /**
     * Post a new member with errors on each field
     */
    public function test_it_cant_post_an_existing_member() {
        $member = factory(\App\Member::class)->make();
        
        $this->postNewMember($member)
            ->assertStatus(302);

        $this->assertDatabaseHas('members', $member->attributesToArray());

        $this->postNewMember($member)
            ->assertStatus(422);
    }

    public function test_it_cant_post_a_member_with_a_wrong_birthdate() {
        $member = factory(\App\Member::class)->make();

        $this->postNewMember($member, null, false)
            ->assertStatus(422);
    }

    /**
     * Update a new member
     */
    public function test_it_can_update_a_member() {
        $member = factory(\App\Member::class)->create();
        $phone = factory(\App\Phone::class)->make();
        $member->phones()->save($phone);
        
        $phone->number = '1-111-111-111';
        $member->lastname = "last";
        $member->phones[0] = $phone;

        $this->postUpdateMember($member, [$phone])
            ->assertStatus(302); 

        $this->assertDatabaseHas('members', $member->attributesToArray());
        $this->assertDatabaseHas('phones', $phone->attributesToArray());
    }

    /**
     * Errors when updating a new member
     */
    public function test_it_cant_update_a_member_with_en_empty_lastname() {
        $member = factory(\App\Member::class)->create();

        $member->lastname="";

        $this->postUpdateMember($member)
            ->assertStatus(422); 

        $this->assertDatabaseMissing('members', $member->attributesToArray());
    }

    /**
     * No error when we update an existing member on field others than lastname, firstname and birthdate
     */
    public function test_it_can_update_a_member_without_changing_the_unicity() {
        $member = factory(\App\Member::class)->create();

        $member->email="toto@mail.com";

        $this->postUpdateMember($member)
            ->assertStatus(302); 

        $this->assertDatabaseHas('members', $member->attributesToArray());
    }

    public function test_it_cant_update_a_member_that_not_exist() {
        $member = factory(\App\Member::class)->make();
        $this->postUpdateMember($member)
            ->assertStatus(404); 
        $this->assertDatabaseMissing('members', $member->attributesToArray());
    }

    public function test_it_can_get_the_edtest_it_member_view() {
        $member = factory(\App\Member::class)->create();
        $this->getEditRequest($member->id)
            ->assertStatus(200);
    }

    public function test_it_cant_get_the_edtest_it_member_view_with_a_wrong_id() {
        $this->getEditRequest('abc')
            ->assertStatus(404);
    }

    public function test_it_cant_get_the_edtest_it_member_view_with_not_existing_id() {
        $this->getEditRequest(1)
            ->assertStatus(404);
    }

    public function test_it_can_delete_a_member() {
        $member = factory(\App\Member::class)->create();
        $phone = factory(\App\Phone::class)->make();
        $member->phones()->save($phone);
        
        $this->getDeleteRequest($member->id)
            ->assertStatus(302);
        
        $this->assertDatabaseMissing('members', $member->attributesToArray());
        $this->assertDatabaseMissing('phones', $phone->attributesToArray());
    }

    public function test_it_cant_delete_a_member_with_a_wrong_id() {
        $this->getDeleteRequest('abc')
            ->assertStatus(404);
   }

    public function test_it_cant_get_the_delete_member_view_with_not_existing_id() {
        $this->getDeleteRequest(1)
            ->assertStatus(404);
    }

    public function test_it_can_create_a_member_with_an_address() {
        $this->withoutExceptionHandling();
        // First, we need to seed the db for the country list
        $this->artisan("db:seed");

        $member = factory(\App\Member::class)->make();
        $addresses = [[
            'street'     => '1 rue du lycée',
            'city'       => 'Rumilly',
            'post_code'  => '74150',
            'country'    => 'FRA', // ISO-3166-2 or ISO-3166-3 country code
            'is_primary' => true, // optional flag
        ],  ['street'     => '10 rue antoine gantin',
            'city'       => 'Annecy',
            'post_code'  => '74000',
            'country'    => 'FRA', // ISO-3166-2 or ISO-3166-3 country code
            'is_billing' => true, // optional flag
        ]];
        
        $response = $this->postNewMember($member, null, true, $addresses)
                        ->assertStatus(302);

        unset($addresses[0]['country']);
        unset($addresses[1]['country']);

        $this->assertDatabaseHas('addresses', $addresses[0]);
        $this->assertDatabaseHas('addresses', $addresses[1]);
    }

    public function test_it_can_update_a_member_with_a_new_address() {
        // First, we need to seed the db for the country list
        $this->artisan("db:seed");

        $member = factory(\App\Member::class)->create();
        $address = [
            'street'     => '1 rue du lycée',
            'city'       => 'Rumilly',
            'post_code'  => '74150',
            'country'    => 'FRA', // ISO-3166-2 or ISO-3166-3 country code
            'is_primary' => true, // optional flag
        ];
        $response = $this->postUpdateMember($member, null, true, [$address])
                        ->assertStatus(302);
        unset($address['country']);
        $this->assertDatabaseHas('members', $member->attributesToArray());
        $this->assertDatabaseHas('addresses', $address);
    }

    /**
     * Post the member in the url 
     */
    protected function postRequest($url, $member, $phones = null, $reformatBirthDate = true, $addresses = null) {
        $user = factory(User::class)->create();
        $array = $member->toArray();
        
        if(isset($phones)) {
            $array['phones'] = (is_array($phones)) ? $phones : $phones->toArray();
        }

        if(isset($addresses)) {
            $array['addresses'] = $addresses;
        }

        if($reformatBirthDate) {
            $array['birthdate'] = Carbon::createFromFormat('Y-m-d', $member->birthdate)->format('d/m/Y');
        }

        return $this->actingAs($user)
                    ->json('POST', $url, $array);
    }

    protected function getRequest($url) {
        $user = factory(User::class)->create();
        return $this->actingAs($user)
            ->get($url);
    }

    protected function getEditRequest($id) {
        return $this->getRequest('/members/edit/' . $id);
    }

    protected function getDeleteRequest($id) {
        return $this->getRequest('/members/delete/' . $id);
    }

    /**
     * Post a new member
     */
    protected function postNewMember($member, $phones = null, $reformatBirthDate = true, $addresses = null) {
        return $this->postRequest('/members/create', $member, $phones, $reformatBirthDate, $addresses);
    }

    /**
     * Post to update a member
     */
    protected function postUpdateMember($member, $phones = null, $reformatBirthDate = true, $addresses = null) {
        return $this->postRequest('/members/update/' . $member->id, $member, $phones, $reformatBirthDate, $addresses);
    }
}
