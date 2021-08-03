<?php

namespace App\Http\Controllers;

use App\Application\query\FuelEconomy\FuelEconomyQueryService;
use App\Http\Requests\RefuelingsSearchRequest;
use App\infra\mysqlquery\FuelEconomyMysqlQueryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RefuelingsSearchController extends Controller
{
    private FuelEconomyQueryService $fuelEconomyQueryService;

    /**
     * RefuelingsSearchController constructor.
     * @param FuelEconomyQueryService $fuelEconomyQueryService
     */
    public function __construct(FuelEconomyQueryService $fuelEconomyQueryService)
    {
        $this->fuelEconomyQueryService = $fuelEconomyQueryService;
    }

    /**
     * Handle the incoming request.
     * @param RefuelingsSearchRequest $request
     * @return JsonResponse
     */
    public function __invoke(RefuelingsSearchRequest $request):JsonResponse
    {
        if(! $request->ajax())
            return response()->json( [],400);

        // 現在認証されているユーザーのID取得
        $user_id = Auth::id();

        $result = $this->fuelEconomyQueryService->findByUseridAndCondition( $user_id, $request->searchCommand($request) );

        return response()->json( ['list'=> $result[0],'count'=>$result[1]],200);
    }
}
