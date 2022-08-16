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

Route::group(['prefix' => '/lpu'], function () {
    Route::get('/', [\App\Http\Controllers\API\V1\LpuController::class, 'index']);
    Route::post('/upload', [\App\Http\Controllers\API\V1\LpuLoadController::class, 'upload']);
    Route::get('/download_form', [\App\Http\Controllers\API\V1\LpuLoadController::class, 'downloadForm']);
    Route::post('/store', [\App\Http\Controllers\API\V1\LpuController::class, 'store']);
    Route::get('/{id}/download', [\App\Http\Controllers\API\V1\LpuLoadController::class, 'download']);
    Route::put('/{id}/update', [\App\Http\Controllers\API\V1\LpuController::class, 'update']);
    Route::get('/{id}', [\App\Http\Controllers\API\V1\LpuController::class, 'show']);
    Route::delete('/{id}', [\App\Http\Controllers\API\V1\LpuController::class, 'destroy']);

});
