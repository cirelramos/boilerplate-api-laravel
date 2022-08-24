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

/*================ PLAYERS ================*/
Route::resource('players', 'Players\Controllers\PlayerController');

/*================ TEAMS ================*/
Route::resource('teams', 'Teams\Controllers\TeamController');

/*================ OAUTH ================*/
Route::post('oauth/login', 'Auth\Controllers\LoginController@login')->name('login');
Route::get('oauth/login', 'Auth\Controllers\LoginController@login')->name('login');
Route::get('oauth/logout', 'Auth\Controllers\LoginController@logout')->name('logout');
