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
        $member = new Member;
        $this->requestToMember($req, $member);
        $member->save();

        return redirect()->route('members.home');
    }

    public function edit($id) {
        $member = Member::find($id);

        if(!isset($member)) {
            return abort(404);
        }

        return view('members.edit', ['members' => Member::all(), 'memberToUpdate' => $member]);
    }

    public function update(CreateMemberRequest $req, $id) {
        $member = Member::find($id);
        
        if(!isset($member)) {
            return abort(404);
        }

        $this->requestToMember($req, $member);
        $member->save();

        return redirect()->route('members.home');
    }

    public function delete($id) {
        $member = Member::find($id);

        if(!isset($member)) {
            return abort(404);
        }
        $member->delete();
        return redirect()->route('members.home');
    }

    protected function requestToMember($req, $member) {
        $member->lastname = $req->lastname;
        $member->firstname = $req->firstname;
        $member->email = $req->email;
        $member->birthdate = Carbon::createFromFormat('d/m/Y', $req->birthdate);
        $member->gender = $req->gender;
        
        return $member; 
    }

}
