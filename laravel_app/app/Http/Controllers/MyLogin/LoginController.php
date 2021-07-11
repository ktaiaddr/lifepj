<?php


namespace App\Http\Controllers\MyLogin;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController
{

    public function __invoke(Request $request){

        if(! Auth::attempt($request->only('email', 'password'))){
            return response()->json(['result'=>'fail'],403);
        }
        if( empty(Auth::user()->email_verified_at)) {
            Auth::logout();
            return response()->json(['result'=>'fail not email verified'],403);
        }

        return response()->json(['result'=>'ok'],200);
    }
}
