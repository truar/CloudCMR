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
        $response = $this->postNewMember($member)
                        ->assertStatus(302);
        
        $this->assertDatabaseHas('members', $member->toArray());
    }

    public function testPostNewMemberWithSpaces() {
        $member = factory(\App\Member::class)->make();
        $member->lastname = "Pinchon Carron de la carrier";
        $member->firstname = "Jean-Paul";
        $response = $this->postNewMember($member)
                        ->assertStatus(302);
        
        $this->assertDatabaseHas('members', $member->toArray());
    }

    /**
     * Post a new member with errors on each field
     */
    public function testPostNewMemberWithErrorsDuplicate() {
        $member = factory(\App\Member::class)->make();
        
        $this->postNewMember($member)
            ->assertStatus(302);

        $this->assertDatabaseHas('members', $member->toArray());

        $this->postNewMember($member)
            ->assertStatus(422);
    }

    /**
     * Update a new member
     */
    public function testUpdateMember() {
        $member = factory(\App\Member::class)->create();

        $member->lastname="last";

        $this->postUpdateMember($member)
            ->assertStatus(302); 

        $this->assertDatabaseHas('members', $member->toArray());
    }

    /**
     * Errors when updating a new member
     */
    public function testUpdateMemberWithErrors() {
        $member = factory(\App\Member::class)->create();

        $member->lastname="";

        $this->postUpdateMember($member)
            ->assertStatus(422); 

        $this->assertDatabaseMissing('members', $member->toArray());
    }

    /**
     * No error when we update an existing member on field others than lastname, firstname and birthdate
     */
    public function testUpdateMemberWithNoDuplicateError() {
        $member = factory(\App\Member::class)->create();

        $member->email="toto@mail.com";

        $this->postUpdateMember($member)
            ->assertStatus(302); 

        $this->assertDatabaseHas('members', $member->toArray());
    }

    public function testUpdateMemberNotExist() {
        $member = factory(\App\Member::class)->make();
        $this->postUpdateMember($member)
            ->assertStatus(404); 
        $this->assertDatabaseMissing('members', $member->toArray());
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
        return $this->getDeleteRequest($member->id)
            ->assertStatus(302);
        
        $this->assertDatabaseMissing('members', $member->toArray());
    }

    public function testDeleteMemberNotInteger() {
        return $this->getDeleteRequest('abc')
            ->assertStatus(404);
   }

    public function testDeleteMemberNotExists() {
        return $this->getDeleteRequest(1)
            ->assertStatus(404);
    }

    /**
     * Post the member in the url 
     */
    protected function postRequest($url, $member) {
        $user = factory(User::class)->create();
        $birthdate = Carbon::createFromFormat('Y-m-d', $member->birthdate)->format('d/m/Y');
        return $this->actingAs($user)
                    ->json('POST', $url, [
                        'lastname' => $member->lastname,
                        'firstname' => $member->firstname,
                        'birthdate' => $birthdate,
                        'email' => $member->email,
                        'gender' => $member->gender
                ]);
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
    protected function postNewMember($member) {
        return $this->postRequest('/members/create', $member);
    }

    /**
     * Post to update a member
     */
    protected function postUpdateMember($member) {
        return $this->postRequest('/members/update/' . $member->id, $member);
    }
}
