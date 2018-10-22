<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Member;
use Lecturize\Addresses\Models\Address;
use App\Http\Controllers\Controller;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Member $member, Address $address)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Member $member, Address $address)
    {
        if($address->addressable_id != $member->id) {
            return response()->json(['errors' => ['message' => 'The member ' . $member->id . ' has no address ' . $address->id]], 404);
        }
        $address->delete();
        return response()->json(['data' => $address->toArray(), 'message' => 'Address deleted'], 200);
    }
}
