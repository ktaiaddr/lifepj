<?php

namespace App\Application\HouseholdAccount\QueryModel;

class TransactionViewModel
{
    /**
     * @var string 取引ID
     */
    public string $transactionId;
    /**
     * @var string 取引日
     */
    public string $date;
    /**
     * @var int 取引金額
     */
    public int $amount;
    /**
     * @var string 取引内容
     */
    public string $contents;
    /**
     * @var string 取引タイプラベル
     */
    public string $typeLabel;
    /**
     * @var AccountBalanceViewModel[] 残高リスト
     */
    public array $balances;

    /**
     * @param string $transactionId
     * @param string $date
     * @param int $amount
     * @param string $contents
     * @param string $type
     * @param AccountBalanceViewModel[] $balances
     */
    public function __construct(string $transactionId, string $date, int $amount, string $contents, string $typeLabel, array $balances)
    {
        $this->transactionId = $transactionId;
        $this->date = $date;
        $this->amount = $amount;
        $this->contents = $contents;
        $this->typeLabel = $typeLabel;
        $this->balances = $balances;
    }

    /**
     * @param AccountBalanceViewModel[] $balances
     */
    public function addBalanceRecreate(array $balances){
        return new TransactionViewModel($this->transactionId,$this->date,$this->amount,$this->contents,$this->typeLabel,$balances);
    }

}
