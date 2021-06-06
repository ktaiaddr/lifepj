<?php


namespace App\Domain\Object\MoneyStorage;


class MoneyStorageEnventMemo
{

    private string $value;

    /**
     * MoneyStorageEnventMemo constructor.
     * @param string $value
     */
    public function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->getValue();
    }


}
