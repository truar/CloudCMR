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

Route::get('/', 'WelcomeController@index')->name('welcome');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/events', 'EventController@index')->name('events.home');
Route::post('/events/create', 'EventController@create')->name('events.create');
Route::post('/events/update/{event}', 'EventController@update')->name('events.update');
Route::get('/events/delete/{event}', 'EventController@delete')->name('events.delete');

Route::prefix('/members')->name('members.')->group(function() {
    Route::get('/', 'MemberController@index')->name('home');
    Route::post('/create', 'MemberController@create')->name('create');
    
    Route::get('/edit/{member}', 'MemberController@edit')->name('edit');
    Route::post('/update/{member}', 'MemberController@update')->name('update');
    
    Route::get('/delete/{member}', 'MemberController@delete')->name('delete');
});


