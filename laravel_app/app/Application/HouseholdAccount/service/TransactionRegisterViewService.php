<?php

namespace App\Application\HouseholdAccount\service;

use App\Application\HouseholdAccount\query\AccountBalanceQuery;
use App\Domain\HouseholdAccount\Model\Transaction\TransactionType;
use App\Domain\HouseholdAccount\Model\Transaction\TransactionTypeDefine;

class TransactionRegisterViewService
{

    private AccountBalanceQuery $accountBalanceQuery;

    /**
     * @param AccountBalanceQuery $accountBalanceQuery
     */
    public function __construct(AccountBalanceQuery $accountBalanceQuery)
    {
        $this->accountBalanceQuery = $accountBalanceQuery;
    }

    /**
     * @return RegisterPageComponents
     */
    public function getComponents(int $user_id){

        try{
            $accountBalanceSelectModels = $this->accountBalanceQuery->findByUser($user_id);

            $existHandMoneyAccountNum = 0;
            $existBankAccountNum = 0;
            foreach($accountBalanceSelectModels as $accountBalanceSelectModel){
                if($accountBalanceSelectModel->isHandMoney()) $existHandMoneyAccountNum++;
                if($accountBalanceSelectModel->isBank()) $existBankAccountNum++;
            }

            $transactionTypeDefines = TransactionType::getTypeDefines();
            $transactionTypeDefinesFilterd = array_values(
                array_filter($transactionTypeDefines,function (TransactionTypeDefine $transactionTypeDefine) use($existHandMoneyAccountNum,$existBankAccountNum) {
                    $transactionType = new TransactionType($transactionTypeDefine->typeValue);
                    if($transactionType->isAccountTransfer()) return $existBankAccountNum >=2;//口座転送するなら銀行口座が2つ以上必要
                    if($transactionType->isCashAddition()) return $existHandMoneyAccountNum >=1;//現金加算するならハンドマネーが必要
                    if($transactionType->isCashPayment()) return $existHandMoneyAccountNum >=1;//現金払いするならハンドマネーが必要
                    if($transactionType->isDirectDevit()) return $existBankAccountNum >=1;//口座引き落としするなら銀行口座が必要
                    if($transactionType->isMoneyReceived()) return $existBankAccountNum >=1;//入金するなら銀行口座が必要
                    if($transactionType->isWithdrawalDeposit()) return $existHandMoneyAccountNum >=1 && $existBankAccountNum >=1;//引き出しするなら銀行、ハンドマネーが必要
                }));

            $registerPageComponents = new RegisterPageComponents($transactionTypeDefinesFilterd,$accountBalanceSelectModels);

        }
        catch(\Exception $e){

        }

        return $registerPageComponents;
    }

}
