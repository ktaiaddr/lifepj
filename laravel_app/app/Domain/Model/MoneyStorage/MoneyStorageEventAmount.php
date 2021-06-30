<?php


namespace App\Domain\Model\MoneyStorage;


class MoneyStorageEventAmount
{
    private int $value;

    /**
     * MoneyStorageId constructor.
     * @param int $value
     * @throws \Exception
     */
    public function __construct(int $value)
    {
        if ($value < 1) {
            throw new \Exception('金額は0以上数値です');
        }
        $this->value = $value;
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

}
