<?php


namespace App\infra\Repository;


use App\Domain\Model\Car\Car;

class InmemoryCarRepository implements \App\Domain\Model\Car\ICarRepository
{

    /** @var Car[] */
    private array $cars;

    public function save(Car $car): bool
    {
        //新規インスタンス
        if( $car->isNewObject() ){
            $max_id = 0;
            if( count($this->persons) >0 ){
                $max_id = max( array_map(function( Person $_person ){
                    return $_person->getPersonId();
                },$this->persons));
            }
            $car->setPersonIdForPersistent($max_id + 1);
            $this->persons[] = $car;
        }
        //永続化済みインスタンス
        else{
            /**
             * @var int $index
             * @var Person $_person
             */
            foreach( $this->persons as $index => $_person ){
                if( $car->getPersonId() === $_person->getPersonId()){
                    $this->persons[$index] = $car;
                    break;
                }
            }
        }

        return true;
    }

    public function find(int $carId): Car
    {
        // TODO: Implement find() method.
    }

    /**
     * @inheritDoc
     */
    public function findByOwnerId(int $personId): array
    {
        // TODO: Implement findByOwnerId() method.
    }
}
