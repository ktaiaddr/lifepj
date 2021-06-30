<?php


namespace App\Application\query\FuelEconomy;


interface FuelEconomyQueryService
{
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
