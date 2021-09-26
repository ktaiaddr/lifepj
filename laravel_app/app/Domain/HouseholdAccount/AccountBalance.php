<?php

namespace App\Domain\HouseholdAccount;

use App\Domain\HouseholdAccount\Model\Notification\Balance;
use App\Domain\HouseholdAccount\Model\Notification\NotificationTransaction;
use App\Domain\HouseholdAccount\Model\ValueObject\TransactionAmount;

class AccountBalance
{
    /**
     * @var int 口座ID
     */
    private int $accountId;

    /**
     * @var int 残高
     */
    private int $balance;


    const TYPE_BANK = 1;//銀行
    const TYPE_HAND_MONEY = 2;//ハンドマネー
    /**
     * @var int アカウント種別
     */
    private int $accountType;
    /**
     * アカウントのタイプが銀行ならtrue
     * @return bool
     */
    public function isBank(){
        return $this->accountType == self::TYPE_BANK;
    }
    /**
     * @return bool
     */
    public function isHandMoney(){
        return $this->accountType == self::TYPE_HAND_MONEY;
    }


    /**
     * @param int $accountId
     * @param int $balance
     * @param int $accountType
     */
    public function __construct(int $accountId, int $balance, int $accountType)
    {
        $this->accountId = $accountId;
        $this->balance = $balance;
        $this->accountType = $accountType;
    }

    /**
     * @param TransactionAmount $transactionAmount
     */
    public function increaseBalance(TransactionAmount $transactionAmount){

        $this->balance = $transactionAmount->increaseBalance($this->balance);

    }
    /**
     * @param TransactionAmount $transactionAmount
     */
    public function reduceBalance(TransactionAmount $transactionAmount){
        $this->balance = $transactionAmount->reduceBalance($this->balance);
    }


    /**
     * 通知
     * @param string $transactionId
     * @param NotificationTransaction $modelBuilder
     */
    public function notify(string $transactionId, NotificationTransaction $modelBuilder){
        $modelBuilder->addBalance(new Balance($transactionId,$this->accountId,$this->balance));
    }
}
