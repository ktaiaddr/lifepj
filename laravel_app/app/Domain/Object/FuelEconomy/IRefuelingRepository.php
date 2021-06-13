<?php


namespace App\Domain\Object\FuelEconomy;


interface IRefuelingRepository
{
    function save(Refueling $refueling): int;
    function find(int $refuelingId): Refueling;
}
