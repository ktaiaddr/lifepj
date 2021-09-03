<?php
namespace App\infra\EloquentRepository\Refuelings;

use App\Domain\Model\Refuelings\FuelEconomy;
use App\Domain\Model\Refuelings\Refueling;

/**
 * 給油クラス リポジトリ 実装クラス
 * Class RefuelingEloquentRepository
 * @package App\infra\EloquentRepository
 */
class RefuelingEloquentRepository implements \App\Domain\Model\Refuelings\IRefuelingRepository
{

    /**
     * DBに給油データを保存
     * @param Refueling $refueling
     * @return int
     */
    function save( Refueling $refueling ): int
    {
        $refuelingModelBuilder = new RefuelingModelBuilder(); //通知オブジェクト生成
        $refueling->notify( $refuelingModelBuilder );         //給油クラスから通知オブジェクトにデータを詰込み
        $elqRefueling = $refuelingModelBuilder->build();      //EloquentModelを生成
//        $elqRefueling->save();                                //保存

        return $elqRefueling->refueling_id;                   //給油データのIDを返却
    }

    /**
     * DBから給油データを取得
     * @param int $refuelingId
     * @return ?Refueling
     * @throws \Exception
     */
    function find(int $refuelingId,int $userId): ?Refueling
    {
        $elqReueling = \App\Models\Refueling::where('refueling_id', $refuelingId)
            ->where('user_id',$userId)
            ->where('del_flg',0)
            ->get()
            ->first();


        if(! $elqReueling) return null;


        return new Refueling(
            $elqReueling->refueling_id,
            $elqReueling->user_id,
            new \DateTime($elqReueling->date),
            new FuelEconomy(
                $elqReueling->refueling_amount,
                $elqReueling->refueling_distance
            ),
            $elqReueling->gas_station,
            $elqReueling->memo,
            $elqReueling->del_flg,
        );
    }
}
