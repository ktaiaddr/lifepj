<?php


namespace App\infra\Repository;


use App\Domain\Object\Person\Person;
use App\Domain\Object\Person\IPersonRepository;

class InmemoryPersonRepository implements IPersonRepository
{

    /** @var array Person[] */
    public array $persons = [];

    public function save(Person $person): bool
    {
        //新規インスタンス
        if( $person->isNewObject() ){
            $max_id = 0;
            if( count($this->persons) >0 ){
                $max_id = max( array_map(function( Person $_person ){
                    return $_person->getId();
                },$this->persons));
            }
            $person->setIdForNewObject($max_id + 1);
            $this->persons[] = $person;
        }
        //永続化済みインスタンス
        else{
            /**
             * @var int $index
             * @var Person $_person
             */
            foreach( $this->persons as $index => $_person ){
                if( $person->getId() === $_person->getId()){
                    $this->persons[$index] = $person;
                    break;
                }
            }
        }

        return true;
    }

    public function find(int $personId): Person|null
    {
        foreach( $this->persons as $index => $_person ){
            if( $personId === $_person->getId()){
                return $_person;
            }
        }
        return null;
    }
}
