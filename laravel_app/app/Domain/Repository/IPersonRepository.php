<?php


namespace App\Domain\Repository;


use App\Domain\Object\Person\Person;

interface IPersonRepository
{
    public function save(Person $person): bool;
    public function find(int $personId): Person|null;

}
