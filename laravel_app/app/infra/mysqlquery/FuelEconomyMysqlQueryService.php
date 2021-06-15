<?php


namespace App\infra\mysqlquery;


use App\Application\query\FuelEconomy\FuelEconomyQueryConditions;
use App\Application\query\FuelEconomy\FuelEconomyQueryModel;
use Illuminate\Support\Facades\DB;

class FuelEconomyMysqlQueryService implements \App\Application\query\FuelEconomy\FuelEconomyQueryService
{

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
        $list = $stmt->fetchAll( \PDO::FETCH_CLASS, FuelEconomyQueryModel::class);

        return $list;
    }

    /**
     * @inheritDoc
     */
    function findByUseridAndCondition(int $userId, FuelEconomyQueryConditions $fuelEconomyQueryConditions): array
    {
        // TODO: Implement findByUseridAndCondition() method.
    }
}
