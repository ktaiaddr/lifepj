<?php

namespace App\infra\HouseholdAccount\EloquentRepository;

use App\Domain\HouseholdAccount\Model\DepositsAndWithdrawals\Account;
use App\Domain\HouseholdAccount\Model\Transaction\Transaction;
use App\Models\HouseholdAccount\EloquentAccountBalance;
use Illuminate\Support\Facades\DB;

class EloquentTransactionRepository implements \App\Domain\HouseholdAccount\repository\TransactionRepository
{
    /**
     * @param string $transactionId
     * @param \DateTime $transactionDate
     * @param string $transactionContents
     * @param Transaction $transaction
     * @param array $accounts
     * @param int $user_id
     * @return bool
     */
    function save(string $transactionId, \DateTime $transactionDate, string $transactionContents, Transaction $transaction, array $accounts, int $user_id):bool
    {
        $modelBuilder = new ModelBuilder();
        $modelBuilder->transactionId($transactionId);
        $modelBuilder->transactionDate($transactionDate);
        $modelBuilder->transactionContents($transactionContents);
        $transaction->notify($modelBuilder);
        for($i=0;$i<count($accounts);$i++){
            $accounts[$i]->notify($transactionId,$modelBuilder);
        }

        DB::beginTransaction();

        $result = \App\Models\HouseholdAccount\Transaction::create([
            'transaction_id' => $modelBuilder->transactionId,
            'user_id' => $user_id,
            'date'=> $modelBuilder->transactionDate->format('Y-m-d'),
            'amount' => $modelBuilder->transactionAmount,
            'contents' => $modelBuilder->transactionContents,
            'type' => $modelBuilder->transactionType
        ]);

        for($i=0; $i < count($modelBuilder->balances) ; $i++){

            $balance = $modelBuilder->balances[$i];

            EloquentAccountBalance::create([
                'transaction_id' => $balance->transactionId,
                'account_id' => $balance->accountId,
                'balance' => $balance->balance,
            ]);
        }

        DB::commit();
//exit;
        return true;

    }


}
