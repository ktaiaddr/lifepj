<?php

use App\Http\Controllers\UI\baseController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';

Route::get('/mylogin', [baseController::class,'create']);
Route::get('/refueling', [baseController::class,'create']);
Route::get('/refueling/regist', [baseController::class,'create']);
Route::get('/household_account', [baseController::class,'create']);
Route::get('/myforgetpassword', [baseController::class,'create']);
Route::get('/myresetpassword/{token}', [baseController::class,'create']);
