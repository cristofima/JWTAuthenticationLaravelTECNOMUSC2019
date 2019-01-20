<?php

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', 'ApiController@login');
Route::post('register', 'ApiController@register');

Route::group(['middleware' => 'auth.jwt'], function () {
    Route::post('logout', 'ApiController@logout');
    Route::get('user', 'ApiController@getAuthUser');
    Route::get('activities', 'ActivityController@index');
    Route::get('activities/{id}', 'ActivityController@show');
    Route::post('activities', 'ActivityController@store');
    Route::put('activities/{id}', 'ActivityController@update');
    Route::delete('activities/{id}', 'ActivityController@destroy');
});
