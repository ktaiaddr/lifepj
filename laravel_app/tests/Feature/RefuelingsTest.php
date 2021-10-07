<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class RefuelingsTest extends TestCase
{
    public function test_給油量が0なので_レスポンスステータス「422」でエラー(){

        $user = new User();
        $user->id = 9;
        $response = $this->actingAs($user)->json('POST','/api/refuelings',[
            'refueling_id'       =>  null         ,//nullは新規登録
            'date'               =>  '2021-07-01' ,
            'refueling_amount'   =>  0            ,//エラー箇所
            'refueling_distance' =>  500          ,
            'gas_station'        =>  ""           ,
            'memo'               =>  "m"          ,
        ],['X-Requested-With' => 'XMLHttpRequest']);

//        $response->dump();

        $response->assertStatus(422);
    }

    public function test_給油量がないのでレスポンスステータス「422」でエラー(){

        $user = new User();
        $user->id = 9;

        //refueling_amountがないのでエラーになる
        $response = $this->actingAs($user)->json('POST','/api/refuelings',[
            'refueling_distance'=>500,
            'gas_station'=> "g",
            'memo'=>"m",
        ],['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertStatus(422);
    }

    public function test_距離がないのでレスポンスステータス「422」でエラー(){

        $user = new User();
        $user->id = 9;

        //refueling_distanceがないのでエラーになる
        $response = $this->actingAs($user)->json('POST','/api/refuelings',[
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

        $response = $this->json('POST','/api/refuelings',[
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

        $user = new User();
        $user->id = 9;

        $response = $this->actingAs($user)->json('POST','/api/refuelings',[
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

        $user = new User();
        $user->id = 9;
        $response = $this->actingAs($user)->json('POST','/api/refuelings',[
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
        $user = new User();
        $user->id = 9;
        $response = $this->actingAs($user)->json('GET','/api/refuelings',[
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

//        $response->dump();
        $response->assertStatus(200);
    }

    public function test_RefuelingsSearch422(){

        $user = new User();
        $user->id = 9;
        $response = $this->actingAs($user)->json('GET','/api/refuelings',[
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
