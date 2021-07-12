<?php

namespace App\Http\Controllers\MyLogin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

class VerifyEmailController extends Controller
{
    /**
     * VerifyEmailController constructor.
     */
    public function __construct()
    {
//        $this->middleware( 'auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');;
    }

    /**
     * @param EmailVerificationRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(EmailVerificationRequest $request)
    {


        if ($request->user()->hasVerifiedEmail()) {
            return response()->json(['result' => true, 'message' => 'already'], 200);
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
            return response()->json(['result' => true, 'message' => 'ok'], 200);
        }

        return response()->json(['result' => false, 'message' => 'verify fail'], 400);

    }

    /**
     * メールアドレス確認（メソッドのオーバーライド）
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse
     */
    public function verify(Request $request)
    {
        $user = User::find($request->route('id'));

        if (!hash_equals((string)$request->route('hash'), sha1($user->getEmailForVerification()))) {
            return view('UI.verify', ['result' => 'fail']);
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($request->user()));
            return view('UI.verify', ['result' => 'success']);
        }
        return view('UI.verify', ['result' => 'fale']);
    }
}
