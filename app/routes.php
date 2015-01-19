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

// Login routes
Route::get('login', 'LoginController@showLogin');
Route::post('login', 'LoginController@getLogin');
Route::get('register', 'LoginController@showRegistration');
Route::post('register', 'LoginController@setRegistration');
Route::get('logout', 'LoginController@logOut');
Route::get('reminder', 'LoginController@getReminder');

// Edge list routes
Route::get('upload', 'EdgeController@uploadEdgeList');
Route::post('upload', 'EdgeController@uploadedFile');
Route::get('upload/edit/{id}', 'EdgeController@getEdgeListAttributes');
Route::post('upload/edit/{id}', 'EdgeController@setEdgeListAttributes');
Route::get('upload/delete/{id}', 'EdgeController@deleteEdgeList');
Route::get('files', 'EdgeController@manageFiles');

// Job routes
Route::get('/job/new', 'JobController@createJob');
Route::post('/job/new', 'JobController@submitJob');
Route::get('/job/update', 'JobController@getUpdateJobs');
Route::post('/job/update', 'JobController@updateJobs');
// Index page
Route::get('/', array('before'=>'auth', function()
{
    return View::make('index');
}));
