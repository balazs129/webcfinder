<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::pattern('id', '[0-9]+');

Route::get('login', 'LoginController@showLogin');
Route::post('login', 'LoginController@getLogin');
Route::get('register', 'LoginController@showRegistration');
Route::post('register', 'LoginController@setRegistration');
Route::get('logout', 'LoginController@logOut');
Route::get('reminder', 'LoginController@getReminder');

Route::get('upload', 'EdgeController@uploadEdgeList');
Route::post('upload', 'EdgeController@uploadedFile');
Route::get('upload/{id}', 'EdgeController@getEdgeListAttributes');
Route::post('upload/{id}', 'EdgeController@setEdgeListAttributes');
Route::get('files', 'EdgeController@manageFiles');

Route::get('/', function()
{
    return View::make('index');
});
