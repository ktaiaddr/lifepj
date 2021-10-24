<?php

namespace App\infra\HouseholdAccount\mysqlquery;

use App\Application\HouseholdAccount\QueryModel\AccountBalanceViewModel;
use App\Application\HouseholdAccount\QueryModel\TransactionViewModel;
use App\Domain\HouseholdAccount\Model\Transaction\SearchCommand;
use App\Domain\HouseholdAccount\Model\Transaction\TransactionType;
use Illuminate\Support\Facades\DB;

class MysqlTransactionViewQuery implements \App\Application\HouseholdAccount\query\TransactionViewQuery
{

    /**
     * @inheritDoc
     */
    public function find(SearchCommand $searchCommand,string $userId): array
    {
        try{

            /** @var  $pdo \PDO */
            $pdo = DB::getPdo();

            $pdo->query(<<<QUERY
CREATE TEMPORARY TABLE IF NOT EXISTS TEMPORARY_LATEST_CLOSING (
account_id int,
closing_next_month_day_of_first date,
balance int
);
QUERY);

            $pdo->query("DELETE FROM TEMPORARY_LATEST_CLOSING;");

            $stmt = $pdo->prepare( <<<QUERY
INSERT INTO TEMPORARY_LATEST_CLOSING
SELECT
  account_id,
--   DATE_ADD( concat(substr(month,1,4),'-',substr(month,5,2),'-01') , INTERVAL 1 MONTH ) as closing_next_month_day_of_first,
  DATE_ADD( str_to_date(concat(month,'01'), '%Y%m%d') , INTERVAL 1 MONTH ) as closing_next_month_day_of_first,
  balance
FROM lifepj.DAT_ACCOUNT_MONTH_CLOSING
WHERE (account_id,month) IN
(SELECT account_id, MAX(month)
FROM lifepj.DAT_ACCOUNT_MONTH_CLOSING
WHERE lifepj.DAT_ACCOUNT_MONTH_CLOSING.month < :target_month_yyyymm
GROUP BY account_id)
QUERY
);
            $stmt->bindValue( ':target_month_yyyymm', str_replace('-','',$searchCommand->viewMonth), \PDO::PARAM_INT );
            $stmt->execute();

            $wheres = [];
            $values = [];

            $values[] = [':target_month_day_of_first', $searchCommand->viewMonth.'-01', \PDO::PARAM_INT];
            $values[] = [':target_month_day_of_first2', $searchCommand->viewMonth.'-01', \PDO::PARAM_INT];

            // ユーザID
            $wheres[] = ' BASE_TRANSACTION.user_id = :user_id ';
            $values[] = [':user_id', $userId, \PDO::PARAM_INT];

            if($searchCommand->transactionTypeVal){
                $wheres[] = ' BASE_TRANSACTION.type = :transaction_type ';
                $values[] = [':transaction_type', $searchCommand->transactionTypeVal, \PDO::PARAM_INT];
            }

            if($searchCommand->accountId > 0){
                $wheres[] = ' BASE_ACCOUNT.account_id = :account_id ';
                $values[] = [':account_id', $searchCommand->accountId, \PDO::PARAM_INT];
            }

            $implode = 'implode';

            $query = <<<QUERY

        select BASE_TRANSACTION.`transaction_id`,
               BASE_TRANSACTION.`date`,
               BASE_TRANSACTION.`amount`,
               BASE_TRANSACTION.`contents`,
               BASE_TRANSACTION.`type`  as transaction_type,
               BASE_ACCOUNT.`account_id`,
               BASE_ACCOUNT.increase_decrease_type,
               (
                   SELECT
                          sum( case SUB_ACCOUNT.increase_decrease_type
                              when 1 then amount * -1 -- 減少タイプならマイナス
                              when 2 then amount      -- 加算タイプならプラス
                              else 0 end )
                              + ifnull(TEMPORARY_LATEST_CLOSING.balance,0)
                   FROM
                        DAT_HOUSEHOLD_ACCOUNT_TRANSACTION as SUB_TRANSACTION,
                        DAT_HOUSEHOLD_ACCOUNT_ACCOUNT as SUB_ACCOUNT
                   WHERE SUB_TRANSACTION.transaction_id = SUB_ACCOUNT.transaction_id
                     AND SUB_ACCOUNT.account_id = BASE_ACCOUNT.account_id
                         -- 開始縛り、月締データが無ければ開始日縛り無しで計算、月締データがあれば締月の翌月以降の取引データのみを計算
                     AND IF( TEMPORARY_LATEST_CLOSING.account_id IS NULL, TRUE, SUB_TRANSACTION.date >= TEMPORARY_LATEST_CLOSING.closing_next_month_day_of_first )
                         -- 終了縛り、取引日時までの取引データを合算させる
                     AND concat(SUB_TRANSACTION.date,' ',TIME_FORMAT(SUB_TRANSACTION.created_at,'%H:%i:%s')) <= concat (BASE_TRANSACTION.date,' ',TIME_FORMAT(BASE_TRANSACTION.created_at,'%H:%i:%s'))
                   )
                   AS balance,
               ACCOUNT.`name`,
               ACCOUNT.`type`  as account_type

        FROM
             DAT_HOUSEHOLD_ACCOUNT_TRANSACTION AS BASE_TRANSACTION

                 LEFT JOIN DAT_HOUSEHOLD_ACCOUNT_ACCOUNT as BASE_ACCOUNT
                     ON BASE_TRANSACTION.transaction_id = BASE_ACCOUNT.transaction_id

                 LEFT JOIN MST_ACCOUNT as ACCOUNT
                     ON BASE_ACCOUNT.account_id = ACCOUNT.account_id

                 LEFT JOIN TEMPORARY_LATEST_CLOSING
                     ON TEMPORARY_LATEST_CLOSING.account_id = ACCOUNT.account_id

        WHERE  {$implode(' and ', $wheres)}
        AND BASE_TRANSACTION.date >= :target_month_day_of_first
        AND BASE_TRANSACTION.date < DATE_ADD(:target_month_day_of_first2, INTERVAL 1 MONTH)

ORDER BY
         BASE_TRANSACTION.date ASC,
         TIME_FORMAT(BASE_TRANSACTION.created_at,'%H:%i:%s') ASC

QUERY;

            $stmt = $pdo->prepare( $query );

            foreach( $values as $value ) $stmt->bindParam( ...$value );

//            $stmt->bindParam(':user_id', $userId);

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
