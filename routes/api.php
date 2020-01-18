<?php

use Illuminate\Http\Request;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('auth')->group(function () {
    Route::post('login', 'AuthController@authenticate');
    Route::post('refresh', 'AuthController@refreshToken');
    Route::post('registration', 'User\UserController@store');
    Route::post('activationlink', 'User\UserController@createActivationLink');
});

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('/users/me', 'User\UserController@getAuthenticatedUser');
});
