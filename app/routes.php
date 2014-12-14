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

Route::get('login', 'LoginController@showLogin');
Route::post('login', 'LoginController@getLogin');
Route::get('register', 'LoginController@showRegistration');
Route::post('register', 'LoginController@setRegistration');
Route::get('logout', 'LoginController@logOut');

Route::get('/', 'MainController@indexPage');
Route::get('upload', 'MainController@uploadEdgeList');
Route::post('upload', 'MainController@uploadedFile');
//Route::get('upload-2', 'MainController@setEdgeList');


