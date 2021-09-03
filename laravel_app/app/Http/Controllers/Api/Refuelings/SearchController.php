<?php

namespace App\Http\Controllers\Api\Refuelings;

use App\Application\query\Refuelings\FuelEconomyQueryService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Refuelings\SearchRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SearchController extends Controller
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

        list($list,$count) = $this->fuelEconomyQueryService->findByUseridAndCondition( $user_id, $request->searchCommand($request) );

        return response()->json( ['list'=> $list,'count'=>$count],200);
    }
}
