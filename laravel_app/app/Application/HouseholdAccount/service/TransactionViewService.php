<?php

namespace App\Application\HouseholdAccount\service;

use App\Application\HouseholdAccount\query\TransactionViewQuery;
use App\Application\HouseholdAccount\QueryModel\TransactionViewModel;
use App\Domain\HouseholdAccount\Model\Transaction\SearchCommand;

class TransactionViewService
{

    private TransactionViewQuery $transactionViewQuery;

    /**
     * @param TransactionViewQuery $transactionViewQuery
     */
    public function __construct(TransactionViewQuery $transactionViewQuery)
    {
        $this->transactionViewQuery = $transactionViewQuery;
    }

    /**
     * @param int $user_id
     * @return TransactionViewModel[]
     */
    public function do(SearchCommand $searchCommand, string $user_id):array{

        try{
            $transactionViewModelList = $this->transactionViewQuery->find($searchCommand, $user_id );
        }
        catch(\Exception $e){
            $transactionViewModelList = [];
        }

        return $transactionViewModelList;
    }

}
