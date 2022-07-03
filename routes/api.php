<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


//===============Register Login ====================
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);




//======================Admin==============================
Route::middleware(['auth:sanctum', 'isApiAdmin'])->group(function() {
    //protected
    Route::get('/checkingAuthenticated', function() {
        return response()->json(['message' => 'You are in', 'status'=>200], 200);
    });
    //category
    Route::post('store-category', [CategoryController::class, 'store']);//store 
    Route::get('view-category', [CategoryController::class, 'view']);//show
    Route::get('edit-category/{id}', [CategoryController::class, 'edit']);//edit view by id
    Route::put('update-category/{id}', [CategoryController::class, 'update']);//update edit page by id
});


//=============== Logout====================
Route::middleware(['auth:sanctum'])->group(function() {
    Route::post('logout', [AuthController::class, 'logout']);
});





/*
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
*/