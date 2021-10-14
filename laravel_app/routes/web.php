<?php

use App\Http\Controllers\MyLogin\VerifyEmailController;
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

//給油
/*一覧*/ Route::get('/refueling', [baseController::class,'create']);
/*登録*/ Route::get('/refueling/regist', [baseController::class,'create']);
/*編集*/ Route::get('/refueling/regist/{refueling_id}', [baseController::class,'create']);

Route::get('/household_account', [baseController::class,'create']);
Route::get('/household_account/regist', [baseController::class,'create']);

Route::get('/myforgetpassword', [baseController::class,'create']);
Route::get('/myresetpassword/{token}', [baseController::class,'create']);
Route::get('/myregisteruser', [baseController::class,'create']);
//Route::get('/myverifyemail/{id}/{hash}', [baseController::class,'create'])->name('myverifyemail');
Route::get('/myverifyemail/{id}/{hash}', [VerifyEmailController::class,'verify'])->name('myverifyemail');
