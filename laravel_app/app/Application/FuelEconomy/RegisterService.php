<?php
namespace App\Application\FuelEconomy;

use App\Domain\Object\FuelEconomy\FuelEconomy;
use App\Domain\Object\FuelEconomy\IRefuelingRepository;
use App\Domain\Object\FuelEconomy\Refueling;
use App\Domain\Object\FuelEconomy\UpdateRefuelingCommand;
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
        $this->refuelingRepository = $refuelingRepository;
    }

    /**
     * @throws \Exception
     */
    public function regist(UpdateRefuelingCommand $refuelingCommand):int {
        //新規登録
        if( $refuelingCommand->isNew() ){
            $refueling = new Refueling( null,
                new FuelEconomy( $refuelingCommand->refuelingAmount, $refuelingCommand->refuelingDistance ),
                $refuelingCommand->gasStation, $refuelingCommand->memo );
        }
        //更新
        else{
            $refueling = $this->refuelingRepository->find( $refuelingCommand->refuelingId );
            $refueling->update( $refuelingCommand );
        }
        $refueling_id = $this->refuelingRepository->save($refueling);
        return $refueling_id;
    }
}
