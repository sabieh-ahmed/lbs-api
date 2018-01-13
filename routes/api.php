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
use Illuminate\Support\Facades\Route;

Route::post('register', 'Api\AuthController@register');
Route::post('login', 'Api\AuthController@login');
Route::post('forgot-password', 'Api\AuthController@forgotPassword');
Route::get('social/auth/{provider}', 'Api\AuthController@socialAuth');

Route::group(['middleware' => 'jwt.auth'], function () {
    Route::post('logout', 'Api\AuthController@logout');
    Route::post('change-password', 'Api\AuthController@changePassword');
});
