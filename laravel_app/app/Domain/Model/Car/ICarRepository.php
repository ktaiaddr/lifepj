<?php


namespace App\Domain\Model\Car;



interface ICarRepository
{
    public function save(Car $car): bool;
    public function find(int $carId): Car;
    /**
     * @param int $personId
     * @return Car[]
     */
    public function findByOwnerId(int $personId): array;
}
