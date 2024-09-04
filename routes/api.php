<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\CustomerController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('v1/customers')->group(function () {
    Route::get('/', [CustomerController::class, 'index']);
    Route::post('/', [CustomerController::class, 'store']);
    Route::get('/pesquisar', [CustomerController::class, 'getByCity']);
    Route::get('/{id}', [CustomerController::class, 'show']);
    Route::delete('/{id}', [CustomerController::class, 'destroy']);
    Route::put('/{id}', [CustomerController::class, 'update']);
});
