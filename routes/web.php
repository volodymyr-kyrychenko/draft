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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/prizing', 'PrizeController@index')->name('prizing');
Route::get('/receive', 'PrizeController@receive')->name('receive');
Route::get('/convert', 'PrizeController@convert')->name('convert');
Route::get('/refuse', 'PrizeController@refuse')->name('refuse');
