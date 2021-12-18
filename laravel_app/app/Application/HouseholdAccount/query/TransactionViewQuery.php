<?php

namespace App\Application\HouseholdAccount\query;

use App\Application\HouseholdAccount\component\TransactionSearchRange;
use App\Application\HouseholdAccount\QueryModel\BalanceAggregateViewModel;
use App\Application\HouseholdAccount\QueryModel\TransactionViewModel;
use App\Domain\HouseholdAccount\Model\Transaction\SearchCommand;

interface TransactionViewQuery
{
    /**
     * @param SearchCommand $searchCommand
     * @param string $userId
     * @return void
     */
    public function createTemporaryLatestClosing(SearchCommand $searchCommand,string $userId):void;

    /**
     * @param SearchCommand $searchCommand
     * @param string $userId
     * @return TransactionViewModel[]
     */
    public function find(SearchCommand $searchCommand,string $userId):array;

    /**
     * @param SearchCommand $searchCommand
     * @param string $userId
     * @return BalanceAggregateViewModel[]
     */
    public function getBalanceAggregateData(SearchCommand $searchCommand,string $userId):array;

    public function getTransactionSearchRange(int $user_id):TransactionSearchRange;
}
