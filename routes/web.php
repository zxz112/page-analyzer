<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', 'DomainController@main')->name('domain.main');

Route::post('/domains', 'DomainController@store')->name('domain.store');

Route::get('/domains/{id}', 'DomainController@show')->name('domain.show');

Route::get('/domains', 'DomainController@index')->name('domain.index');

Route::post('/check', 'CheckController@store')->name('check.store');
