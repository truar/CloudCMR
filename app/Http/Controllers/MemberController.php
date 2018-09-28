<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreateMemberRequest;
use Validator;
use Illuminate\Validation\Rule;
use App\Member;
use Carbon\Carbon;

class MemberController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    public function index(Request $req) {
        return view('members.index', ['members' => Member::all(), 'member' => new Member]);
    }

    public function create(CreateMemberRequest $req) {        
        
        $member = new Member;
        $this->requestToMember($req, $member);
        $member->save();

        return $this->redirectHome();
    }

    public function edit(Member $member) {
        return view('members.edit', ['members' => Member::all(), 'member' => $member]);
    }

    public function update(CreateMemberRequest $req, Member $member) {

        $this->requestToMember($req, $member);
        $member->save();

        return $this->redirectHome();
    }

    public function delete(Member $member) {
        $member->delete();
        return $this->redirectHome();
    }

    protected function requestToMember($req, $member) {
        $member->lastname = $req->lastname;
        $member->firstname = $req->firstname;
        $member->email = $req->email;
        $member->birthdate = Carbon::createFromFormat('d/m/Y', $req->birthdate);
        $member->gender = $req->gender;
    }

    protected function redirectHome() {
        return redirect()->route('members.home');
    }

}
