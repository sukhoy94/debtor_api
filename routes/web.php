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

Route::get('/login', function() {
   return view('users.login');
})->name('login');

Route::get('/register', function() {
    return view('users.register');
})->name('register');


Route::get('/auth/activations-link/{token?}', 'User\UserController@verifyUser')->name('user.verify');
