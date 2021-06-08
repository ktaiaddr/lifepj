<?php


namespace App\Domain\Object\Person;

interface IPersonRepository
{
    public function save(Person $person): bool;
    public function find(int $personId): Person|null;

}
