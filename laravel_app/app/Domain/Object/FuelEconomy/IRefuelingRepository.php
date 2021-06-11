<?php


namespace App\Domain\Object\FuelEconomy;


interface IRefuelingRepository
{
    function save(Refueling $refueling): void;
    function find(int $refuelingId): Refueling;
}
