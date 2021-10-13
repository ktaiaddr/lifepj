<?php


use App\Http\Controllers\Api\HouseholdAccount\RegisterController;
use App\Http\Controllers\MyLogin\LoginCheckController;
use App\Http\Controllers\MyLogin\LoginController;
use App\Http\Controllers\MyLogin\LogoutController;
use App\Http\Controllers\MyLogin\PasswordResetController;
use App\Http\Controllers\MyLogin\PasswordResetExecController;
use App\Http\Controllers\MyLogin\RegisterUserController;
use App\Http\Controllers\MyLogin\VerifyEmailController;
use App\Http\Controllers\Api\Refuelings\RegistController;
use App\Http\Controllers\Api\Refuelings\SearchController;
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

//給油
/*一覧のリストを取得  */ Route::get('/refuelings', SearchController::class );
/*新規データを登録   */  Route::post('/refuelings', [RegistController::class,'regist']);
/*指定IDのデータを取得*/ Route::get('/refuelings/{refueling_id}', [RegistController::class,'entry'] );
/*指定IDのデータを更新*/ Route::put('/refuelings/{refueling_id}', [RegistController::class,'update']);
/*指定IDのデータを削除*/ Route::delete('/refuelings/{refueling_id}', [RegistController::class,'delete']);

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


//口座管理
/*一覧のリストを取得  */ Route::get('/household_account', SearchController::class );
/*新規データを登録   */  Route::post('/household_account', [RegisterController::class,'register']);
///*指定IDのデータを取得*/ Route::get('/household_account/{refueling_id}', [RegisterController::class,'entry'] );
///*指定IDのデータを更新*/ Route::put('/household_account/{refueling_id}', [RegisterController::class,'update']);
///*指定IDのデータを削除*/ Route::delete('/household_account/{refueling_id}', [RegisterController::class,'delete']);


