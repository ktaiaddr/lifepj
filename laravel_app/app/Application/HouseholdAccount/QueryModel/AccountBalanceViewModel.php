<?php

namespace App\Application\HouseholdAccount\QueryModel;

class AccountBalanceViewModel
{
    /**
     * @var int 口座ID
     */
    public int $accountId;
    /**
     * @var int 残高
     */
    public int $balance;
    /**
     * @var string 口座名
     */
    public string $name;

    /**
     * @param int $accountId
     * @param int $balance
     * @param string $name
     */
    public function __construct(int $accountId, int $balance, string $name)
    {
        $this->accountId = $accountId;
        $this->balance = $balance;
        $this->name = $name;
    }


}
