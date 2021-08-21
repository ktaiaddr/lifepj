<?php


use App\Http\Controllers\MyLogin\LoginCheckController;
use App\Http\Controllers\MyLogin\LoginController;
use App\Http\Controllers\MyLogin\LogoutController;
use App\Http\Controllers\MyLogin\PasswordResetController;
use App\Http\Controllers\MyLogin\PasswordResetExecController;
use App\Http\Controllers\MyLogin\RegisterUserController;
use App\Http\Controllers\MyLogin\VerifyEmailController;
use App\Http\Controllers\RefuelingsRegistController;
use App\Http\Controllers\RefuelingsSearchController;
use App\Http\Controllers\UI\baseController;
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
Route::get('/refuelings/regist/{refueling_id?}', [RefuelingsRegistController::class,'entry'] );
Route::post('/refuelings/regist/{refueling_id?}', [RefuelingsRegistController::class,'submit']);

Route::post('/mylogin', LoginController::class );
Route::post('/mylogout', LogoutController::class );
Route::get('/mylogincheck', LoginCheckController::class);
Route::post('/mypasswordreset', PasswordResetController::class);
Route::post('/mypasswordresetexec', PasswordResetExecController::class);
Route::post('/myregistuser', RegisterUserController::class);
//Route::get('/myverifyemail/{id}/{hash}', [VerifyEmailController::class,'verify']);

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


