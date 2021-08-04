<?php


namespace App\Http\Controllers\MyLogin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginCheckController extends Controller
{
    public function __invoke(Request $request){

        if(! Auth::check()){
            Log::debug('loginCheck NG');
            return response()->json(['result'=>'fail'],403);
        }

        Log::debug('loginCheck OK');
        return response()->json(['result'=>'ok'],200);
    }
}
