<?php

namespace App\Http\Controllers;

use App\Application\query\FuelEconomy\FuelEconomyQueryService;
use App\Application\Service\FuelEconomy\RegisterService;
use App\Domain\Model\FuelEconomy\IRefuelingRepository;
use App\Domain\Model\FuelEconomy\UpdateRefuelingCommand;
use App\Http\Requests\RefuelingsRegistRequest;
use App\infra\EloquentRepository\RefuelingEloquentRepository;
use http\Env\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RefuelingsRegistController extends Controller
{

    private RegisterService $registerService;
    private FuelEconomyQueryService $fuelEconomyQueryService;

    /**
     * RefuelingsRegistController constructor.
     * @param RegisterService $registerService
     * @param FuelEconomyQueryService $fuelEconomyQueryService
     */
    public function __construct(RegisterService $registerService, FuelEconomyQueryService $fuelEconomyQueryService)
    {
        $this->registerService = $registerService;
        $this->fuelEconomyQueryService = $fuelEconomyQueryService;
    }


    public function entry(Request $request, int $refueling_id ){

        if(! $request->ajax())
            return response()->json( [],400);

        try{
            // 現在認証されているユーザーのID取得
            $user_id = Auth::id();

            if(! $user_id)
                throw new \Exception('ユーザIDがありません');

            $refueling = $this->fuelEconomyQueryService->findByUseridAndRefuelingid( $user_id, $refueling_id );

            // レスポンス
            return response()->json($refueling);
        }
        catch(\Exception $exception){

        }
    }

    /**
     * @param RefuelingsRegistRequest $request
     * @return JsonResponse
     */
    public function submit(RefuelingsRegistRequest $request):JsonResponse{

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
