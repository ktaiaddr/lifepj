<?php

namespace Tests\Feature;

use Tests\TestCase;

class RefuelingsTest extends TestCase
{
    /**
     * 給油量が0なので422
     */
    public function test_RefuelingsRegistIdIsNimus_Status422(){

        $response = $this->json('POST','/api/refuelings/regist',[
            'refueling_id'       =>  null         ,
            'date'               =>  '2021-07-01' ,
            'refueling_amount'   =>  0            ,
            'refueling_distance' =>  500          ,
            'gas_station'        =>  ""           ,
            'memo'               =>  "m"          ,
        ],['X-Requested-With' => 'XMLHttpRequest']);

        $response->dump();

        $response->assertStatus(422);
    }

    /**
     * 給油量がないので422
     */
    public function test_RefuelingsRegistAmountNullStatus422(){

        $response = $this->json('POST','/api/refuelings/regist',[
            'refueling_distance'=>500,
            'gas_station'=> "g",
            'memo'=>"m",
        ],['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertStatus(422);
    }

    /**
     * 距離が無いので422
     */
    public function test_RefuelingsRegistDistanceNullStatus422(){

        $response = $this->json('POST','/api/refuelings/regist',[
            'refueling_amount'=>500,
            'gas_station'=> "g",
            'memo'=>"m",
        ],['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertStatus(422);
    }

    /**
     * セッションIDが取得できないので400
     */
    public function test_RefuelingsRegistNewStatusIs400(){

        $response = $this->json('POST','/api/refuelings/regist',[
            'date'                =>  '2021-07-05',
            'refueling_amount'    =>  1,
            'refueling_distance'  =>  1,
            'gas_station'         =>  "g",
            'memo'                =>  "m",
        ],['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertStatus(400);
    }

    /**
     * 新規に正常登録できるリクエスト
     */
    public function test_RefuelingsRegistNewStatusIs200(){

        $response = $this->withSession(['user_id' => 9])->json('POST','/api/refuelings/regist',[
            'date'                => '2021-07-05',
            'refueling_amount'    => 1,
            'refueling_distance'  => 500,
            'gas_station'         => "g",
            'memo'                => "m",
        ],['X-Requested-With' => 'XMLHttpRequest']);

//        $response->dumpHeaders();
//        $response->dumpSession();
//        $response->dump();
        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     * @depends test_RefuelingsRegistNewStatusIs200
     * @return void
     */
    public function test_RefuelingsRegistUpdate(){

        $response = $this->withSession(['user_id' => 9])->json('POST','/api/refuelings/regist',[
            'refueling_id'       =>  1,
            'date'               =>  '2021-08-01',
            'refueling_amount'   =>  2,
            'refueling_distance' =>  500,
            'gas_station'        =>  "g",
            'memo'               =>  "m",
        ],['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     * @depends test_RefuelingsRegistUpdate
     * @return void
     */
    public function test_RefuelingsSearch200()
    {
        $response = $this->withSession(['user_id' => 9])->json('GET','/api/refuelings',[
            'date_start'      =>  '2021-01-01',
            'date_end'        =>  '2021-08-01',
            'amount_low'      =>  1.1,
            'amount_high'     =>  200.1,
            'distance_low'    =>  1.1,
            'distance_high'   =>  1000,
            'gas_station'     =>  'g',
            'memo'            =>  "m",
            'page'            =>  1,
        ],['X-Requested-With' => 'XMLHttpRequest']);

        $response->dump();
        $response->assertStatus(200);
    }

    public function test_RefuelingsSearch422(){

        $response = $this->json('GET','/api/refuelings',[
            'date_start'     =>  null,
            'date_end'       =>  '2021-06-30',
            'amount_low'     =>  1,
            'amount_high'    =>  1,
            'distance_low'   =>  1,
            'distance_high'  =>  1,
            'gas_station'    =>  1,
            'memo'           =>  1,
            'page'           =>  1,
        ],['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertStatus(422);
    }

}
