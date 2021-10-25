<?php

namespace App\Application\HouseholdAccount\QueryModel;

class BalanceAggregateViewModel
{
    public ?int $account_id;
    public ?string $account_name;
    /**
     * @var ?int 締月データ以降の集計合計
     */
    public ?int $aggregate_balance;
    /**
     * @var ?int 最終締月の残高データ
     */
    public ?int $latest_closing_balance;
    /**
     * @var int|null
     */
    public ?int $balance;

    public ?string $closing_next_month_day_of_first;

    /**
     * @param int $account_id
     * @param string $account_name
     * @param int $aggregate_balance
     * @param int|null $latest_closing_balance
     * @param string $closing_next_month_day_of_first
     */
    public function __construct()
    {
//        $this->account_id = $account_id;
//        $this->account_name = $account_name;
//        $this->aggregate_balance = $aggregate_balance;
//        $this->latest_closing_balance = $latest_closing_balance;
//        $this->closing_next_month_day_of_first = $closing_next_month_day_of_first;

        $this->balance = $this->aggregate_balance + $this->latest_closing_balance;
    }


}
