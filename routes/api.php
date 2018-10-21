<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::middleware('auth:api')->get('/user', 'ApiController@index');

Route::prefix('/members/{member}/phones')->name('phones.')->group(function() {
    Route::put('/update/{phone}', 'Api\PhoneController@update')->name('update');
    Route::delete('/delete/{phone}', 'Api\PhoneController@delete')->name('delete');

});