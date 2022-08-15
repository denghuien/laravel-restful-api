<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['middleware' => ['api.services', 'api.response']], function(){
    Route::group(['prefix' => 'passport'], function () {
        Route::post('/login', 'PassportController@login');
        Route::post('/register', 'PassportController@register');
    });
});
Route::group(['middleware' => ['api.authenticate', 'api.services', 'api.response']], function(){
    Route::group(['prefix' => 'passport'], function () {
        Route::get('/', 'PassportController@index');
        Route::any('/logout', 'PassportController@logout');
        Route::post('/password/update', 'PassportController@passwordUpdate');
    });
});
