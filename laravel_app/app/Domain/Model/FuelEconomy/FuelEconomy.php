<?php
namespace App\Domain\Model\FuelEconomy;

use Exception;

/**
 * 燃費クラス
 * Class FuelEconomy
 * @package App\Domain\Object\FuelEconomy
 */
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
     * @throws Exception
     */
    public function __construct(float $refuelingAmount, float $refuelingDistance)
    {
        if(! ($refuelingAmount > 0) ) throw new Exception('給油量が無効です（0以下です）');
        if(! ($refuelingDistance > 0) ) throw new Exception('走行距離が無効です（0以下です）');

        $this->refuelingAmount = $refuelingAmount;
        $this->refuelingDistance = $refuelingDistance;
    }

    /**
     * @param float|null $refuelingAmount
     * @param float|null $refuelingDistance
     * @return FuelEconomy
     * @throws Exception
     */
    public function recreate(?float $refuelingAmount, ?float $refuelingDistance): FuelEconomy
    {
        return new FuelEconomy(
            $refuelingAmount    ?: $this->refuelingAmount,
            $refuelingDistance ?: $this->refuelingDistance
        );
    }

    /**
     * 燃費（キロ/リットル）を返す
     * @return float
     */
    #[Pure] public function calcFuelEconomy(): float{
        return round( $this->refuelingDistance / $this->refuelingAmount,2);
    }

    public function notify(IRefuelingNotification $refuelingModelBuilder){
        $refuelingModelBuilder->refuelingAmount($this->refuelingAmount);
        $refuelingModelBuilder->refuelingDistance($this->refuelingDistance);
    }

}
