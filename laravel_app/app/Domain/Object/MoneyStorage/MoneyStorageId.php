<?php


namespace App\Domain\Object\MoneyStorage;


class MoneyStorageId
{
    /** @var int  */
    private int $value;

    /**
     * MoneyStorageId constructor.
     * @param ?int $value
     * @throws \Exception
     */
    public function __construct(?int $value)
    {
        if ($value === null) {
            throw new \Exception('ストレージIDは数値です');
        }
        if ($value < 1) {
            throw new \Exception('ストレージIDは１以上です');
        }
        $this->value = $value;
    }


}

