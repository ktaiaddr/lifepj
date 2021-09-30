<?php

namespace App\Domain\HouseholdAccount\Model\Notification;

interface NotificationTransaction
{
    public function transactionId( string $transactionId ):void;

    public function transactionDate( \Datetime $transactionDate ):void;

    public function transactionAmount( int $transactionAmount ):void;

    public function transactionClass( int $transactionClass ):void;

    public function addBalance(Balance $balance):void;
}
