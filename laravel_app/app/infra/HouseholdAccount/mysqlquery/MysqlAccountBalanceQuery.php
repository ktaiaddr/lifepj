<?php

namespace App\infra\HouseholdAccount\mysqlquery;

use App\Application\HouseholdAccount\QueryModel\AccountBalanceSelectModel;
use App\Application\HouseholdAccount\QueryModel\AccountBalanceViewModel;
use App\Domain\HouseholdAccount\Model\Account\Account;
use App\Domain\HouseholdAccount\Model\Account\AccountType;
use App\Models\HouseholdAccount\EloquentAccountBalance;
use Illuminate\Support\Facades\DB;

class MysqlAccountBalanceQuery implements \App\Application\HouseholdAccount\query\AccountBalanceQuery
{

    public function find(int $accountId,int $user_id,int $increase_reduce_type): Account|null
    {
        try{

            /** @var  $pdo \PDO */
            $pdo = DB::getPdo();

            $query  = "    select MST_ACCOUNT.account_id,                                            ";
            $query .= "           MST_ACCOUNT.type                                                  ";
//            $query .= "           DAT_HOUSEHOLD_ACCOUNT_ACCOUNT.increase_decrease_type                              ";
            $query .= "      FROM MST_ACCOUNT                ";
//            $query .= "      FROM MST_ACCOUNT left join DAT_HOUSEHOLD_ACCOUNT_ACCOUNT                ";
//            $query .= "        on MST_ACCOUNT.account_id = DAT_HOUSEHOLD_ACCOUNT_ACCOUNT.account_id  ";
            $query .= "     where MST_ACCOUNT.account_id = :account_id                               ";
            $query .= "       and MST_ACCOUNT.user_id = :user_id                                     ";
//            $query .= "  order by DAT_HOUSEHOLD_ACCOUNT_ACCOUNT.created_at desc                      ";
//            $query .= "     limit 1                                                                  ";

            $stmt = $pdo->prepare( $query );
            $stmt->bindParam(':account_id', $accountId);
            $stmt->bindParam(':user_id', $user_id);

            if(! $stmt->execute()){
                throw new \Exception();
            };

            $result = $stmt->fetch(\PDO::FETCH_ASSOC );

//            $account = new Account($result['account_id'], ($result['increase_decrease_type'] ?:0) , new AccountType($result['type']) );
            $account = new Account($result['account_id'], $increase_reduce_type , new AccountType($result['type']) );

        }
        catch(\Exception $e){
            $account = null;
        }

        return $account;

    }

    /**
     * @param int $user_id
     * @return AccountBalanceSelectModel[]
     */
    public function findByUser(int $user_id): array{

        try{

            /** @var  $pdo \PDO */
            $pdo = DB::getPdo();

            $query  = "    select MST_ACCOUNT.account_id,                                            ";
            $query .= "           MST_ACCOUNT.type,                                                  ";
            $query .= "           MST_ACCOUNT.name                              ";
            $query .= "      FROM MST_ACCOUNT";
            $query .= "     where MST_ACCOUNT.user_id = :user_id                              ";

            $stmt = $pdo->prepare( $query );
            $stmt->bindParam(':user_id', $user_id);

            if(! $stmt->execute()){
                throw new \Exception();
            };

            $resultAll = $stmt->fetchAll(\PDO::FETCH_ASSOC );

            $accounts = [];
            foreach($resultAll as $result){
                $accounts[] = new AccountBalanceSelectModel($result['account_id'], new AccountType($result['type']) , $result['name'] );
            }

        }
        catch(\Exception $e){
            $accounts = [];
        }

        return $accounts;


    }

}
