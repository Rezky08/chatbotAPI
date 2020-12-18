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
    Route::group(['prefix' => 'application'], function () {
        Route::group(['prefix' => 'key'], function () {
            Route::get('/', 'ApplicationKeyController@index');
            Route::delete('/{id}', 'ApplicationKeyController@destroy');
            Route::get('/add', 'ApplicationKeyController@create');
            Route::post('/add', 'ApplicationKeyController@store');
        });
        Route::group(['prefix' => 'chat'], function () {
            Route::get('/{id?}', 'ApplicationChatController@index');
        });
    });
    Route::group(['prefix' => 'telegram'], function () {
        Route::get('/', 'TelegramController@index');
        Route::post('/', 'TelegramController@store');
        Route::delete('/{app_id}/{id}', 'TelegramController@destroy');
        Route::group(['prefix' => 'account'], function () {
            Route::get('/', 'TelegramAccountController@index');
        });
        Route::group(['prefix' => 'chat'], function () {
            Route::get('/', 'TelegramChatController@index');
        });
    });
    Route::group(['prefix' => 'question'], function () {
        Route::get('/{id?}', 'QuestionController@show');
        Route::post('/{id}/bulk', 'QuestionController@bulkStore');
    });
});

Route::get('/logout', 'LoginController@destroy');
