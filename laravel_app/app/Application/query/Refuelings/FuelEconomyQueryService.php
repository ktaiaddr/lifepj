<?php


namespace App\Application\query\Refuelings;


interface FuelEconomyQueryService
{

    function findByUseridAndRefuelingid(int $userId, int $refueling_id);

    /**
     * @param int $userId
     * @return FuelEconomyQueryModel[]
     */
    function findByUserid(int $userId): array;
    /**
     * @return FuelEconomyQueryModel[]
     */
    function findByUseridAndCondition(int $userId, FuelEconomyQueryConditions $fuelEconomyQueryConditions): array;
}
