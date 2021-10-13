<?php

namespace App\Http\Controllers\Api\HouseholdAccount;

use App\Application\HouseholdAccount\service\TransactionRegisterService;
use App\Http\Controllers\Controller;
use App\Http\Requests\HouseholdAccount\RegisterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{

    private TransactionRegisterService $transactionRegisterService;

    /**
     * @param TransactionRegisterService $transactionRegisterService
     */
    public function __construct(TransactionRegisterService $transactionRegisterService)
    {
        $this->transactionRegisterService = $transactionRegisterService;
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

            // updateコマンドで登録又は更新
//            if($updateCommand->isNew())
            $refueling_id = $this->transactionRegisterService->do( $registerCommand, $user_id );
//            else
//                $refueling_id = $this->registerService->update( $updateCommand, $user_id );

            // レスポンス
            return response()->json( ['id'=> $refueling_id] );
        }
        catch(\Exception $e){
            return response()->json(['message'=>$e->getMessage()],400);
        }

    }
}
