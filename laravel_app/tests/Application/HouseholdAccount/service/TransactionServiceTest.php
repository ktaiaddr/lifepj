<?php

namespace Tests\Application\HouseholdAccount\service;

use App\Application\HouseholdAccount\query\AccountQuery;
use App\Application\HouseholdAccount\service\TransactionService;
use App\Domain\HouseholdAccount\Model\Transaction\Transaction;
use App\Domain\HouseholdAccount\repository\TransactionRepository;
use App\infra\HouseholdAccount\inmemoryQuery\AccountInmemoryQuery;
use App\infra\HouseholdAccount\InmemoryRepository\TransactionInmemoryRepository;

//use PHPUnit\Framework\TestCase;
use Tests\TestCase;

class TransactionServiceTest extends TestCase
{

    private TransactionService $transactionService;
//    protected function setUp(): void
//    {
//        parent::setUp(); // TODO: Change the autogenerated stub
////        $pdo = DB::getPdo();
////        $stmt = $pdo->prepare('truncate refuelings');
////        $stmt->execute();
////        app()->bind(AccountQuery::class,AccountInmemoryQuery::class);
////        app()->singleton(TransactionRepository::class,TransactionInmemoryRepository::class);
//        $this->transactionService = app()->make(TransactionService::class);;
//    }



//    protected function tearDown(): void
//    {
//        parent::tearDown(); // TODO: Change the autogenerated stub
//    }


    /**
     * @test
     */
    public function test_登録(){
        $this->transactionService = app()->make(TransactionService::class);;

        $this->transactionService->do(100,Transaction::CLASSIFICATION_ACCOUNT_TRANSFER,1,2,"amema");
    }
}
