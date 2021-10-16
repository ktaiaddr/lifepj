<?php

namespace App\Http\Controllers\Api\HouseholdAccount;

use App\Application\HouseholdAccount\service\TransactionRegisterService;
use App\Application\HouseholdAccount\service\TransactionRegisterViewService;
use App\Http\Controllers\Controller;
use App\Http\Requests\HouseholdAccount\RegisterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{

    private TransactionRegisterService $transactionRegisterService;
    private TransactionRegisterViewService $transactionRegisterViewService;

    /**
     * @param TransactionRegisterService $transactionRegisterService
     * @param TransactionRegisterViewService $transactionRegisterViewService
     */
    public function __construct(TransactionRegisterService $transactionRegisterService, TransactionRegisterViewService $transactionRegisterViewService)
    {
        $this->transactionRegisterService = $transactionRegisterService;
        $this->transactionRegisterViewService = $transactionRegisterViewService;
    }


    public function entry(Request $request){

        if (! $request->ajax()) return response()->json( [],400);

        try{
            // 現在認証されているユーザーのID取得
            $user_id = Auth::id();

            if(! $user_id)
                throw new \Exception('ユーザIDがありません');

            $registerPageComponents = $this->transactionRegisterViewService->getComponents();

            // レスポンス
            return response()->json(['registerPageComponents' => $registerPageComponents],200 );
        }
        catch(\Exception $exception){

        }
    }

    /**
     *
     */
    public function register(RegisterRequest $request){

        if (! $request->ajax()) return response()->json( [],400);

        try{
            // 現在認証されているユーザーのID取得
            $user_id = Auth::id();

            if(! $user_id)
                throw new \Exception('ユーザIDがありません');

            // リクエストをupdateコマンドに変換
            $registerCommand = $request->transferCommand();

            // 登録
            $transactionId = $this->transactionRegisterService->do( $registerCommand, $user_id );

            // レスポンス
            return response()->json( ['$transactionId'=> $transactionId] );
        }
        catch(\Exception $e){
            return response()->json(['message'=>$e->getMessage()],400);
        }

    }
}
