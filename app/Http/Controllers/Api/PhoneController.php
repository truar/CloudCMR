<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Phone;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePhoneRequest;

class PhoneController extends Controller
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
     * @param  Phone $phone
     * @return \Illuminate\Http\Response
     */
    public function show(Phone $phone)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Phone $phone
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePhoneRequest $request, Phone $phone)
    {
        $phone->number = $request->number;
        $phone->save();
        return response()->json(['data' => $phone->toArray()], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Phone $phone
     * @return \Illuminate\Http\Response
     */
    public function delete(Phone $phone)
    {
        $phone->delete();
        return response()->json(['data' => $phone->toArray(), 'message' => 'Phone deleted'], 200);
    }
}
