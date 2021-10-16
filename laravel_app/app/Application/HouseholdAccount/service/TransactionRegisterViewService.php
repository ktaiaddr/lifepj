<?php

namespace App\Application\HouseholdAccount\service;

class TransactionRegisterViewService
{

    public function __construct()
    {
    }

    /**
     * @return RegisterPageComponents
     */
    public function getComponents(){
        $registerPageComponents = new RegisterPageComponents();

        return $registerPageComponents;
    }

}
