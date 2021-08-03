<?php

namespace App\Http\Controllers;

use App\Application\Service\FuelEconomy\RegisterService;
use App\Domain\Model\FuelEconomy\UpdateRefuelingCommand;
use App\Http\Requests\RefuelingsRegistRequest;
use http\Env\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RefuelingsRegistController extends Controller
{

    private RegisterService $registerService;

    /**
     * RefuelingsRegistController constructor.
     * @param RegisterService $registerService
     */
    public function __construct(RegisterService $registerService)
    {
        $this->registerService = $registerService;
    }

    /**
     * @param RefuelingsRegistRequest $request
     * @return JsonResponse
     */
    public function __invoke(RefuelingsRegistRequest $request):JsonResponse{

        if(! $request->ajax())
            return response()->json( [],400);

        try{
            // 現在認証されているユーザーのID取得
            $user_id = Auth::id();

            if(! $user_id)
                throw new \Exception('ユーザIDがありません');

            // リクエストをupdateコマンドに変換
            $updateCommand = $request->transferCommand();

            // updateコマンドで登録又は更新
            $refueling_id = $this->registerService->regist( $updateCommand, $user_id );

            // レスポンス
            return response()->json(['id'=>$refueling_id] );
        }
        catch(\Exception $e){
            return response()->json([],400);
        }

    }

}
