<?php

namespace App\infra\HouseholdAccount\mysqlquery;

use App\Domain\HouseholdAccount\Model\Account\Account;
use App\Domain\HouseholdAccount\Model\Account\AccountType;
use App\Models\HouseholdAccount\EloquentAccountBalance;
use Illuminate\Support\Facades\DB;

class MysqlAccountBalanceQuery implements \App\Application\HouseholdAccount\query\AccountBalanceQuery
{

    public function find(int $accountId): Account|null
    {
        try{

            $query = <<<QUERY
                select MST_ACCOUNT.account_id,
                       MST_ACCOUNT.type,
                       DAT_HOUSEHOLD_ACCOUNT_BALANCE.balance
                  FROM MST_ACCOUNT left join DAT_HOUSEHOLD_ACCOUNT_BALANCE
                    on MST_ACCOUNT.account_id = DAT_HOUSEHOLD_ACCOUNT_BALANCE.account_id
                 where MST_ACCOUNT.account_id = :account_id
              order by DAT_HOUSEHOLD_ACCOUNT_BALANCE.created_at desc
                 limit 1
QUERY;

            /** @var  $pdo \PDO */
            $pdo = DB::getPdo();
            $stmt = $pdo->prepare( $query );
            $stmt->bindParam(':account_id', $accountId);

            if(! $stmt->execute()){

            };

            $result = $stmt->fetch(\PDO::FETCH_ASSOC );

        }
        catch(\Exception $e){

        }

        return new Account($result['account_id'], $result['balance'], new AccountType($result['type']) );
    }
}
