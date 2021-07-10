<?php

namespace App\Http\Controllers;

use App\Application\query\FuelEconomy\FuelEconomyQueryService;
use App\Http\Requests\RefuelingsSearchRequest;
use App\infra\mysqlquery\FuelEconomyMysqlQueryService;
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(RefuelingsSearchRequest $request)
    {
        if(! $request->ajax())
            return response()->json( [],400);

        $user_id = session()->get('user_id');
        $user_id = Auth::id();

        $fuelEconomyQueryModel_list = $this->fuelEconomyQueryService->findByUseridAndCondition( $user_id, $request->searchCommand($request) );

        return response()->json( $fuelEconomyQueryModel_list,200);
    }
}
