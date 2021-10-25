<?php

namespace App\Application\HouseholdAccount\service;

use App\Application\HouseholdAccount\component\TransactionSearchRange;
use App\Application\HouseholdAccount\component\ViewPage;
use App\Application\HouseholdAccount\query\TransactionViewQuery;
use App\Application\HouseholdAccount\QueryModel\BalanceAggregateViewModel;
use App\Application\HouseholdAccount\QueryModel\TransactionViewModel;
use App\Domain\HouseholdAccount\Model\Transaction\SearchCommand;

class TransactionViewService
{

    private TransactionViewQuery $transactionViewQuery;
    private TransactionRegisterViewService $transactionRegisterViewService;

    /**
     * @param TransactionViewQuery $transactionViewQuery
     */
    public function __construct(TransactionViewQuery $transactionViewQuery,
                                TransactionRegisterViewService $transactionRegisterViewService)
    {
        $this->transactionViewQuery = $transactionViewQuery;
        $this->transactionRegisterViewService = $transactionRegisterViewService;
    }

    /**
     * @param SearchCommand $searchCommand
     * @param string $user_id
     */
    public function generateTemporaryLatestClosingData(SearchCommand $searchCommand, string $user_id):void
    {
        $this->transactionViewQuery->createTemporaryLatestClosing($searchCommand, $user_id );
    }

    /**
     * @param SearchCommand $searchCommand
     * @param string $user_id
//     * @return TransactionViewModel[]
     * @return ViewPage|null
     */
    public function do(SearchCommand $searchCommand, string $user_id):ViewPage|null{

        try{

            $transactionViewModelList = $this->transactionViewQuery->find($searchCommand, $user_id );

            $balanceAggregateViewModelList = $this->transactionViewQuery->getBalanceAggregateData($searchCommand, $user_id );

            $registerPageComponents = $this->transactionRegisterViewService->getComponents($user_id);

            $transactionSearchRange = $this->transactionViewQuery->getTransactionSearchRange();

            $viewPageComponent = new ViewPage($transactionViewModelList,$balanceAggregateViewModelList,$registerPageComponents,$transactionSearchRange);

        }
        catch(\Exception $e){
//            $transactionViewModelList = [];
            $viewPageComponent = null;

        }

//        return $transactionViewModelList;
        return $viewPageComponent;
    }

    /**
     * @param SearchCommand $searchCommand
     * @param string $user_id
     * @return BalanceAggregateViewModel[]
     */
    public function getAggregateData(SearchCommand $searchCommand, string $user_id):array
    {
//        try{
            $balanceAggregateViewModelList = $this->transactionViewQuery->getBalanceAggregateData($searchCommand, $user_id );
//        }
//        catch(\Exception $e){
//            $balanceAggregateViewModelList = [];
//        }

        return $balanceAggregateViewModelList;

    }

}
