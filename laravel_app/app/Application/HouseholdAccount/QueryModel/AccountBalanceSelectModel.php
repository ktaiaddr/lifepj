<?php

namespace App\Application\HouseholdAccount\QueryModel;

use App\Domain\HouseholdAccount\Model\Account\AccountType;

class AccountBalanceSelectModel
{
    /**
     * @var int 口座ID
     */
    public int $accountId;
    /**
     * @var int 種別値
     */
    public int $accountTypeValue;
    /**
     * @var string 種別ラベル
     */
    public string $accountTypeLabel;
    /**
     * @var AccountType 種別
     */
    private AccountType $accountType;
    /**
     * @var string 口座名
     */
    public string $name;

    /**
     * @param int $accountId
     * @param int $accountTypeValue
     * @param AccountType $accountType
     * @param string $name
     */
    public function __construct(int $accountId, int $accountTypeValue,AccountType $accountType, string $name)
    {
        $this->accountId = $accountId;
        $this->accountTypeValue = $accountTypeValue;
        $this->accountTypeLabel = $accountType->typeLabel();
        $this->accountType = $accountType;
        $this->name = $name;
    }

    /**
     * @return bool
     */
    public function isHandMoney(){
        return $this->accountType->isHandMoney();
    }

    /**
     * @return bool
     */
    public function isBank(){
        return $this->accountType->isBank();
    }
}
