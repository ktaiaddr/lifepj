<?php

namespace App\Http\Controllers;

use App\Application\Service\FuelEconomy\RegisterService;
use App\Http\Requests\RefuelingsRegistRequest;
use http\Env\Response;
use Illuminate\Http\Request;

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

    public function __invoke(RefuelingsRegistRequest $request){

        if(! $request->ajax())
            return response()->json( [],400);

        try{
            $user_id = session()->get('user_id');
            if(! $user_id)
                throw new \Exception('ユーザIDがありません');

            $refueling_id = $this->registerService->regist( $request->transferCommand(), $user_id );
            return response()->json(['id'=>$refueling_id],200);
        }
        catch(\Exception $e){
            return response()->json([],400);
        }

    }

}
