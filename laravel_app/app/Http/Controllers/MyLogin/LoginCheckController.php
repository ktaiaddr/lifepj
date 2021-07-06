<?php


namespace App\Http\Controllers\MyLogin;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginCheckController
{
    public function __invoke(Request $request){

        if(! Auth::check()){
            return response()->json(['result'=>'fail'],403);
        }
        return response()->json(['result'=>'ok'],200);
    }
}
