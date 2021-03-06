<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\JsonResponse;

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
        $this->dbType = env('DB_CONNECTION', 'sqlite');
        // $this->withoutExceptionHandling();

        $this->user = factory(User::class)->create();
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

    public function test_it_can_post_a_new_member_with_dash_and_spaces_without_usca() {
        $member = factory(\App\Member::class)->states('space-and-dash')->make();
        unset($member->uscaNumber);
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

        if($this->isSqlite()) {
            $member->birthdate = Carbon::createFromFormat('Y-m-d H:i:s', $member->birthdate)->format('Y-m-d');
        }

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
        // First, we need to seed the db for the country list
        $this->artisan("db:seed", ['--class' => 'CountriesSeeder']);

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
        $this->artisan("db:seed", ['--class' => 'CountriesSeeder']);

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

    public function test_it_cant_update_a_member_with_an_empty_phone_number() {
        $member = factory(\App\Member::class)->create();
        $phone = factory(\App\Phone::class)->make();
        $phone->number = '';

        $response = $this->postUpdateMember($member, [$phone])
                        ->assertStatus(422);

    }

    public function test_it_can_search_a_member_contains_the_same_last_name() {
        $members = factory(\App\Member::class, 1)->make();
        $members[0]->lastname = 'RUARO';
        $members[0]->save();

        $searchText = ['searchText' => 'RUARO'];
        $response = $this->actingAs($this->user)
            ->json('POST', route('members.search'), $searchText);
        
        $jsonExpected = $members->toArray();
        $jsonExpected[0]['edit_url'] = route('members.edit', ['member' => $members[0]]);

        $response->assertStatus(200)
            ->assertJson(['data' => $jsonExpected]);
    }

    public function test_it_can_search_a_member_contains_the_same_first_name() {
        $members = factory(\App\Member::class, 1)->make();
        $members[0]->firstname = 'RUARO';
        $members[0]->save();

        $searchText = ['searchText' => 'RUARO'];
        $response = $this->actingAs($this->user)
            ->json('POST', route('members.search'), $searchText);
        
        $jsonExpected = $members->toArray();
        $jsonExpected[0]['edit_url'] = route('members.edit', ['member' => $members[0]]);

        $response->assertStatus(200)
            ->assertJson(['data' => $jsonExpected]);
    }

    public function test_it_can_search_a_member_doesnt_contain_the_same_first_name() {
        $members = factory(\App\Member::class, 1)->make();
        $members[0]->firstname = 'RUARO';
        $members[0]->save();

        $searchText = ['searchText' => 'THIBAULT'];
        $response = $this->actingAs($this->user)
            ->json('POST', route('members.search'), $searchText);
        
        $response->assertStatus(200)
            ->assertJson(['data' => []]);
    }

    public function test_it_can_search_a_member_doesnt_contain_the_same_last_name() {
        $members = factory(\App\Member::class, 1)->make();
        $members[0]->lastname = 'RUARO';
        $members[0]->save();

        $searchText = ['searchText' => 'THIBAULT'];
        $response = $this->actingAs($this->user)
            ->json('POST', route('members.search'), $searchText);
        
        $response->assertStatus(200)
            ->assertJson(['data' => []]);
    }

    public function test_it_can_search_a_member_contains_partially_the_first_name_at_the_beginning() {
        $members = factory(\App\Member::class, 1)->make();
        $members[0]->firstname = 'RUARO';
        $members[0]->save();

        $searchText = ['searchText' => 'RU'];
        $response = $this->actingAs($this->user)
            ->json('POST', route('members.search'), $searchText);
    
        $jsonExpected = $members->toArray();
        $jsonExpected[0]['edit_url'] = route('members.edit', ['member' => $members[0]]);

        $response->assertStatus(200)
            ->assertJson(['data' => $jsonExpected]);
    }

    public function test_it_can_search_a_member_contains_partially_the_first_name_in_the_middle() {
        $members = factory(\App\Member::class, 1)->make();
        $members[0]->firstname = 'RUARO';
        $members[0]->save();

        $searchText = ['searchText' => 'UAR'];
        $response = $this->actingAs($this->user)
            ->json('POST', route('members.search'), $searchText);
        
        $jsonExpected = $members->toArray();
        $jsonExpected[0]['edit_url'] = route('members.edit', ['member' => $members[0]]);

        $response->assertStatus(200)
            ->assertJson(['data' => $jsonExpected]);
    }

    public function test_it_can_search_a_member_contains_partially_the_first_name_at_the_end() {
        $members = factory(\App\Member::class, 1)->make();
        $members[0]->firstname = 'RUARO';
        $members[0]->save();

        $searchText = ['searchText' => 'RO'];
        $response = $this->actingAs($this->user)
            ->json('POST', route('members.search'), $searchText);
        
        $jsonExpected = $members->toArray();
        $jsonExpected[0]['edit_url'] = route('members.edit', ['member' => $members[0]]);

        $response->assertStatus(200)
            ->assertJson(['data' => $jsonExpected]);
    }

    public function test_it_can_search_a_member_contains_partially_the_last_name_at_the_beginning() {
        $this->withoutExceptionHandling();
        $members = factory(\App\Member::class, 1)->make();
        $members[0]->lastname = 'RUARO';
        $members[0]->save();

        $searchText = ['searchText' => 'RU'];
        $response = $this->actingAs($this->user)
            ->json('POST', route('members.search'), $searchText);
        
        $jsonExpected = $members->toArray();
        $jsonExpected[0]['edit_url'] = route('members.edit', ['member' => $members[0]]);

        $response->assertStatus(200)
            ->assertJson(['data' => $jsonExpected]);
    }

    public function test_it_can_search_a_member_contains_partially_the_last_name_in_the_middle() {
        $members = factory(\App\Member::class, 1)->make();
        $members[0]->lastname = 'RUARO';
        $members[0]->save();

        $searchText = ['searchText' => 'UAR'];
        $response = $this->actingAs($this->user)
            ->json('POST', route('members.search'), $searchText);
        
        $jsonExpected = $members->toArray();
        $jsonExpected[0]['edit_url'] = route('members.edit', ['member' => $members[0]]);

        $response->assertStatus(200)
            ->assertJson(['data' => $jsonExpected]);
    }

    public function test_it_can_search_a_member_contains_partially_the_last_name_at_the_end() {
        $members = factory(\App\Member::class, 1)->make();
        $members[0]->lastname = 'RUARO';
        $members[0]->save();

        $searchText = ['searchText' => 'RO'];
        $response = $this->actingAs($this->user)
            ->json('POST', route('members.search'), $searchText);
        
        $jsonExpected = $members->toArray();
        $jsonExpected[0]['edit_url'] = route('members.edit', ['member' => $members[0]]);

        $response->assertStatus(200)
            ->assertJson(['data' => $jsonExpected]);
    }

    public function test_it_cant_search_a_member_without_search_text() {
        $members = factory(\App\Member::class, 1)->make();
        $members[0]->lastname = 'RUARO';
        $members[0]->save();

        $response = $this->actingAs($this->user)
            ->json('POST', route('members.search'), []);
        
        $response->assertStatus(JsonResponse::HTTP_BAD_REQUEST);
    }

    public function test_it_can_search_members_order_by_lastname_firstname() {
        $members = factory(\App\Member::class, 4)->make();
        $index = 0;
        $members[$index]->lastname = 'DEAF';
        $members[$index]->firstname = 'DEF';
        $members[$index++]->save();
        $members[$index]->lastname = 'DEAF';
        $members[$index]->firstname = 'CDE';
        $members[$index++]->save();
        $members[$index]->lastname = 'ABC';
        $members[$index]->firstname = 'EFG';
        $members[$index++]->save();
        $members[$index]->lastname = 'ABC';
        $members[$index]->firstname = 'ABC';
        $members[$index++]->save();

        $jsonExpected = [$members[3]->toArray(), $members[2]->toArray(), $members[1]->toArray(), $members[0]->toArray()];
        $index = 0;
        $jsonExpected[$index++]['edit_url'] = route('members.edit', ['member' => $members[3]]);
        $jsonExpected[$index++]['edit_url'] = route('members.edit', ['member' => $members[2]]);
        $jsonExpected[$index++]['edit_url'] = route('members.edit', ['member' => $members[1]]);
        $jsonExpected[$index++]['edit_url'] = route('members.edit', ['member' => $members[0]]);

        $searchText = ['searchText' => 'A'];
        $response = $this->actingAs($this->user)
            ->json('POST', route('members.search'), $searchText);

        $response->assertStatus(200)
            ->assertJson(['data' => $jsonExpected]);


    }

    /**
     * Post the member in the url 
     */
    protected function postRequest($url, $member, $phones = null, $reformatBirthDate = true, $addresses = null) {
        $user = factory(User::class)->create();
        
        // We need to update the member birthdare into a datetime as sqlite don't deal with date
        if($this->isSqlite()) {
            $member->birthdate = Carbon::createFromFormat('Y-m-d', $member->birthdate)->format('Y-m-d H:i:s');
        }

        $array = $member->toArray();


        if(isset($phones)) {
            $array['phones'] = (is_array($phones)) ? $phones : $phones->toArray();
        }

        if(isset($addresses)) {
            $array['addresses'] = $addresses;
        }

        if($reformatBirthDate) {
            $format = ($this->isSqlite()) ? 'Y-m-d H:i:s' : 'Y-m-d';
            $array['birthdate'] = Carbon::createFromFormat($format, $member->birthdate)->format('d/m/Y');
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

    private function isSqlite() {
        return $this->dbType == "sqlite";
    }
}
