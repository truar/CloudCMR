<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

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
    public function testGetMembersNoAuth() {
        $response = $this->get('/members');

        $response->assertStatus(302);
    }
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetMembers()
    {
        $user = factory(User::class)->create();
        $response = $this->actingAs($user)
                         ->get('/members');

        $response->assertStatus(200);
    }

    /**
     * Post a new member with no errors 
     */
    public function testPostNewMember() {
        $member = factory(\App\Member::class)->make();
        $phones = factory(\App\Phone::class, 10)->make();
        $adress = factory(\App\Adress::class)->make();

        $response = $this->postNewMember($member, $phones)
                        ->assertStatus(302);
        
        $this->assertDatabaseHas('members', $member->attributesToArray());
        foreach($phones as $phone) {
            $this->assertDatabaseHas('phones', $phone->attributesToArray());
        }
    }

    public function testPostNewMemberWithSpacesAndDash() {
        $member = factory(\App\Member::class)->make();
        $member->lastname = "Pinchon Carron de la carrier";
        $member->firstname = "Jean-Paul";
        $response = $this->postNewMember($member)
                        ->assertStatus(302);
        
        $this->assertDatabaseHas('members', $member->attributesToArray());
    }

    /**
     * Post a new member with errors on each field
     */
    public function testPostNewMemberWithErrorsDuplicate() {
        $member = factory(\App\Member::class)->make();
        
        $this->postNewMember($member)
            ->assertStatus(302);

        $this->assertDatabaseHas('members', $member->attributesToArray());

        $this->postNewMember($member)
            ->assertStatus(422);
    }

    public function testPostNewMemberErrorOnBirthdate() {
        $member = factory(\App\Member::class)->make();
        $member->birthdate = '1992-11-23';
        $user = factory(User::class)->create();
        $this->actingAs($user)
                    ->json('POST', '/members/create', [
                        'lastname' => $member->lastname,
                        'firstname' => $member->firstname,
                        'birthdate' => $member->birthdate,
                        'email' => $member->email,
                        'gender' => $member->gender
                ])
                ->assertStatus(422);
    }

    /**
     * Update a new member
     */
    public function testUpdateMember() {
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
    public function testUpdateMemberWithErrors() {
        $member = factory(\App\Member::class)->create();

        $member->lastname="";

        $this->postUpdateMember($member)
            ->assertStatus(422); 

        $this->assertDatabaseMissing('members', $member->attributesToArray());
    }

    /**
     * No error when we update an existing member on field others than lastname, firstname and birthdate
     */
    public function testUpdateMemberWithNoDuplicateError() {
        $member = factory(\App\Member::class)->create();

        $member->email="toto@mail.com";

        $this->postUpdateMember($member)
            ->assertStatus(302); 

        $this->assertDatabaseHas('members', $member->attributesToArray());
    }

    public function testUpdateMemberNotExist() {
        $member = factory(\App\Member::class)->make();
        $this->postUpdateMember($member)
            ->assertStatus(404); 
        $this->assertDatabaseMissing('members', $member->attributesToArray());
    }

    public function testGetEditMember() {
        $member = factory(\App\Member::class)->create();
        $this->getEditRequest($member->id)
            ->assertStatus(200);
    }

    public function testGetEditMemberNotInteger() {
        $this->getEditRequest('abc')
            ->assertStatus(404);
    }

    public function testGetEditMemberNotExist() {
        $this->getEditRequest(1)
            ->assertStatus(404);
    }

    public function testDeleteMember() {
        $member = factory(\App\Member::class)->create();
        $phone = factory(\App\Phone::class)->make();
        $member->phones()->save($phone);
        
        $this->getDeleteRequest($member->id)
            ->assertStatus(302);
        
        $this->assertDatabaseMissing('members', $member->attributesToArray());
        $this->assertDatabaseMissing('phones', $phone->attributesToArray());
    }

    public function testDeleteMemberNotInteger() {
        $this->getDeleteRequest('abc')
            ->assertStatus(404);
   }

    public function testDeleteMemberNotExists() {
        $this->getDeleteRequest(1)
            ->assertStatus(404);
    }

    /**
     * Post the member in the url 
     */
    protected function postRequest($url, $member, $phones = null) {
        $user = factory(User::class)->create();
        $birthdate = Carbon::createFromFormat('Y-m-d', $member->birthdate)->format('d/m/Y');
        $array = [
            'lastname' => $member->lastname,
            'firstname' => $member->firstname,
            'birthdate' => $birthdate,
            'email' => $member->email,
            'gender' => $member->gender
        ];

        if(isset($phones)) {
            $index = 0;
            foreach($phones as $phone) {
                $array['phones'][$index++] = $phone->number;
            }
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
    protected function postNewMember($member, $phones = null) {
        return $this->postRequest('/members/create', $member, $phones);
    }

    /**
     * Post to update a member
     */
    protected function postUpdateMember($member, $phones = null) {
        return $this->postRequest('/members/update/' . $member->id, $member, $phones);
    }
}
