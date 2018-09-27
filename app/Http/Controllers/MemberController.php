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
        return view('members.index', ['members' => Member::all(), 'newMember' => new Member]);
    }

    public function create(CreateMemberRequest $req) {
        // Validate the unicity of a member
        
        $member = new Member;
        $member->lastname = $req->lastname;
        $member->firstname = $req->firstname;
        $member->email = $req->email;
        $member->birthdate = Carbon::createFromFormat('d/m/Y', $req->birthdate);
        $member->gender = $req->gender;

        $member->save();

        return redirect()->route('member.home');
    }

}
