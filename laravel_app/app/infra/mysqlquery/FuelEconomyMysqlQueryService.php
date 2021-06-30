<?php


namespace App\infra\mysqlquery;


use App\Application\query\FuelEconomy\FuelEconomyQueryConditions;
use App\Application\query\FuelEconomy\FuelEconomyQueryModel;
use Illuminate\Support\Facades\DB;

class FuelEconomyMysqlQueryService implements \App\Application\query\FuelEconomy\FuelEconomyQueryService
{

    const LIMIT = 5;

    /**
     * @inheritDoc
     */
    function findByUserid(int $userId): array
    {
        $pdo = DB::getPdo();

        $query = ' select * from refuelings where user_id = :user_id';

        $stmt = $pdo->prepare( $query );

        $stmt->bindValue( 'user_id', $userId );

        $stmt->execute();

        return $stmt->fetchAll( \PDO::FETCH_CLASS, FuelEconomyQueryModel::class);

    }

    /**
     * @inheritDoc
     */
    function findByUseridAndCondition(int $userId, FuelEconomyQueryConditions $fuelEconomyQueryConditions): array
    {

        $pdo = DB::getPdo();

        $wheres = [];
        $values = [];

        $limit_bind = [':limit',self::LIMIT ,\PDO::PARAM_INT];

        $wheres[] = ' user_id = :user_id ';
        $values[] = [':user_id', $userId, \PDO::PARAM_INT];

        //日付開始日
        if( $fuelEconomyQueryConditions->getDateStart() !==null ){
            $wheres[] = 'date >= :date_start';
            $values[] = ['date_start', $fuelEconomyQueryConditions->getDateStart()->format('Y-m-d'), \PDO::PARAM_STR];
        }
        //日付終了日
        if( $fuelEconomyQueryConditions->getDateEnd() !==null ){
            $wheres[] = 'date <= :date_end';
            $values[] = ['date_end',  $fuelEconomyQueryConditions->getDateEnd()->format('Y-m-d'), \PDO::PARAM_STR];
        }
        //給油量最低
        if( $fuelEconomyQueryConditions->getAmountLow() !==null ){
            $wheres[] = 'refueling_amount >= :amount_low';
            $values[] = [':amount_low',  $fuelEconomyQueryConditions->getAmountLow(), \PDO::PARAM_STR];
        }
        //給油量最高
        if( $fuelEconomyQueryConditions->getAmountHigh() !==null ){
            $wheres[] = 'refueling_amount <= :amount_high';
            $values[] = [':amount_high',  $fuelEconomyQueryConditions->getAmountHigh(), \PDO::PARAM_STR];
        }
        //走行最低
        if( $fuelEconomyQueryConditions->getDistanceLow() !==null ){
            $wheres[] = 'refueling_distance >= :distance_low';
            $values[] = [':distance_low',  $fuelEconomyQueryConditions->getDistanceLow(), \PDO::PARAM_STR];
        }
        //走行最高
        if( $fuelEconomyQueryConditions->getDistanceHigh() !==null ){
            $wheres[] = 'refueling_distance <= :distance_high';
            $values[] = [':distance_high',  $fuelEconomyQueryConditions->getDistanceHigh(), \PDO::PARAM_STR];
        }
        //ガスステーション
        if( $fuelEconomyQueryConditions->getGasStation() !==null && mb_strlen($fuelEconomyQueryConditions->getGasStation()) >0 ){
            $wheres[] = 'gas_station like :gas_station';
            $values[] = [':gas_station', '%'.$fuelEconomyQueryConditions->getGasStation().'%', \PDO::PARAM_STR];
        }
        //メモ
        if( $fuelEconomyQueryConditions->getMemo() !==null && mb_strlen($fuelEconomyQueryConditions->getMemo()) >0 ){
            $wheres[] = 'memo like :memo';
            $values[] = [':memo', '%'.$fuelEconomyQueryConditions->getMemo().'%', \PDO::PARAM_STR];
        }
        //メモ
        if(! empty($fuelEconomyQueryConditions->getPage()))
            $offset_bind = [':offset', self::LIMIT *($fuelEconomyQueryConditions->getPage()-1), \PDO::PARAM_INT];
        else
            $offset_bind = [':offset', 0, \PDO::PARAM_INT];

        $query = ' select * from refuelings where '. implode( ' and ', $wheres ). ' limit :limit offset :offset';

        $stmt = $pdo->prepare( $query );

        foreach( $values as $value )
            $stmt->bindValue( ...$value );

        $stmt->bindValue( ...$limit_bind );
        $stmt->bindValue( ...$offset_bind );

        $stmt->execute();

        $list = $stmt->fetchAll( \PDO::FETCH_CLASS, FuelEconomyQueryModel::class );

        return $list;

    }
}
