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

Route::get('/', 'DomainController@main')->name('main');

Route::post('/domains', 'DomainController@store')->name('store');

Route::get('/domains/{id}', 'DomainController@show')->name('show');

Route::get('/domains', 'DomainController@index')->name('index');

Route::post('/check', 'DomainController@check')->name('check');
