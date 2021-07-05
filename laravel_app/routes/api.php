<?php

use App\Http\Controllers\MyLogin\LoginController;
use App\Http\Controllers\RefuelingsRegistController;
use App\Http\Controllers\RefuelingsSearchController;
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

Route::get('/refuelings', RefuelingsSearchController::class );
Route::post('/refuelings/regist', RefuelingsRegistController::class );

Route::post('/mylogin', LoginController::class );

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


