<?php


namespace App\infra\Repository;


use App\Domain\Object\Person\Person;

class InmemoryPersonRepository implements \App\Domain\Repository\IPersonRepository
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
                    return $_person->getPersonId();
                },$this->persons));
            }
            $person->setPersonIdForPersistent($max_id + 1);
            $this->persons[] = $person;
        }
        //永続化済みインスタンス
        else{
            /**
             * @var int $index
             * @var Person $_person
             */
            foreach( $this->persons as $index => $_person ){
                if( $person->getPersonId() === $_person->getPersonId()){
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
            if( $personId === $_person->getPersonId()){
                return $_person;
            }
        }
        return null;
    }
}
