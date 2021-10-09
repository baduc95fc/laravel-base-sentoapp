<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
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
//login app
Route::post('login', [
    'uses' => 'ApiProcessingAppController@login'
]);
//register app
Route::post('register', [
    'uses' => 'ApiProcessingAppController@register'
]);
//get user's profile
Route::get('profile', [
    'uses' => 'ApiProcessingAppController@profile',
    'middleware' => 'auth.jwt'
]);
//get logout
Route::get('logout', [
    'uses' => 'ApiProcessingAppController@logout',
    'middleware' => 'auth.jwt'
]);
Route::post('login/facebook', [
    'uses' => 'ApiProcessingAppController@loginWithFacebook'
]);
Route::post('login/google', [
    'uses' => 'ApiProcessingAppController@loginWithGoogle'
]);
