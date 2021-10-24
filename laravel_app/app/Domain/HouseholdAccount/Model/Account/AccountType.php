<?php

namespace App\Domain\HouseholdAccount\Model\Account;

class AccountType
{
    const TYPE_BANK = 1;//銀行
    const TYPE_HAND_MONEY = 2;//ハンドマネー

    private int $value;

    /**
     * @param int $value
     * @throws \Exception
     */
    public function __construct(int $value)
    {
        if($value != self::TYPE_BANK && $value != self::TYPE_HAND_MONEY)
            throw new \Exception();

        $this->value = $value;
    }

    /**
     * @return string|null
     */
    public function typeLabel():?string{

        if($this->isHandMoney()) return "ハンドマネー";

        return "銀行口座";
    }

    public function isBank(): bool
    {
        return $this->value == self::TYPE_BANK;
    }

    public function isHandMoney(): bool
    {
        return $this->value == self::TYPE_HAND_MONEY;
    }
}
