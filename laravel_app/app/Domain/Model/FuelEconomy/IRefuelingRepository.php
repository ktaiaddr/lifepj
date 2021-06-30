<?php


namespace App\Domain\Model\FuelEconomy;


interface IRefuelingRepository
{
    function save(Refueling $refueling): int;
    function find(int $refuelingId): Refueling;
}
