<?php

namespace App\Domain\HouseholdAccount\Model\Transaction;

use App\Domain\HouseholdAccount\Model\DepositsAndWithdrawals\Deposits;
use App\Domain\HouseholdAccount\Model\DepositsAndWithdrawals\Withdrawals;
use App\Domain\HouseholdAccount\Model\Notification\NotificationTransaction;
use App\Domain\HouseholdAccount\Model\ValueObject\TransactionAmount;
use Illuminate\Support\Facades\Log;


/**
 * 口座振替
 */
class AccountTransfer implements Transaction
{
    /**
     * 取引ID Str::orderedUuid()で発行
     * @var string
     */
    private string $transactionId;
    /**
     * @var \DateTime
     */
    private \DateTime $date;
    /**
     * 金額
     * @var TransactionAmount
     */
    private TransactionAmount $transactionAmount;
    /**
     * 支払い
     * @var Withdrawals
     */
    private Withdrawals $withdrawals;
    /**
     * 入金
     * @var Deposits
     */
    private Deposits $deposits;

    /**
     * @param string $transactionId
     * @param \DateTime $date
     * @param TransactionAmount $transactionAmount
     * @param Withdrawals $withdrawals
     * @param Deposits $deposits
     * @throws \Exception
     */
    public function __construct(string $transactionId, \DateTime $date, TransactionAmount $transactionAmount, Withdrawals $withdrawals, Deposits $deposits)
    {

        if(! $withdrawals->isBank())
            throw new \Exception("移動元の口座種別が不正です");

        if(! $deposits->isBank())
            throw new \Exception("移動先の口座種別が不正です");

        $this->transactionId = $transactionId;
        $this->date = $date;
        $this->transactionAmount = $transactionAmount;
        $this->withdrawals = $withdrawals;
        $this->deposits = $deposits;
    }

    /**
     * @return mixed|void
     */
    public function updateBalance()
    {
        $this->withdrawals->updateBalance($this->transactionAmount);
        $this->deposits->updateBalance($this->transactionAmount);
    }

    public function notify(NotificationTransaction $modelBuilder){

        $modelBuilder->transactionId($this->transactionId);
        $modelBuilder->transactionDate($this->date);
        $modelBuilder->transactionClass(Transaction::CLASSIFICATION_ACCOUNT_TRANSFER);

        $this->transactionAmount->notify($modelBuilder);
        $this->withdrawals->notify($this->transactionId,$modelBuilder);
        $this->deposits->notify($this->transactionId,$modelBuilder);

    }
}
