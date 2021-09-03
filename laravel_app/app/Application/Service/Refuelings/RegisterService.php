<?php
namespace App\Application\Service\Refuelings;

use App\Domain\Model\Refuelings\FuelEconomy;
use App\Domain\Model\Refuelings\IRefuelingRepository;
use App\Domain\Model\Refuelings\Refueling;
use App\Domain\Model\Refuelings\UpdateRefuelingCommand;
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

        $refueling = new Refueling(
            null,
            $userId,
            $refuelingCommand->date,
            new FuelEconomy($refuelingCommand->refuelingAmount, $refuelingCommand->refuelingDistance),
            $refuelingCommand->gasStation,
            $refuelingCommand->memo,
            0
        );

        return $this->refuelingRepository->save($refueling);
    }


    /**
     * @param UpdateRefuelingCommand $refuelingCommand
     * @param int $userId
     * @return int
     * @throws \Exception
     */
    public function update(UpdateRefuelingCommand $refuelingCommand, int $userId):int
    {
        //既存データの取り出し
        $refueling = $this->refuelingRepository->find( $refuelingCommand->refuelingId, $userId );

        if(! $refueling)
            throw new \Exception('更新対象のデータが見つかりません',404 );

        //コマンドによる更新
        $refueling->update( $refuelingCommand );

        //保存してIDを返す
        return $this->refuelingRepository->save($refueling);
    }

}
