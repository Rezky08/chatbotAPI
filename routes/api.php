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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['middleware' => 'client'], function () {
    Route::get('/test', 'TesterController@index');
});

Route::group(['prefix' => 'token'], function () {
    Route::post('/', 'API\AuthController@store');
});

Route::group(['middleware' => ['client:application']], function () {
    Route::group(['prefix' => 'question'], function () {
        Route::get('/', 'API\QuestionController@index');
        Route::post('/', 'API\QuestionController@store');
    });
    Route::group(['prefix' => 'answer'], function () {
        Route::get('/', 'API\AnswerController@index');
        Route::post('/', 'API\AnswerController@store');
    });
    Route::group(['prefix' => 'label'], function () {
        Route::get('/', 'API\LabelController@index');
        Route::post('/', 'API\LabelController@store');
    });
    Route::group(['prefix' => 'chat'], function () {
        Route::get('/', 'API\ApplicationChatController@index');
        Route::post('/', 'API\ApplicationChatController@store');
    });
    Route::group(['prefix' => 'telegram'], function () {
        Route::group(['prefix' => 'chat'], function () {
            Route::get('/', 'API\TelegramChatController@index');
        });
        Route::group(['prefix' => 'account'], function () {
            Route::get('/', 'API\TelegramAccountController@index');
            Route::post('/', 'API\TelegramAccountController@store');
        });
    });
});
Route::group(['prefix' => 'telegram'], function () {
    Route::group(['prefix' => 'chat'], function () {
        Route::post('{c_id}/{bot_token}', 'API\TelegramChatController@store');
    });
});
