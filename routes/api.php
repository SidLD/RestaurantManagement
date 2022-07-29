<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MealController;
use App\Http\Controllers\TableController;

use App\Http\Controllers\PassportAuthController;
use App\Http\Controllers\PostController;

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

Route::post('register', [PassportAuthController::class, 'register']);
Route::post('login', [PassportAuthController::class, 'login']);
Route::middleware('auth:api')->group(function () {

    Route::get('user/{type}', [UserController::class, 'index']);
    Route::get('user/{type}/{id}', [UserController::class, 'show']);
    Route::post('user/{type}', [UserController::class, 'store']);
    Route::put('user/{type}/{id}', [UserController::class, 'update']);
    Route::delete('user/{type}/{id}', [UserController::class, 'destroy']);

    Route::resource('booking', BookingController::class);
    Route::resource('table', TableController::class);

    Route::resource('category', CategoryController::class);
    Route::post('/category/{id}/product/', [CategoryController::class, 'addProduct']);
    Route::post('/category/{id}/product/{product_id}', [CategoryController::class, 'removeProduct']);
    Route::get('/category/{id}/product/selectable', [ProductController::class, 'getMealSelectableProduct']);

    Route::resource('meal', MealController::class);
    Route::post('/meal/{id}/product/', [MealController::class, 'addProduct']);
    Route::post('/meal/{id}/product/{product_id}', [MealController::class, 'removeProduct']);
    Route::get('/meal/{id}/product/selectable', [ProductController::class, 'getCategorySelectableProduct']);

    Route::get('/product', [ProductController::class, 'index']);
    Route::get('/product/{id}', [ProductController::class, 'show']);
    Route::post('/product', [ProductController::class, 'store']);
    Route::post('/product/update/{id}', [ProductController::class, 'update']);
    Route::post('/product/display/{id}', [ProductController::class, 'productDisplay']);
    Route::delete('/product/{id}', [ProductController::class, 'destroy']);
});

// Route::middleware('auth:sanctum')->get('/users', function (Request $request) {
    //     return $request->user();
    // });
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
