<?php


namespace App\Domain\Model\MoneyStorage;


interface IMoneyStorageEventRepository
{
    function save(MoneyStorageEvent $moneyStorageEvent): MoneyStorageEvent ;
    function findById(int $eventId): MoneyStorageEvent|null ;
    /**
     * @param MoneyStorageId $moneyStorageId
     * @return MoneyStorageEvent[]
     */
    function findByStorageId(MoneyStorageId $moneyStorageId): array ;
}
