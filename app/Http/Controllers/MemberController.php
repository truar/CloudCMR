<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreateMemberRequest;
use Validator;
use Illuminate\Validation\Rule;
use App\Member;
use Carbon\Carbon;
use App\Phone;

class MemberController extends Controller
{
    const AUTH = 'auth';
    const MEMBERS_HOME = 'members.home';
    const MEMBERS_INDEX = 'members.index';
    const MEMBERS_EDIT = 'members.edit';
    const MEMBERS = 'members';
    const MEMBER = 'member';
    const DATE_FORMAT = 'd/m/Y';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware(self::AUTH);
    }

    public function index(Request $req) {
        return view(self::MEMBERS_INDEX, [self::MEMBERS => Member::all(), self::MEMBER => new Member]);
    }

    public function create(CreateMemberRequest $req) {        
        
        $member = new Member;
        $this->requestToMember($req, $member);

        $member->save();
        
        if(isset($req->addresses)) {
            // Loop to save the addresses
            foreach($req->addresses as $address) {
                $member->addAddress($address);
            }
        }

        if(isset($member->phones[0])) {
            foreach($member->phones as $phone) {
                $member->phones()->save($phone);
            }
        }

        return $this->redirectHome();
    }

    public function edit(Member $member) {
        return view(self::MEMBERS_EDIT, [self::MEMBERS => Member::all(), self::MEMBER => $member]);
    }

    public function update(CreateMemberRequest $req, Member $member) {

        $this->requestToMember($req, $member);
        $member->save();

        if(isset($member->phones[0])) {
            foreach($member->phones as $phone) {
                $member->phones()->save($phone);
            }
        }

        $member->flushAddresses();
        if(isset($req->addresses)) {
            // Loop to save the addresses
            foreach($req->addresses as $address) {
                $member->addAddress($address);
            }
        }

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
        $member->birthdate = Carbon::createFromFormat(self::DATE_FORMAT, $req->birthdate);
        $member->gender = $req->gender;
        $member->uscaNumber = $req->uscaNumber;

        if(isset($req->phones)) {
            $index = 0;
            foreach($req->phones as $phone) {
                if(!isset($member->phones[$index])) {
                    $member->phones[$index] = new Phone;
                }
                $member->phones[$index++]->number = $phone['number'];
            }
        }
    }

    protected function redirectHome() {
        return redirect()->route(self::MEMBERS_HOME);
    }

}
