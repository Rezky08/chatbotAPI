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
    });
    Route::group(['prefix' => 'answer'], function () {
        Route::get('/', 'API\AnswerController@index');
    });
});
