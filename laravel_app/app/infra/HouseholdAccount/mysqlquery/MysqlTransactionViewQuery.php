<?php

namespace App\infra\HouseholdAccount\mysqlquery;

use App\Application\HouseholdAccount\QueryModel\AccountBalanceViewModel;
use App\Application\HouseholdAccount\QueryModel\TransactionViewModel;
use App\Domain\HouseholdAccount\Model\Account\Account;
use App\Domain\HouseholdAccount\Model\Account\AccountType;
use App\Domain\HouseholdAccount\Model\Transaction\Transaction;
use App\Domain\HouseholdAccount\Model\Transaction\TransactionType;
use Illuminate\Support\Facades\DB;

class MysqlTransactionViewQuery implements \App\Application\HouseholdAccount\query\TransactionViewQuery
{

    /**
     * @inheritDoc
     */
    public function find(string $userId): array
    {
        try{

            /** @var  $pdo \PDO */
            $pdo = DB::getPdo();

            $query  = "    select DAT_HOUSEHOLD_ACCOUNT_TRANSACTION.`transaction_id`,                                            ";
            $query .= "           DAT_HOUSEHOLD_ACCOUNT_TRANSACTION.`date`,                                                  ";
            $query .= "           DAT_HOUSEHOLD_ACCOUNT_TRANSACTION.`amount`,                                                  ";
            $query .= "           DAT_HOUSEHOLD_ACCOUNT_TRANSACTION.`contents`,                                                  ";
            $query .= "           DAT_HOUSEHOLD_ACCOUNT_TRANSACTION.`type`  as transaction_type,                                                  ";
            $query .= "           BALANCE.`account_id`,                              ";
            $query .= "           BALANCE.`balance`    ,                          ";
            $query .= "           ACCOUNT.`name`        ,                      ";
            $query .= "           ACCOUNT.`type`  as account_type                           ";

            $query .= "      FROM DAT_HOUSEHOLD_ACCOUNT_TRANSACTION            ";
            $query .= " left join DAT_HOUSEHOLD_ACCOUNT_BALANCE as BALANCE               ";
            $query .= "        on DAT_HOUSEHOLD_ACCOUNT_TRANSACTION.transaction_id = BALANCE.transaction_id  ";
            $query .= " left join MST_ACCOUNT as ACCOUNT               ";
            $query .= "        on BALANCE.account_id = ACCOUNT.account_id  ";

            $query .= "     where DAT_HOUSEHOLD_ACCOUNT_TRANSACTION.user_id = :user_id                               ";
            $query .= "  order by DAT_HOUSEHOLD_ACCOUNT_TRANSACTION.created_at desc                      ";
            $query .= "     limit 10                     ";

            $stmt = $pdo->prepare( $query );
            $stmt->bindParam(':user_id', $userId);

            if(! $stmt->execute()){
                throw new \Exception();
            };

            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC );
//var_dump($result);
            /** @var TransactionViewModel[] $transactionViewModelList */
            $transactionViewModelList = [];

            /** @var TransactionViewModel $transactionViewModel */
            $transactionViewModel = null;

            /** @var AccountBalanceViewModel[] $accountBalanceViewModelList */
            $accountBalanceViewModelList = [];

            $beforeTransactionId = null;

            foreach( $result as $resultRow ){

                if($beforeTransactionId && $transactionViewModel && $beforeTransactionId != $resultRow['transaction_id']){
                    $transactionViewModelList[] = $transactionViewModel->addBalanceRecreate( $accountBalanceViewModelList );
                    $accountBalanceViewModelList = [];
                }

                $accountBalanceViewModelList[] = new AccountBalanceViewModel($resultRow['account_id'],$resultRow['balance'],$resultRow['name']);

                // 残高無しで生成
                $transactionViewModel = new TransactionViewModel(
                    $resultRow['transaction_id'],
                    $resultRow['date'],
                    $resultRow['amount'],
                    $resultRow['contents'],
                    (new TransactionType($resultRow['transaction_type'] ))->getLabel(),
                    []
                );

                $beforeTransactionId = $resultRow['transaction_id'];
            }

            $transactionViewModelList[] = $transactionViewModel->addBalanceRecreate( $accountBalanceViewModelList );

        }
        catch(\Exception $e){
            var_dump($e->getMessage());
            $transactionViewModelList = [];
        }
//var_dump($transactionViewModelList);
        return $transactionViewModelList;

    }
}
