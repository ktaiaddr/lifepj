<?php
namespace App\Domain\Model\FuelEconomy;
use JetBrains\PhpStorm\Pure;

/**
 * Class Refueling 給油クラス
 * @package App\Domain\Object\FuelEconomy
 */
class Refueling extends \App\Domain\Model\Entity
{
    const KEY = 'refuelingId';

    /** @var ?int key */
    protected ?int $refuelingId;

    /** @var int  */
    private int $userId;

    /** @var \DateTime  */
    private \DateTime $date;

    /** @var FuelEconomy  */
    private FuelEconomy $fuelEconomy;

    /** @var string  */
    private string $gasStation;

    /** @var string  */
    private string $memo;

    /**
     * Refueling constructor.
     * @param ?int $refuelingId
     * @param \Datetime $date
     * @param FuelEconomy $fuelEconomy
     * @param string $gasStation
     * @param string $memo
     * @throws \Exception
     */
    public function __construct(?int $refuelingId, int $userId, \DateTime $date, FuelEconomy $fuelEconomy, string $gasStation, string $memo)
    {
        if( $refuelingId !== null && $refuelingId < 1 ) throw new \Exception('idは1以上の数値です', 4101);
        $this->refuelingId = $refuelingId;
        $this->userId = $userId;
        $this->date = $date;
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

        $refuelingModelBuilder->refuelingId($this->refuelingId);
        $refuelingModelBuilder->userId($this->userId);
        $refuelingModelBuilder->date($this->date);
        $refuelingModelBuilder->fuelEconomy($this->fuelEconomy);
        $refuelingModelBuilder->gasStation($this->gasStation);
        $refuelingModelBuilder->memo($this->memo);
    }

    /**
     * @throws \Exception
     */
    public function update(UpdateRefuelingCommand $updateRefuelingCommand){

        if($updateRefuelingCommand->refuelingDistance !==null || $updateRefuelingCommand->refuelingAmount !==null){
            $newRefuelingDistance = $updateRefuelingCommand->refuelingDistance !==null
                ? $updateRefuelingCommand->refuelingDistance
                : $this->fuelEconomy->getRefuelingDistance();

            $newRefuelingAmount = $updateRefuelingCommand->refuelingAmount !==null
                ? $updateRefuelingCommand->refuelingAmount
                : $this->fuelEconomy->getRefuelingAmount();

            $this->fuelEconomy = new FuelEconomy($newRefuelingAmount,$newRefuelingDistance);
        }

        if($updateRefuelingCommand->gasStation != null)
            $this->gasStation = $updateRefuelingCommand->gasStation;

        if($updateRefuelingCommand->memo != null)
            $this->memo = $updateRefuelingCommand->memo;

    }

}
