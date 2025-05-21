<?php

use App\Http\Controllers\Pharamcy\ChequeController;
use App\Http\Controllers\PharamcyController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Models\Pharamcy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckRoleAndPermissionMiddleware;



//login route
Route::post('login', [AuthController::class, 'login']);
//logout route
Route::post('logout', [AuthController::class, 'logout']);
Route::post('register', [AuthController::class, 'register']);

//================================================================


//Roles
Route::apiResource('role', RoleController::class);


//=====================Permission and Role routes====================

//getUserRoles
Route::get('user/roles/{id}', [UserController::class, 'getUserRoles']);
Route::post('role/{role}/permissions', [RoleController::class, 'syncPermissions']);

Route::delete('role/{role}/permissions', [RoleController::class, 'removePermissionsFromRole']);
   Route::apiResource('pharamcy', PharamcyController::class);

Route::apiResource('permission', PermissionController::class);

Route::middleware('auth:sanctum')->group(function () {

    Route::apiResource('user', UserController::class);

    Route::post('user/{user}/roles', [UserController::class, 'assignRoleToUser']);

    Route::delete('user/{user}/roles', [UserController::class, 'removeRolesFromUser']);

 
});
