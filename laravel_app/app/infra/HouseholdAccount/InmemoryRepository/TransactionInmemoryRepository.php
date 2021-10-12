<?php

namespace App\infra\HouseholdAccount\InmemoryRepository;

use App\Domain\HouseholdAccount\Model\DepositsAndWithdrawals\Account;
use App\Domain\HouseholdAccount\Model\Transaction\Transaction;
use App\infra\HouseholdAccount\EloquentRepository\ModelBuilder;
use Illuminate\Support\Facades\DB;

class TransactionInmemoryRepository implements \App\Domain\HouseholdAccount\repository\TransactionRepository
{

    /**
     * @var Transaction[]
     */
    private array $transactions = [];

    /**
     * @param string $transactionId
     * @param \DateTime $transactionDate
     * @param string $transactionContents
     * @param Transaction $transaction
     * @param Account[] $accounts
     * @return bool
     */
    function save(string $transactionId, \DateTime $transactionDate, string $transactionContents, Transaction $transaction, array $accounts):bool
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
//
//        $result = \App\Models\HouseholdAccount\Transaction::create([
//            'transaction_id' => $modelBuilder->transactionId,
//            'date'=> $modelBuilder->transactionDate->format('Y-m-d'),
//            'amount' => $modelBuilder->transactionAmount,
//            'contents' => $modelBuilder->transactionContents,
//            'type' => $modelBuilder->transactionType
//        ]);

        $eloqTransaction = new \App\Models\HouseholdAccount\Transaction();
        $eloqTransaction->transaction_id = $modelBuilder->transactionId;
        $eloqTransaction->date = $modelBuilder->transactionDate->format('Y-m-d');
        $eloqTransaction->amount = $modelBuilder->transactionAmount;
        $eloqTransaction->contents = $modelBuilder->transactionContents;
        $eloqTransaction->type = $modelBuilder->transactionType;

        $eloqTransaction->save();
        DB::commit();

        return true;

    }


}
