<?php

use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "Admin" middleware group. Now create something great!
|
*/

/* User */
Route::get('/', [
    'uses' => 'LoginController@index'
])->name('admin');
Route::post('checkLogin', [
    'uses' => 'LoginController@checkLogin'
]);
Route::get('dashboard', [
    'uses' => 'DashboardController@index',
    'middleware' => 'admin'
]);
Route::get('logout', [
    'uses' => 'LoginController@logout',
    'middleware' => 'admin'
]);
/*Route::get('/admin/forgot', [
    'uses' => 'LoginController@forgot',
]);*/
Route::post('sendForgot', [
    'uses' => 'LoginController@sendForgot',
]);
Route::post('sendForgot', [
    'uses' => 'LoginController@sendForgot',
]);
Route::post('resetPassword', [
    'uses' => 'LoginController@resetPassword',
]);
Route::get('resetPassword', [
    'uses' => 'LoginController@resetPassword',
]);
Route::get('profile', [
    'uses' => 'UserController@profile',
    'middleware' => 'admin'
]);
Route::post('updateProfile', [
    'uses' => 'UserController@updateProfile',
    'middleware' => 'admin'
]);
/* End user */
/* app config */
Route::get('appConfig', [
    'uses' => 'AppConfigController@index',
    'middleware' => 'admin'
]);
Route::post('updateAppConfig', [
    'uses' => 'AppConfigController@updateAppConfig',
    'middleware' => 'admin'
]);
/* end app config */
/* administrator */
Route::get('administrator', [
    'uses' => 'UserController@index',
    'middleware' => 'admin'
]);
Route::post('getDataUserAdministrator', [
    'uses' => 'UserController@getDataUserAdministrator',
    'middleware' => 'admin'
]);
Route::get('viewDetailMember/{id}', [
    'uses' => 'UserController@viewDetailMember',
    'middleware' => 'admin'
]);
Route::post('saveAdministrator', [
    'uses' => 'UserController@store',
    'middleware' => 'admin'
]);
Route::get('getDataAdministratorById/{id}', [
    'uses' => 'UserController@edit',
    'middleware' => 'admin'
]);
Route::post('updateAdministrator/{id}', [
    'uses' => 'UserController@update',
    'middleware' => 'admin'
]);
Route::post('deleteAdministrator/{id}', [
    'uses' => 'UserController@delete',
    'middleware' => 'admin'
]);
Route::post('updateStatusAdministrator', [
    'uses' => 'UserController@updateStatus',
    'middleware' => 'admin'
]);
Route::post('changePasswordAdmin/{id}', [
    'uses' => 'UserController@changePasswordAdmin',
    'middleware' => 'admin'
]);
/* end administrator */

/* member */
Route::get('member', [
    'uses' => 'UserController@member',
    'middleware' => 'admin'
]);
Route::post('getDataUserMember', [
    'uses' => 'UserController@getDataUserMember',
    'middleware' => 'admin'
]);
Route::post('updateStatusMember', [
    'uses' => 'UserController@updateStatusMember',
    'middleware' => 'admin'
]);
Route::get('searchUserAutoComplete', [
    'uses' => 'UserController@searchUserAutoComplete',
    'middleware' => 'admin'
]);
/* end member */

