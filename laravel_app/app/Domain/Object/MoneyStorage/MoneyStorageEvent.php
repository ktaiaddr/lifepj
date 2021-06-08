<?php


namespace App\Domain\Object\MoneyStorage;

use App\Domain\Object\Entity;

class MoneyStorageEvent extends Entity
{
    /** @var string  */
    const KEY = 'eventId';

    /** @var ?int キー */
    protected ?int $eventId;

    /** @var MoneyStorageId 対象ストレージのID */
    private MoneyStorageId $storageId;

    /** @var MoneyStorageEventType 出し入れの種別 */
    private MoneyStorageEventType $type;

    /** @var MoneyStorageEventAmount 出し入れした金額 */
    private MoneyStorageEventAmount $amount;

    /** @var MoneyStorageEventMemo メモ */
    private MoneyStorageEventMemo $memo;

    /**
     * MoneyStorageEvent constructor.
     * @param int|null $eventId
     * @param MoneyStorageId $storageId
     * @param MoneyStorageEventType $type
     * @param MoneyStorageEventAmount $amount
     * @param MoneyStorageEventMemo $memo
     */
    public function __construct(?int $eventId, MoneyStorageId $storageId, MoneyStorageEventType $type, MoneyStorageEventAmount $amount, MoneyStorageEventMemo $memo)
    {
        $this->eventId = $eventId;
        $this->storageId = $storageId;
        $this->type = $type;
        $this->amount = $amount;
        $this->memo = $memo;
    }

    /**
     * @return int|null
     */
    public function getEventId(): ?int
    {
        return $this->eventId;
    }
}
