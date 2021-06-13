<?php


namespace App\Application\Service\FuelEconomy;


use App\Domain\Model\FuelEconomy\IRefuelingRepository;

class ReadService
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

    public function readDetail(int $refuelingId){
        $refueling = $this->refuelingRepository->find($refuelingId);
    }

    public function readList(int $refuelingId){
        $refueling = $this->refuelingRepository->find($refuelingId);
    }

}
