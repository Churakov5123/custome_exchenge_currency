<?php

use App\Src\Ship\Containers\Sections\Currency\Api\V1\Controllers\CurrencyExchangeController;
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


Route::group(['namespace' => 'ApiV1Currency'], function () {
    Route::post('exchange', [CurrencyExchangeController::class, 'exchange']);
});

Route::fallback(function () {
    return response()->json(['message' => 'Not Found!'], 404);
});


