<?php
namespace App\Domain\Object\FuelEconomy;
use JetBrains\PhpStorm\Pure;

/**
 * Class Refueling 給油クラス
 * @package App\Domain\Object\FuelEconomy
 */
class Refueling extends \App\Domain\Object\Entity
{
    const KEY = 'refuelingId';

    /** @var ?int key */
    private ?int $refuelingId;

    /** @var FuelEconomy  */
    private FuelEconomy $fuelEconomy;

    /** @var string  */
    private string $gasStation;

    /** @var string  */
    private string $memo;

    /**
     * Refueling constructor.
     * @param ?int $refuelingId
     * @param FuelEconomy $fuelEconomy
     * @param string $gasStation
     * @param string $memo
     * @throws \Exception
     */
    public function __construct(?int $refuelingId, FuelEconomy $fuelEconomy, string $gasStation, string $memo)
    {
        if( $refuelingId !== null && $refuelingId < 1 ) throw new \Exception('idは1以上の数値です', 4101);
        $this->refuelingId = $refuelingId;
        $this->fuelEconomy = $fuelEconomy;
        $this->gasStation = $gasStation;
        $this->memo = $memo;
    }

    #[Pure] public function calcFuelEconomy(): float
    {
        return $this->fuelEconomy->calcFuelEconomy();
    }

    public function updateMemo(string $memo){
        $this->memo = $memo;
    }

    /**
     * @param IRefuelingNotification $refuelingModelBuilder
     */
    public function notify(IRefuelingNotification $refuelingModelBuilder )
    {
        var_dump($this->refuelingId,1);
        $refuelingModelBuilder->refuelingId($this->refuelingId);
        $refuelingModelBuilder->fuelEconomy($this->fuelEconomy);
        $refuelingModelBuilder->gasStation($this->gasStation);
        $refuelingModelBuilder->memo($this->memo);
    }
}
