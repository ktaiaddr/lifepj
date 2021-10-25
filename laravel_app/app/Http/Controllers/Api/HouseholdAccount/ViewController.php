<?php

namespace App\Http\Controllers\Api\HouseholdAccount;

use App\Application\HouseholdAccount\service\TransactionRegisterViewService;
use App\Application\HouseholdAccount\service\TransactionViewService;
use App\Http\Requests\HouseholdAccount\SearchRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ViewController
{

    private TransactionViewService $transactionViewService;
    private TransactionRegisterViewService $transactionRegisterViewService;

    /**
     * @param TransactionViewService $transactionViewService
     */
    public function __construct(TransactionViewService $transactionViewService,TransactionRegisterViewService $transactionRegisterViewService)
    {
        $this->transactionViewService = $transactionViewService;
        $this->transactionRegisterViewService = $transactionRegisterViewService;

    }

    /**
     * Handle the incoming request.
     * @param SearchRequest $request
     * @return JsonResponse
     */
    public function __invoke(SearchRequest $request):JsonResponse
    {
        Log::debug("hoge");

        if(! $request->ajax())
            return response()->json( [],400);

        // 現在認証されているユーザーのID取得
        $user_id = Auth::id();

        $commnd = $request->transferCommand();

        //最終締月の残高データを生成
        $this->transactionViewService->generateTemporaryLatestClosingData( $commnd, $user_id );

        $data = $this->transactionViewService->do( $commnd, $user_id );
//        $balanceAggregateViewModel = $this->transactionViewService->getAggregateData( $commnd, $user_id );



//        $registerPageComponents = $this->transactionRegisterViewService->getComponents($user_id);

//        var_dump($data);

//        return response()->json( ['data'=>$data,'balanceAggregateViewModel'=>$balanceAggregateViewModel,'registerPageComponents'=>$registerPageComponents], 200 );
        return response()->json( $data, 200 );

    }


}
