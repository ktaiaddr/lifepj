<?php

namespace App\Http\Controllers\Api\Refuelings;

use App\Application\query\Refuelings\FuelEconomyQueryService;
use App\Application\Service\Refuelings\RegisterService;
use App\Domain\Model\Refuelings\IRefuelingRepository;
use App\Domain\Model\Refuelings\UpdateRefuelingCommand;
use App\Http\Controllers\Controller;
use App\Http\Requests\Refuelings\DeleteRequest;
use App\Http\Requests\Refuelings\RegistRequest;
use App\infra\EloquentRepository\Refuelings\RefuelingEloquentRepository;
use http\Env\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegistController extends Controller
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

        if (! $request->ajax()) return response()->json( [],400);

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
     * @param RegistRequest $request
     * @return JsonResponse
     */
    public function regist(RegistRequest $request):JsonResponse{

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
//            if($updateCommand->isNew())
                $refueling_id = $this->registerService->regist( $updateCommand, $user_id );
//            else
//                $refueling_id = $this->registerService->update( $updateCommand, $user_id );

            // レスポンス
            return response()->json( ['id'=> $refueling_id] );
        }
        catch(\Exception $e){
            return response()->json([],400);
        }

    }

    /**
     * @param RegistRequest $request
     * @return JsonResponse
     */
    public function update(RegistRequest $request):JsonResponse{

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
//            if($updateCommand->isNew())
//                $refueling_id = $this->registerService->regist( $updateCommand, $user_id );
//            else
                $refueling_id = $this->registerService->update( $updateCommand, $user_id );

            // レスポンス
            return response()->json( ['id'=> $refueling_id] );
        }
        catch(\Exception $e){
            return response()->json([],400);
        }

    }

    /**
     * @param DeleteRequest $request
     * @return JsonResponse
     */
    public function delete(DeleteRequest $request):JsonResponse{

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
            $refueling_id = $this->registerService->update( $updateCommand, $user_id );

            // レスポンス
            return response()->json(['id'=>$refueling_id] );

        }
        catch(\Exception $e){
            return response()->json([],400);
        }

    }
}
