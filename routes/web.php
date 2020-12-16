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
// Route::get('/app/form', 'AppManagerController@create');
// Route::get('/dashboard', 'AppManagerController@create');
Route::group(['middleware' => ['auth.redirect:web']], function () {
    Route::group(['prefix' => 'login'], function () {
        Route::get('/', 'LoginController@create')->name('login');
        Route::post('/', 'LoginController@store');
    });
    Route::group(['prefix' => 'register'], function () {
        Route::get('/', 'RegisterController@create');
        Route::post('/', 'RegisterController@store');
    });
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', 'AppManagerController@index');
    Route::group(['prefix' => 'app-key'], function () {
        Route::get('/', 'ApplicationKeyController@index');
        Route::delete('/{id}', 'ApplicationKeyController@destroy');
        Route::get('/add', 'ApplicationKeyController@create');
        Route::post('/add', 'ApplicationKeyController@store');
        Route::get('/a/b/c/d', 'ApplicationKeyController@show');
    });
    Route::group(['prefix' => 'telegram'], function () {
        Route::get('/', 'TelegramController@index');
        Route::post('/', 'TelegramController@store');
    });
});

Route::get('/logout', 'LoginController@destroy');
