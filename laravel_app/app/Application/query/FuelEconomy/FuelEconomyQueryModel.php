<?php


namespace App\Application\query\FuelEconomy;


use App\Domain\Model\FuelEconomy\FuelEconomy;

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

        try{
            $fuelEconomy = new FuelEconomy($this->refueling_amount,$this->refueling_distance);
            return $fuelEconomy->calcFuelEconomy();

        }catch(\Exception $e){

        }
    }
}
