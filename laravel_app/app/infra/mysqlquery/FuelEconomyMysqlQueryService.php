<?php


namespace App\infra\mysqlquery;


use App\Application\query\FuelEconomy\FuelEconomyQueryConditions;
use App\Application\query\FuelEconomy\FuelEconomyQueryModel;
use App\Http\Requests\Refuelings\SearchRequest;
use Illuminate\Support\Facades\DB;

class FuelEconomyMysqlQueryService implements \App\Application\query\FuelEconomy\FuelEconomyQueryService
{

    /**
     * @param int $userId
     * @param int $refueling_id
     * @return FuelEconomyQueryModel
     */
    function findByUseridAndRefuelingid(int $userId, int $refueling_id){

        $pdo = DB::getPdo();

//        $query = ' select * from refuelings where user_id = :user_id and refueling_id = :refueling_id and del_flg = 0 order by id desc limit 1';
        $query = ' select * from refuelings where id = (select max(id)  from refuelings where user_id = :user_id and  refueling_id = :refueling_id) and del_flg = 0';

        $stmt = $pdo->prepare( $query );

        $stmt->bindValue( 'user_id', $userId );
        $stmt->bindValue( 'refueling_id', $refueling_id );

        $stmt->execute();

        return $stmt->fetchAll( \PDO::FETCH_CLASS, FuelEconomyQueryModel::class);

    }

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

        // ユーザID
        $wheres[] = ' user_id = :user_id ';
        $values[] = [':user_id', $userId, \PDO::PARAM_INT];
        $values[] = [':user_id_group', $userId, \PDO::PARAM_INT];
        //日付開始日
        if( $fuelEconomyQueryConditions->getDateStart() !==null ){
            $wheres[] = 'date >= :date_start';
            $values[] = [':date_start', $fuelEconomyQueryConditions->getDateStart()->format('Y-m-d'), \PDO::PARAM_STR];
        }

        //日付終了日
        if( $fuelEconomyQueryConditions->getDateEnd() !==null ){
            $wheres[] = 'date <= :date_end';
            $values[] = [':date_end',  $fuelEconomyQueryConditions->getDateEnd()->format('Y-m-d'), \PDO::PARAM_STR];
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

        //件数取得用クエリ
        $count_query  = "   select  count(*) as count ";
        $count_query .= "     from  refuelings, ";
        $count_query .= '           ( ';
        $count_query .= '              select max(id) as maxid ';
        $count_query .= '                from refuelings ';
        $count_query .= '               where user_id = :user_id_group  ';
        $count_query .= '            group by refueling_id  ';
        $count_query .= '           ) as max_refueling_ids ';
        $count_query .= "    where  ". implode( ' and ', $wheres );
        $count_query .= '      and  refuelings.id = max_refueling_ids.maxid ';
        $count_query .= "      and  del_flg = 0 ";

        $count_stmt = $pdo->prepare( $count_query );
        foreach( $values as $value ) $count_stmt->bindValue( ...$value );
        //件数取得
        $count_stmt->execute();
        $result = $count_stmt->fetch( \PDO::FETCH_ASSOC );
        $count = $result['count'];

        // order by 値
        $order_by_value = match ($fuelEconomyQueryConditions->getSortKey()) {
            SearchRequest::SORT_KEY_DISTANCE => ' refueling_distance ',
            SearchRequest::SORT_KEY_AMOUNT => ' refueling_amount ',
            SearchRequest::SORT_KEY_FUELECONOMY => ' (refueling_distance / refueling_amount) ',
            SearchRequest::SORT_KEY_GASSTATION => ' gas_station ',
            SearchRequest::SORT_KEY_MEMO => ' memo ',
            default => ' date ',
        };

        // 並び
        $sort_order = match ($fuelEconomyQueryConditions->getSortOrder()) {
            SearchRequest::SORT_ASC => ' asc ',
            default => ' desc ',
        };

        //クエリ組み立て
        $query  = "   select  * ";
        $query .= "     from  refuelings, ";
        $query .= '           ( ';
        $query .= '              select max(id) as maxid ';
        $query .= '                from refuelings ';
        $query .= '               where user_id = :user_id_group  ';
        $query .= '            group by refueling_id  ';
        $query .= '           ) as max_refueling_ids ';
        $query .= "    where  ". implode( ' and ', $wheres );
        $query .= '      and  refuelings.id = max_refueling_ids.maxid ';
        $query .= "      and  del_flg = 0 ";
        $query .= " order by ". $order_by_value;
        $query .= " ".$sort_order;
        $query .= "    limit  :limit ";
        $query .= "   offset  :offset ";

        //ステートメント作成
        $stmt = $pdo->prepare( $query );

        //検索条件をバインド
        foreach( $values as $value ) $stmt->bindValue( ...$value );

        //1ページの表示件数を生成
        $limit = $fuelEconomyQueryConditions->getLimit() ?: self::LIMIT;
        $limit_bind = [':limit',$limit ,\PDO::PARAM_INT];

        //1ページの表示件数をバインド
        $stmt->bindValue( ...$limit_bind );

        //ページオフセットを生成
        $offset = $limit *(! empty($fuelEconomyQueryConditions->getPage())? $fuelEconomyQueryConditions->getPage()-1: 0);
        $offset_bind = [':offset', $offset, \PDO::PARAM_INT];

        //ページオフセットをバインド
        $stmt->bindValue( ...$offset_bind );

        //実行
        $stmt->execute();

        $list = $stmt->fetchAll( \PDO::FETCH_CLASS, FuelEconomyQueryModel::class );

        return [$list,$count];

    }

    const LIMIT = 5;

}
