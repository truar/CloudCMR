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
        $response = $this->postRequest($member)
                        ->assertStatus(302);
    }

    /**
     * Post a new member with errors on each field
     */
    public function testPostNewMemberWithErrorsDuplicate() {
        $member = factory(\App\Member::class)->make();
        
        $this->postRequest($member)
            ->assertStatus(302);

        $this->postRequest($member)
            ->assertStatus(422);
        
    }

    protected function postRequest($member) {
        $user = factory(User::class)->create();
        $birthdate = Carbon::createFromFormat('Y-m-d', $member->birthdate)->format('d/m/Y');
        return $this->actingAs($user)
                    ->json('POST', '/members/create', [
                        'lastname' => $member->lastname,
                        'firstname' => $member->firstname,
                        'birthdate' => $birthdate,
                        'email' => $member->email,
                        'gender' => $member->gender
                ]);
    }
}
