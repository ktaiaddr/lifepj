<?php

namespace App\Application\HouseholdAccount\component;

use App\Application\HouseholdAccount\QueryModel\BalanceAggregateViewModel;
use App\Application\HouseholdAccount\QueryModel\TransactionViewModel;
use App\Application\HouseholdAccount\service\RegisterPageComponents;

class ViewPage
{

    /** @var TransactionViewModel[] */
    public array $transactionViewModels;

    /** @var BalanceAggregateViewModel[]  */
    public array $balanceAggregateViewModel;

    /** @var RegisterPageComponents */
    public RegisterPageComponents $registerPageComponents;

    /** @var TransactionSearchRange  */
    public TransactionSearchRange $transactionSearchRange;

    /**
     * @param TransactionViewModel[] $transactionViewModels
     * @param BalanceAggregateViewModel[] $balanceAggregateViewModel
     * @param RegisterPageComponents $registerPageComponents
     * @param TransactionSearchRange $transactionSearchRange
     */
    public function __construct(array $transactionViewModels, array $balanceAggregateViewModel, RegisterPageComponents $registerPageComponents, TransactionSearchRange $transactionSearchRange)
    {
        $this->transactionViewModels = $transactionViewModels;
        $this->balanceAggregateViewModel = $balanceAggregateViewModel;
        $this->registerPageComponents = $registerPageComponents;
        $this->transactionSearchRange = $transactionSearchRange;
    }


}
