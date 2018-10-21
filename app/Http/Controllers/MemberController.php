<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreateMemberRequest;
use App\Member;
use Carbon\Carbon;
use App\Phone;

class MemberController extends Controller {
    
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
        parent::__construct();
    }

    public function index(Request $req) {
        return view(self::MEMBERS_INDEX, [self::MEMBERS => Member::all(), self::MEMBER => new Member]);
    }

    public function create(CreateMemberRequest $req) {        
    
        $member = new Member;
        $this->requestToMember($req, $member);

        $member->save();
        $member->savePhones();
        $member->saveAddresses($req->addresses);

        return $this->redirectHome();
    }

    public function edit(Member $member) {
        $member->birthdate = Carbon::createFromFormat('Y-m-d', $member->birthdate)->format('d/m/Y');
        return view(self::MEMBERS_EDIT, [self::MEMBERS => Member::all(), self::MEMBER => $member]);
    }

    public function update(CreateMemberRequest $req, Member $member) {

        $this->requestToMember($req, $member);

        $member->save();
        $member->savePhones();
        // Delete all the addresses before saving the new ones
        $member->flushAddresses();
        $member->saveAddresses($req->addresses);

        return redirect()->route(self::MEMBERS_EDIT, [$member]);
    }

    public function delete(Member $member) {
        // Delete the phones first.
        // We don't want to del on delete cascade as this can be different regarding the DB
        $member->phones()->delete();
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
