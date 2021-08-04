<?php


namespace App\Http\Controllers\MyLogin;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController
{

    public function __invoke(Request $request){

        //email,passwordでログインチェック
        if(! Auth::attempt($request->only('email', 'password'))){

            //未登録
            Log::error("login error email:".$request->email );

            //email,passwordでログインチェック
            return response()->json(['result'=>'fail'],403);
        }

        //登録ユーザがメール認証済みであることをチェック
        if( empty(Auth::user()->email_verified_at)) {

            //メール認証されていない
            Log::error("login error by email_verified_at is empty email:".$request->email );

            //ログアウトさせる
            Auth::logout();

            //エラーレスポンスを返す
            return response()->json(['result'=>'fail not email verified'],403);
        }

        Log::debug("successful login email:".$request->email );

        //ログイン成功のレスポンスを返す
        return response()->json(['result'=>'ok'],200);
    }
}
