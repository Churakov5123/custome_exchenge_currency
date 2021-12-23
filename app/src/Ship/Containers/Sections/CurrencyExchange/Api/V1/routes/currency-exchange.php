<?php

use App\src\Ship\Containers\Sections\CurrencyExchange\Api\Controllers\CurrencyExchangeController;
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


Route::group(['namespace' => 'ApiCurrencyExchange'], function () {
    Route::post('currency/exchange', [CurrencyExchangeController::class, 'exchange']);
});

Route::fallback(function () {
    return response()->json(['message' => 'Not Found!'], 404);
});


