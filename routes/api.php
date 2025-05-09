<?php

use App\Http\Controllers\Pharamcy\ChequeController;
use App\Http\Controllers\Pharamcy\DistributorController;

use App\Http\Controllers\Admin\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//User Route
Route::apiResource('users',UserController::class);
//Role Route

