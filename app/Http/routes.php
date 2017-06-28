<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'HomeController@index')->name('home');
Route::get('/alert', function() {
    return redirect()->route('home')->with('info', 'You have sign up!');
});


// Authentication
Route::get('/signup', 'AuthController@getSignup')->name('auth.signup')->middleware('guest'); // guest is not able to singup
Route::post('/signup', 'AuthController@postSignup')->middleware('guest');

Route::get('/signin', 'AuthController@getSignin')->name('auth.signin')->middleware('guest');
Route::post('/signin', 'AuthController@postSignin')->middleware('guest');

Route::get('/signout', 'AuthController@getSignout')->name('auth.signout');


// Search
Route::get('/search', 'SearchController@getResults')->name('search.results');

// User profile
Route::get('/user/{username}', 'ProfileController@getProfile')->name('profile.index');

// you need to be sign in to access these two routes
Route::get('/profile/edit', 'ProfileController@getEdit')->name('profile.edit')->middleware('auth');
Route::post('/profile/edit', 'ProfileController@postEdit')->name('profile.edit')->middleware('auth');

