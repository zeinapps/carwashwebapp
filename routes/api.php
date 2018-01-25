<?php

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

Route::post('login', ['uses' => 'Api\LoginController@login']);
Route::post('register', ['uses' => 'Api\LoginController@create']);
Route::group([ 'middleware' => ['auth:api']], function () {
    Route::get('user', ['uses' => 'Api\UserController@user']);
});

