<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\Frontend\CartController;
use App\Http\Controllers\API\Frontend\CheckoutController;
use App\Http\Controllers\API\Frontend\FrontendController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


//========================Register Login ====================
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
//======================= Logout====================
Route::middleware(['auth:sanctum'])->group(function() {
    Route::post('logout', [AuthController::class, 'logout']);
});


//===========================Admin==============================
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
    Route::delete('delete-category/{id}', [CategoryController::class, 'destroy']);//delete by id
    //product
    Route::get('all-category', [ProductController::class, 'all_category']);
    Route::post('store-product', [ProductController::class, 'store']);
    Route::get('view-product', [ProductController::class, 'view']);
    Route::get('edit-product/{id}', [ProductController::class, 'edit']);
    Route::post('update-product/{id}', [ProductController::class, 'update']);
    Route::delete('delete-product/{id}', [ProductController::class, 'destroy']);
    //Order
    Route::get('admin/orders', [OrderController::class, 'index']);
});

//=============================Frontend==============================
Route::get('get-category', [FrontendController::class, 'category']);//show
Route::get('fetchproduct/{product_slug}', [FrontendController::class, 'product']);//show
Route::get('view-productdeatils/{category_slug}/{product_slug}', [FrontendController::class, 'productDetails']);
//Cart
Route::post('add-to-cart', [CartController::class, 'addToCart']);//product Details
Route::get('cart', [CartController::class, 'cart_view_item']);
Route::put('cart-update-quantity', [CartController::class, 'update_quantity']);
Route::delete('delete-cartitem/{cart_id}', [CartController::class, 'destroy_cart']);
//checkout
Route::post('place-order', [CheckoutController::class, 'place_order']);
Route::post('validate-order', [CheckoutController::class, 'validate_order']);


/*
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
*/



  