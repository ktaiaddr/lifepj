<?php


namespace App\Domain\Object\FuelEconomy;


class FuelEconomy
{
    /** @var float  */
    private float $refuelingAmount;
    /** @var float  */
    private float $refuelingDistance;

    /**
     * FuelEconomy constructor.
     * @param float $refuelingAmount
     * @param float $refuelingDistance
     */
    public function __construct(float $refuelingAmount, float $refuelingDistance)
    {
        if(! ($refuelingAmount > 0) ) throw new \Exception('給油量が無効です（0以下です）');
        if(! ($refuelingDistance > 0) ) throw new \Exception('走行距離が無効です（0以下です）');
        $this->refuelingAmount = $refuelingAmount;
        $this->refuelingDistance = $refuelingDistance;
    }

    /**
     * 燃費（キロ/リットル）を返す
     * @return float
     */
    #[Pure] public function calcFuelEconomy(): float{
        return round( $this->refuelingDistance / $this->refuelingAmount,2);
    }
}
