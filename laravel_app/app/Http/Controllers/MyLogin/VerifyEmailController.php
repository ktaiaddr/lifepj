<?php

namespace App\Http\Controllers\MyLogin;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class VerifyEmailController extends Controller
{
    /**
     * @param EmailVerificationRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(EmailVerificationRequest $request)
    {
//        if ($request->user()->hasVerifiedEmail()) {
//            return response()->json(['result'=>true,'message'=>'already'],200);
//        }

        if ($request->user()->markEmailAsVerified()){
            event(new Verified($request->user()));
            return response()->json(['result'=>true,'message'=>'ok'],200);
        }

        return response()->json(['result'=>false,'message'=>'verify fail'],400);

    }
}
