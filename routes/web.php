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
    
    Route::get('/edit/{id}', 'MemberController@edit')->name('edit');
    Route::post('/update/{id}', 'MemberController@update')->name('update');
    
    Route::get('/delete/{id}', 'MemberController@delete')->name('delete');
});


