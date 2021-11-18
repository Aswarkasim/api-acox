<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\FoodApiController;
use App\Http\Controllers\API\OrderApiController;
use App\Http\Controllers\API\ProgramController;
use App\Http\Controllers\API\UserApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use phpDocumentor\Reflection\Types\Resource_;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware => auth:sanctum'], function () {
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/warungLogin', [AuthController::class, 'warungLogin']);
Route::post('/register', [AuthController::class, 'register']);

Route::group(['middleware'  => ['auth:sanctum']], function () {
    Route::get('/profile', function (Request $request) {
        return auth()->user();
    });


    Route::get('user', [UserApiController::class, 'index']);

    Route::resource('foods', FoodApiController::class);
    Route::get('food/getByUser/{user_id}', [FoodApiController::class, 'getByUser']);
    Route::post('food/update/{id}', [FoodApiController::class, 'update']);
    Route::put('food/is_ready/{id}', [FoodApiController::class, 'is_ready']);


    Route::resource('order', OrderApiController::class);
    Route::put('order/get_order/{id}', [OrderApiController::class, 'get_order']);


    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::resource('programs', ProgramController::class);

Route::group(['middleware' => ['auth:sanctum']], function () {
});
