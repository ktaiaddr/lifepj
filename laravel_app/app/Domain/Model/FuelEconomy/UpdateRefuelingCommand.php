<?php


namespace App\Domain\Model\FuelEconomy;


class UpdateRefuelingCommand
{
    public ?int $refuelingId;
    public ?\DateTime $date;
    public ?float $refuelingAmount;
    public ?float $refuelingDistance;
    public ?string $gasStation;
    public ?string $memo;
    public ?int $delFlg;

    /**
     * UpdateRefuelingCommand constructor.
     * @param int|null $refuelingId
     * @param \DateTime|null $date
     * @param float|null $refuelingAmount
     * @param float|null $refuelingDistance
     * @param string|null $gasStation
     * @param string|null $memo
     * @param int|null $del_flg
     */
    public function __construct(
        int $refuelingId=null, \DateTime $date=null, float $refuelingAmount=null,
        float $refuelingDistance=null, string $gasStation=null, string $memo=null,
        ?int $del_flg =0)
    {
        $this->refuelingId = $refuelingId;
        $this->date = $date;
        $this->refuelingAmount = $refuelingAmount;
        $this->refuelingDistance = $refuelingDistance;
        $this->gasStation = $gasStation;
        $this->memo = $memo;
        $this->delFlg = $del_flg;
    }

    public function isNew():bool {
        return $this->refuelingId === null;
    }


}
