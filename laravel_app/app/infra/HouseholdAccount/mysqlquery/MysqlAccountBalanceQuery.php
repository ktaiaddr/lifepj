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

            /** @var  $pdo \PDO */
            $pdo = DB::getPdo();

            $query  = "    select MST_ACCOUNT.account_id,                                            ";
            $query .= "           MST_ACCOUNT.type,                                                  ";
            $query .= "           DAT_HOUSEHOLD_ACCOUNT_BALANCE.balance                              ";
            $query .= "      FROM MST_ACCOUNT left join DAT_HOUSEHOLD_ACCOUNT_BALANCE                ";
            $query .= "        on MST_ACCOUNT.account_id = DAT_HOUSEHOLD_ACCOUNT_BALANCE.account_id  ";
            $query .= "     where MST_ACCOUNT.account_id = :account_id                               ";
            $query .= "  order by DAT_HOUSEHOLD_ACCOUNT_BALANCE.created_at desc                      ";
            $query .= "     limit 1                                                                  ";

            $stmt = $pdo->prepare( $query );
            $stmt->bindParam(':account_id', $accountId);

            if(! $stmt->execute()){
                throw new \Exception();
            };

            $result = $stmt->fetch(\PDO::FETCH_ASSOC );

            $account = new Account($result['account_id'], ($result['balance'] ?:0) , new AccountType($result['type']) );

        }
        catch(\Exception $e){
            $account = null;
        }

        return $account;

    }
}
