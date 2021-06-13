<?php


namespace App\Domain\Object\FuelEconomy;


class UpdateRefuelingCommand
{
    public ?int $refuelingId;
    public ?float $refuelingAmount;
    public ?float $refuelingDistance;
    public ?string $gasStation;
    public ?string $memo;

    /**
     * updateRefuelingCommand constructor.
     * @param int $refuelingId
     * @param float $refuelingAmount
     * @param float $refuelingDistance
     * @param string $gasStation
     * @param string $memo
     */
    public function __construct(int $refuelingId=null, float $refuelingAmount=null, float $refuelingDistance=null, string $gasStation=null, string $memo=null)
    {
        $this->refuelingId = $refuelingId;
        $this->refuelingAmount = $refuelingAmount;
        $this->refuelingDistance = $refuelingDistance;
        $this->gasStation = $gasStation;
        $this->memo = $memo;
    }

    public function isNew():bool {
        return $this->refuelingId === null;
    }


}
