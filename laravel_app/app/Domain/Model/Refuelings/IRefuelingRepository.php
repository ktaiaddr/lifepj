<?php


namespace App\Domain\Model\Refuelings;


interface IRefuelingRepository
{
    function save(Refueling $refueling): int;
    function find(int $refuelingId, int $userId): ?Refueling;
}
