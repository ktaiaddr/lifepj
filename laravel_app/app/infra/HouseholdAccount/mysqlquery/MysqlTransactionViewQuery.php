<?php

namespace App\infra\HouseholdAccount\mysqlquery;

use App\Application\HouseholdAccount\QueryModel\AccountBalanceViewModel;
use App\Application\HouseholdAccount\QueryModel\TransactionViewModel;
use App\Domain\HouseholdAccount\Model\Account\Account;
use App\Domain\HouseholdAccount\Model\Account\AccountType;
use Illuminate\Support\Facades\DB;

class MysqlTransactionViewQuery implements \App\Application\HouseholdAccount\query\TransactionViewQuery
{

    /**
     * @inheritDoc
     */
    public function find(int $userId): array
    {
        try{

            /** @var  $pdo \PDO */
            $pdo = DB::getPdo();

            $query  = "    select TRANSACTION.`transaction_id`,                                            ";
            $query .= "           TRANSACTION.`date`,                                                  ";
            $query .= "           TRANSACTION.`amount`,                                                  ";
            $query .= "           TRANSACTION.`contents`,                                                  ";
            $query .= "           TRANSACTION.`type`  as transaction_type,                                                  ";
            $query .= "           BALANCE.`account_id`,                              ";
            $query .= "           BALANCE.`balance`    ,                          ";
            $query .= "           ACCOUNT.`name`        ,                      ";
            $query .= "           ACCOUNT.`type`  as account_type                           ";

            $query .= "      FROM DAT_HOUSEHOLD_ACCOUNT_TRANSACTION as TRANSACTION            ";
            $query .= " left join DAT_HOUSEHOLD_ACCOUNT_BALANCE as BALANCE               ";
            $query .= "        on TRANSACTION.transaction_id = BALANCE.transaction_id  ";
            $query .= " left join MST_ACCOUNT as ACCOUNT               ";
            $query .= "        on BALANCE.account_id = ACCOUNT.account_id  ";

            $query .= "     where TRANSACTION.user_id = :user_id                               ";
            $query .= "  order by TRANSACTION.created_at desc                      ";
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
                    new \DateTime($resultRow['date']),
                    $resultRow['amount'],
                    $resultRow['contents'],
                    $resultRow['transaction_type'],
                    []
                );

                $beforeTransactionId = $resultRow['transaction_id'];
            }

            $transactionViewModelList[] = $transactionViewModel->addBalanceRecreate( $accountBalanceViewModelList );

        }
        catch(\Exception $e){
            $transactionViewModelList = null;
        }
//var_dump($transactionViewModelList);
        return $transactionViewModelList;

    }
}
