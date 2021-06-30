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
        $this->userId      = $userId;
        $this->date        = $date;
        $this->fuelEconomy = $fuelEconomy;
        $this->gasStation  = $gasStation;
        $this->memo        = $memo;
    }

    /**
     * 燃費を返します
     * @return float
     */
    #[Pure] public function calcFuelEconomy(): float
    {
        return $this->fuelEconomy->calcFuelEconomy();
    }

//    //メモを更新します
//    public function updateMemo(string $memo){
//        $this->memo = $memo;
//    }

    /**
     * @param IRefuelingNotification $refuelingModelBuilder
     */
    public function notify(IRefuelingNotification $refuelingModelBuilder )
    {
        $refuelingModelBuilder->refuelingId($this->refuelingId);
        $refuelingModelBuilder->userId($this->userId);
        $refuelingModelBuilder->date($this->date);
        $refuelingModelBuilder->gasStation($this->gasStation);
        $refuelingModelBuilder->memo($this->memo);

        $this->fuelEconomy->notify($refuelingModelBuilder);
    }

    /**
     * @throws \Exception
     */
    public function update(UpdateRefuelingCommand $updateRefuelingCommand){

        $this->fuelEconomy = $this->fuelEconomy->recreate(
            $updateRefuelingCommand->refuelingAmount?:null,
            $updateRefuelingCommand->refuelingDistance?:null
        );

        if($updateRefuelingCommand->gasStation != null)
            $this->gasStation = $updateRefuelingCommand->gasStation;

        if($updateRefuelingCommand->memo != null)
            $this->memo = $updateRefuelingCommand->memo;

    }

}
