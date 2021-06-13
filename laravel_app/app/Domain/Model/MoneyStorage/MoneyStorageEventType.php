<?php


namespace App\Domain\Model\MoneyStorage;

class MoneyStorageEventType
{
    const IN  = 1;
    const OUT = 2;

    /** @var int IN:入金 OUT:出金 */
    private int $type;
    /**
     * MoneyStorageEventType constructor.
     */
    private function __construct(int $type)
    {
        $this->type = $type;
    }
    /**
     * @return MoneyStorageEventType
     */
    public static function in():MoneyStorageEventType
    {
        return new self(self::IN);
    }
    /**
     * @return MoneyStorageEventType
     */
    public static function out():MoneyStorageEventType
    {
        return new self(self::OUT);
    }

    public function isIn():bool
    {
        return $this->type === MoneyStorageEventType::IN;
    }
    public function isOut():bool
    {
        return $this->type === MoneyStorageEventType::OUT;
    }
}
