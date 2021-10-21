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
     * @var int
     */
    public int $increase_decrease_type;

    /**
     * @param int $accountId
     * @param int $balance
     * @param string $name
     * @param int $increase_decrease_type
     */
    public function __construct(int $accountId, int $balance, string $name, int $increase_decrease_type)
    {
        $this->accountId = $accountId;
        $this->balance = $balance;
        $this->name = $name;
        $this->increase_decrease_type = $increase_decrease_type;
    }


}
