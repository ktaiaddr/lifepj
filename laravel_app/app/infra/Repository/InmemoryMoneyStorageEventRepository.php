<?php
namespace App\infra\Repository;
use App\Domain\Model\MoneyStorage\MoneyStorageEvent;
use App\Domain\Model\MoneyStorage\MoneyStorageId;

class InmemoryMoneyStorageEventRepository implements \App\Domain\Model\MoneyStorage\IMoneyStorageEventRepository
{
    /** @var array MoneyStorageEvent[] */
    public array $moneyStorageEvent = [];

    function save(MoneyStorageEvent $moneyStorageEvent): MoneyStorageEvent
    {
        //新規インスタンス
        if( $moneyStorageEvent->isNewObject() ){
            $max_id = 0;
            if( count($this->moneyStorageEvent) >0 ){
                $max_id = max( array_map(function( MoneyStorageEvent $_moneyStorageEvent ){
                    return $_moneyStorageEvent->getId();
                },$this->moneyStorageEvent));
            }
            $moneyStorageEvent->setIdForNewObject($max_id + 1);
            $this->moneyStorageEvent[] = $moneyStorageEvent;
        }
        //永続化済みインスタンス
        else{
            /**
             * @var int $index
             * @var Person $_person
             */
            foreach( $this->moneyStorageEvent as $index => $_moneyStorageEvent ){
                if( $moneyStorageEvent->getId() === $_moneyStorageEvent->getId()){
                    $this->moneyStorageEvent[$index] = $moneyStorageEvent;
                    break;
                }
            }
        }

        return $moneyStorageEvent;
    }

    function findById(int $eventId): MoneyStorageEvent|null
    {
        foreach( $this->moneyStorageEvent as $index => $_moneyStorageEvent ){
            if( $eventId === $_moneyStorageEvent->getId()){
                return $_moneyStorageEvent;
            }
        }
        return null;
    }

    /**
     * @inheritDoc
     */
    function findByStorageId(MoneyStorageId $moneyStorageId): array
    {
        // TODO: Implement findByStorageId() method.
    }
}
