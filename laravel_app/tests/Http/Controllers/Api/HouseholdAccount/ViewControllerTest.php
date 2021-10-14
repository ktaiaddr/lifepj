<?php

namespace Tests\Http\Controllers\Api\HouseholdAccount;

use App\Application\HouseholdAccount\service\TransactionViewService;
use App\Domain\HouseholdAccount\Model\Transaction\Transaction;
use App\Models\HouseholdAccount\EloquentAccountBalance;
use App\Models\HouseholdAccount\EloquentAccout;
use App\Models\HouseholdAccount\EloquentTransaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
//use PHPUnit\Framework\TestCase;
use Tests\TestCase;

class ViewControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        $userId = 1;
        $transaction_id = (string)Str::orderedUuid();
        $transaction1 = EloquentTransaction::create([
            'transaction_id' => $transaction_id,
            'user_id' => $userId,
            'date' => (new \DateTime())->format('Y-m-d'),
            'amount' => 100,
            'contents' => '',
            'type' => Transaction::CLASSIFICATION_ACCOUNT_TRANSFER
        ]);

        EloquentAccountBalance::create([
            'transaction_id' => $transaction_id,
            'account_id' => 1,
            'balance' => 100000,
        ]);

        EloquentAccountBalance::create([
            'transaction_id' => $transaction_id,
            'account_id' => 2,
            'balance' => 100000,
        ]);

        EloquentAccout::create([
            'account_id'=>1,
            'user_id'=>$userId,
            'type'=>1,
            'name'=>"hoge",
        ]);
        EloquentAccout::create([
            'account_id'=>2,
            'user_id'=>$userId,
            'type'=>1,
            'name'=>"hoge",
        ]);




        $transaction_id = (string)Str::orderedUuid();
        $transaction1 = EloquentTransaction::create([
            'transaction_id' => $transaction_id,
            'user_id' => $userId,
            'date' => (new \DateTime())->format('Y-m-d'),
            'amount' => 100,
            'contents' => '',
            'type' => Transaction::CLASSIFICATION_ACCOUNT_TRANSFER
        ]);

        EloquentAccountBalance::create([
            'transaction_id' => $transaction_id,
            'account_id' => 1,
            'balance' => 200000,
        ]);

        EloquentAccountBalance::create([
            'transaction_id' => $transaction_id,
            'account_id' => 2,
            'balance' => 200000,
        ]);

    }

    public function dataProvider(){

        $ajax_header = ['X-Requested-With' => 'XMLHttpRequest'];
        $empty_header = [];

        return [
            "成功パターン" => [$status=200,$ajax_header],
        ];
    }

    /**
     * @dataProvider dataProvider
     */
    public function test_viewController($status,$header){

        $user = new User();
        $user->id = 1;
        $response = $this->actingAs($user)->json('GET','/api/household_account',[
        ],$header);

        $response->assertStatus($status);

        $response->dump();

    }
}