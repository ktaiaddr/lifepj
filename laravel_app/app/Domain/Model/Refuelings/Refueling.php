<?php
namespace App\Domain\Model\Refuelings;
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

    /** @var ?string  */
    private ?string $gasStation;

    /** @var ?string  */
    private ?string $memo;

    /** @var ?int  */
    private ?int $delFlg;

    /**
     * Refueling constructor.
     * @param ?int $refuelingId
     * @param \Datetime $date
     * @param FuelEconomy $fuelEconomy
     * @param ?string $gasStation
     * @param ?string $memo
     * @param ?int $del_flg
     * @throws \Exception
     */
    public function __construct(?int $refuelingId, int $userId, \DateTime $date, FuelEconomy $fuelEconomy, ?string $gasStation, ?string $memo, ?int $del_flg)
    {
        if( $refuelingId !== null && $refuelingId < 1 ) throw new \Exception('idは1以上の数値です', 4101);
        $this->refuelingId = $refuelingId;
        $this->userId      = $userId;
        $this->date        = $date;
        $this->fuelEconomy = $fuelEconomy;
        $this->gasStation  = $gasStation;
        $this->memo        = $memo;
        $this->delFlg      = $del_flg;
    }

    /**
     * 燃費を返します
     * @return float
     */
    #[Pure] public function calcFuelEconomy(): float
    {
        return $this->fuelEconomy->calcFuelEconomy();
    }

    /**
     * @param IRefuelingNotification $refuelingModelBuilder
     */
    public function notify( IRefuelingNotification $refuelingModelBuilder )
    {
        $refuelingModelBuilder->refuelingId( $this->refuelingId );
        $refuelingModelBuilder->userId     ( $this->userId      );
        $refuelingModelBuilder->date       ( $this->date        );
        $refuelingModelBuilder->gasStation ( $this->gasStation  );
        $refuelingModelBuilder->memo       ( $this->memo        );
        $refuelingModelBuilder->delFlg     ( $this->delFlg       );

        $this->fuelEconomy->notify( $refuelingModelBuilder      );
    }

    /**
     * @throws \Exception
     */
    public function update(UpdateRefuelingCommand $updateRefuelingCommand){

        $this->fuelEconomy = $this->fuelEconomy->recreate(
            $updateRefuelingCommand->refuelingAmount?:null,
            $updateRefuelingCommand->refuelingDistance?:null
        );

        if($updateRefuelingCommand->date != null)
            $this->date = $updateRefuelingCommand->date;

        if($updateRefuelingCommand->gasStation != null)
            $this->gasStation = $updateRefuelingCommand->gasStation;

        if($updateRefuelingCommand->memo != null)
            $this->memo = $updateRefuelingCommand->memo;

        if($updateRefuelingCommand->delFlg != null)
            $this->delFlg = $updateRefuelingCommand->delFlg;
    }

}
