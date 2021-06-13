<?php


namespace App\Application\Service\FuelEconomy;


use App\Domain\Object\FuelEconomy\IRefuelingRepository;

class ListDataService
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

    public function get(){

    }

}
