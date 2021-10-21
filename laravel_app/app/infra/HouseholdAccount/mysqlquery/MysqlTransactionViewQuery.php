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

            $query  = "    select BASE_TRANSACTION.`transaction_id`,                                            ";
            $query .= "           BASE_TRANSACTION.`date`,                                                  ";
            $query .= "           BASE_TRANSACTION.`amount`,                                                  ";
            $query .= "           BASE_TRANSACTION.`contents`,                                                  ";
            $query .= "           BASE_TRANSACTION.`type`  as transaction_type,                                                  ";
            $query .= "           BASE_ACCOUNT.`account_id`,                              ";
//            $query .= "           BASE_ACCOUNT.`increase_decrease_type`    ,                          ";
            $query .= "           BASE_ACCOUNT.increase_decrease_type," ;

            $query .= "                       (select";
            $query .= "           sum(case";
            $query .= "           DAT_HOUSEHOLD_ACCOUNT_ACCOUNT.increase_decrease_type";
            $query .= "            when 1 then amount * -1";
            $query .= "            when 2 then amount";
            $query .= "            else 0 end)";
            $query .= "            from DAT_HOUSEHOLD_ACCOUNT_TRANSACTION, DAT_HOUSEHOLD_ACCOUNT_ACCOUNT";
            $query .= "           where DAT_HOUSEHOLD_ACCOUNT_TRANSACTION.transaction_id = DAT_HOUSEHOLD_ACCOUNT_ACCOUNT.transaction_id";
            $query .= "                       and DAT_HOUSEHOLD_ACCOUNT_ACCOUNT.account_id = BASE_ACCOUNT.account_id";
            $query .= "                       and  concat (DAT_HOUSEHOLD_ACCOUNT_TRANSACTION.date,' ',TIME_FORMAT(DAT_HOUSEHOLD_ACCOUNT_TRANSACTION.created_at,'%H:%i:%s')) <= concat (BASE_TRANSACTION.date,' ',TIME_FORMAT(BASE_TRANSACTION.created_at,'%H:%i:%s'))";
            $query .= "           ) as balance ," ;

            $query .= "           ACCOUNT.`name`        ,                      ";
            $query .= "           ACCOUNT.`type`  as account_type                           ";

            $query .= "      FROM DAT_HOUSEHOLD_ACCOUNT_TRANSACTION as BASE_TRANSACTION           ";
            $query .= " left join DAT_HOUSEHOLD_ACCOUNT_ACCOUNT as BASE_ACCOUNT               ";
            $query .= "        on BASE_TRANSACTION.transaction_id = BASE_ACCOUNT.transaction_id  ";
            $query .= " left join MST_ACCOUNT as ACCOUNT               ";
            $query .= "        on BASE_ACCOUNT.account_id = ACCOUNT.account_id  ";

            $query .= "     where BASE_TRANSACTION.user_id = :user_id                               ";
            $query .= "  order by BASE_TRANSACTION.date asc      ,BASE_TRANSACTION.created_at asc                 ";
//            $query .= "     limit 10                     ";

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

                $accountBalanceViewModelList[] = new AccountBalanceViewModel($resultRow['account_id'],$resultRow['balance'],$resultRow['name'],$resultRow['increase_decrease_type']);

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

            if($transactionViewModel)
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
