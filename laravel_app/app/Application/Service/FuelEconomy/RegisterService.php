<?php
namespace App\Application\Service\FuelEconomy;

use App\Domain\Model\FuelEconomy\FuelEconomy;
use App\Domain\Model\FuelEconomy\IRefuelingRepository;
use App\Domain\Model\FuelEconomy\Refueling;
use App\Domain\Model\FuelEconomy\UpdateRefuelingCommand;
use App\Presentation\FuelEconomy\RefuelingData;

class RegisterService
{
    private IRefuelingRepository $refuelingRepository;

    /**
     * Register constructor.
     * @param IRefuelingRepository $refuelingRepository
     */
    public function __construct(IRefuelingRepository $refuelingRepository)
    {
        //コンストラクタインジェクション
        $this->refuelingRepository = $refuelingRepository;
    }

    /**
     * @param UpdateRefuelingCommand $refuelingCommand
     * @param int $userId
     * @return int
     * @throws \Exception
     */
    public function regist(UpdateRefuelingCommand $refuelingCommand, int $userId):int
    {
        //新規登録
        if( $refuelingCommand->isNew() )
            return $this->refuelingRepository->save(
                new Refueling(
                    null,
                    $userId,
                    $refuelingCommand->date,
                    new FuelEconomy( $refuelingCommand->refuelingAmount, $refuelingCommand->refuelingDistance ),
                    $refuelingCommand->gasStation,
                    $refuelingCommand->memo,
                    0
                )
            );

        //更新
        $refueling = $this->refuelingRepository->find( $refuelingCommand->refuelingId, $userId );
        if(! $refueling)
            throw new \Exception('更新対象のデータが見つかりません',404 );
        $refueling->update( $refuelingCommand );
        return $this->refuelingRepository->save($refueling);
    }

}
