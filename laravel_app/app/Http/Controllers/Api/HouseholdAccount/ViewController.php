<?php

namespace App\Http\Controllers\Api\HouseholdAccount;

use App\Application\HouseholdAccount\service\TransactionViewService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ViewController
{

    private TransactionViewService $transactionViewService;

    /**
     * @param TransactionViewService $transactionViewService
     */
    public function __construct(TransactionViewService $transactionViewService)
    {
        $this->transactionViewService = $transactionViewService;

    }

    /**
     * Handle the incoming request.
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request):JsonResponse
    {
        Log::debug("hoge");

        if(! $request->ajax())
            return response()->json( [],400);

        // 現在認証されているユーザーのID取得
        $user_id = Auth::id();

        $data = $this->transactionViewService->do( $user_id );

//        var_dump($data);

        return response()->json( ['data'=>$data], 200 );

    }


}
