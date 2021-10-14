<?php

namespace App\Application\HouseholdAccount\QueryModel;

class TransactionViewModel
{
    /**
     * @var string 取引ID
     */
    public string $transactionId;
    /**
     * @var \Datetime 取引日
     */
    public \Datetime $date;
    /**
     * @var int 取引金額
     */
    public int $amount;
    /**
     * @var string 取引内容
     */
    public string $contents;
    /**
     * @var int 取引タイプ
     */
    public int $type;
    /**
     * @var AccountBalanceViewModel[] 残高リスト
     */
    public array $balances;

    /**
     * @param string $transactionId
     * @param \Datetime $date
     * @param int $amount
     * @param string $contents
     * @param int $type
     * @param AccountBalanceViewModel[] $balances
     */
    public function __construct(string $transactionId, \Datetime $date, int $amount, string $contents, int $type, array $balances)
    {
        $this->transactionId = $transactionId;
        $this->date = $date;
        $this->amount = $amount;
        $this->contents = $contents;
        $this->type = $type;
        $this->balances = $balances;
    }

    /**
     * @param AccountBalanceViewModel[] $balances
     */
    public function addBalanceRecreate(array $balances){
        return new TransactionViewModel($this->transactionId,$this->date,$this->amount,$this->contents,$this->type,$balances);
    }

}
