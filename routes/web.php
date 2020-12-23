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

Route::redirect('/', '/dashboard');
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
    Route::get('/dashboard', 'DashboardController@index');
    Route::group(['prefix' => 'application'], function () {
        Route::get('/', 'ApplicationController@index');
        Route::delete('/{id}', 'ApplicationController@destroy');
        Route::get('/add', 'ApplicationController@create');
        Route::post('/add', 'ApplicationController@store');
        Route::post('/{id}/train', 'ApplicationTrainController@train');
        Route::group(['prefix' => 'chat'], function () {
            Route::get('/{id?}', 'ApplicationChatController@index');
        });
    });
    Route::group(['prefix' => 'telegram'], function () {
        Route::get('/', 'TelegramController@index');
        Route::post('/', 'TelegramController@store');
        Route::delete('/{app_id}/{id}', 'TelegramController@destroy');
        Route::group(['prefix' => 'account'], function () {
            Route::get('/{t_id?}/{id?}', 'TelegramAccountController@index');
        });
        Route::group(['prefix' => 'chat'], function () {
            Route::get('/{t_id?}/{id?}', 'TelegramChatController@index');
        });
    });
    Route::group(['prefix' => 'question'], function () {
        Route::get('/{id?}', 'QuestionController@show');
        Route::post('/{id}/bulk', 'QuestionController@bulkStore');
        Route::get('/{id}/add', 'QuestionController@create');
        Route::post('/{id}/add', 'QuestionController@store');
        Route::get('/{id}/{q_id}/edit', 'QuestionController@edit');
        Route::put('/{id}/{q_id}/edit', 'QuestionController@update');
        Route::delete('/{id}/{q_id}', 'QuestionController@destroy');
    });
    Route::group(['prefix' => 'answer'], function () {
        Route::get('/{id?}', 'AnswerController@show');
        Route::post('/{id}/bulk', 'AnswerController@bulkStore');
        Route::get('/{id}/add', 'AnswerController@create');
        Route::post('/{id}/add', 'AnswerController@store');
        Route::get('/{id}/{q_id}/edit', 'AnswerController@edit');
        Route::put('/{id}/{q_id}/edit', 'AnswerController@update');
        Route::delete('/{id}/{q_id}', 'AnswerController@destroy');
    });
    Route::group(['prefix' => 'label'], function () {
        Route::get('/{id?}', 'LabelController@show');
        Route::get('/{id}/add', 'LabelController@create');
        Route::post('/{id}/add', 'LabelController@store');
        Route::delete('/{id}/{l_id}', 'LabelController@destroy');
    });
});

Route::get('/logout', 'LoginController@destroy');
