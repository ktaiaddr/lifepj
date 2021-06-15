<?php


namespace App\Application\query\FuelEconomy;


class FuelEconomyQueryModel
{
    public int $user_id;

    public string $date;

    public float $refueling_amount;

    public float $refueling_distance;

    public string $gas_station;

    public string $memo;

    /**
     * 燃費計算
     * @return float
     */
    public function calcFuelEconomy():float{
        return round( $this->refueling_distance / $this->refueling_amount,2);
    }
}
