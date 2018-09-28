<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::prefix('/members')->name('members.')->group(function() {
    Route::get('/', 'MemberController@index')->name('home');
    Route::post('/create', 'MemberController@create')->name('create');
    
    Route::get('/edit/{member}', 'MemberController@edit')->name('edit');
    Route::post('/update/{member}', 'MemberController@update')->name('update');
    
    Route::get('/delete/{member}', 'MemberController@delete')->name('delete');
});


