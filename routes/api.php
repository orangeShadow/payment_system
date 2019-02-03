<?php

use Illuminate\Http\Request;

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

Route::prefix('v1')->group(function () {

    Route::get('currencies', 'Api\BankController@getCurrencies');
    Route::post('add-rate', 'Api\BankController@addRate');

    Route::post('add-balance', 'Api\BankController@addBalance');
    Route::post('remittance', 'Api\BankController@remittance');

    Route::get('payments-report', 'Api\BankController@paymentsReport');

    Route::get('user', 'Api\UserController@index');
    Route::post('user', 'Api\UserController@store');
});


