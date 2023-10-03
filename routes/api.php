<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserProfile;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\CheckoutController;


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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::controller(RegisterController::class)->group(function(){
    Route::post('register', 'register');
    Route::post('login', 'login');
});
        
Route::get('app-settings', [CompanyController::class,'settings']);
Route::middleware('auth:sanctum')->group( function () {
    Route::post('generate-password', [UserProfile::class,'generate_password']);
    
    Route::resource('products', ProductController::class);
    Route::get('products-detail/{slug}', [ProductController::class,'show']);
    Route::post('add-to-cart', [CheckoutController::class,'add_to_cart']);
    Route::get('cart-list', [CheckoutController::class,'cart_list']);
    Route::post('remove-cart-item', [CheckoutController::class,'remove_item']);
    Route::post('remove-all-cart-items', [CheckoutController::class,'delete_cart']);
    Route::post('checkout', [CheckoutController::class,'checkout']);
    Route::get('user-orders/{type?}', [CheckoutController::class,'user_orders']);
    Route::get('orders-detail/{order_id}', [CheckoutController::class,'user_orders_detail']);
    
    
    
});

