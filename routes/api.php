<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\OrderItemController;

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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/   


//products
Route::get('products', [ProductController::class, 'index']);
Route::post('customers',[CustomerController::class, 'store']);


Route::get('products/total', [ProductController::class, 'getTopSoldProducts']);

Route::get('products/types', [ProductController::class, 'types']);
Route::get('products/types/{type_name}', [ProductController::class, 'productByType']);
Route::delete('products/{product}',[ProductController::class,'destroy']);
Route::post('products',[ProductController::class,'store']);
Route::put('products/{product}',[ProductController::class,'update']);
Route::get('products/{product}',[ProductController::class,'show']);


//customers
Route::post('customers',[CustomerController::class, 'store']);
Route::get('customers/orders/{customer}/open', [CustomerController::class, 'openOrders']);
Route::get('customers/orders/{customer}/closed', [CustomerController::class, 'closedOrders']);
Route::get('customers/{customer}',[CustomerController::class, 'show']);
Route::put('customers/{customer}',[CustomerController::class, 'update']);



//login/logout
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']);

//cart
Route::post('cart/checkout', [CartController::class,'checkout']);


//current user
Route::middleware('auth:sanctum')->get('users/me', [UserController::class, 'me']);

//user
Route::get('users', [UserController::class, 'index']);
Route::get('users/types', [UserController::class, 'types']);
Route::get('users/types/{type_name}', [UserController::class, 'usersByType']);
Route::get('users/{user}', [UserController::class, 'show']);
Route::post('users',[UserController::class, 'store']);
Route::post('users/{user}/password',[UserController::class,'changePassword']);
Route::patch('users/{user}', [UserController::class, 'patchUser']);
Route::delete('users/{user}', [UserController::class, 'destroy']);
Route::put('users/{user}',[UserController::class,'update']);



//deliveryman
Route::get('deliverymen/orders', [UserController::class, 'deliverymanOrders']);



// Orders
Route::get('orders/preparation/queue', [OrderController::class, 'nextOrderToPrepare']);
Route::get('orders/active', [OrderController::class, 'activeOrders']);
Route::get('orders/{id}', [OrderController::class, 'show']);
Route::patch('orders/{id}', [OrderController::class, 'update']);



//employees
Route::get('employees', [UserController::class, 'employeesIndex']);
Route::get('employee/{id}/currentOrder', [UserController::class, 'getCurrentOrder']);

//items
Route::get('items/salesnumber/category', [OrderItemController::class, 'getNumberOfSalesPerCategory']);





Route::get('products/total/category', [ProductController::class, 'getTotalProductsByType']);
Route::get('products/sold/category', [ProductController::class, 'getQuantitySoldByCategory']);
Route::get('orders/average/time', [OrderController::class, 'averageTimePerTask']);
Route::get('orders/quantity/year', [OrderController::class, 'ordersPerYear']);
Route::get('orders/quantity/month', [OrderController::class, 'ordersPerMonth']);
Route::get('orders/total/status', [OrderController::class, 'ordersStatus']);
Route::get('sales/quantity/year', [OrderController::class, 'salesPerYear']);
Route::get('sales/quantity/month', [OrderController::class, 'salesPerMonth']);


