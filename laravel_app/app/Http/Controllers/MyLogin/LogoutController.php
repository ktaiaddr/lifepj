<?php


namespace App\Http\Controllers\MyLogin;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController
{

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->json(true,200);
    }
}
