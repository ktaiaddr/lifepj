<?php


namespace App\Domain\Object\MoneyStorage;

use App\Domain\Object\Entity;

class MoneyStorageEvent extends Entity
{
    /** @var string  */
    const KEY = 'eventId';

    /** @var ?int キー */
    private ?int $eventId;

    /** @var MoneyStorageId 対象ストレージのID */
    private MoneyStorageId $storageId;

    /** @var MoneyStorageEventType 出し入れの種別 */
    private MoneyStorageEventType $type;

    /** @var MoneyStorageAmount 出し入れした金額 */
    private MoneyStorageAmount $amount;

    /** @var MoneyStorageEnventMemo メモ */
    private MoneyStorageEnventMemo $memo;

    /**
     * MoneyStorageEvent constructor.
     * @param int|null $eventId
     * @param MoneyStorageId $storageId
     * @param MoneyStorageEventType $type
     * @param MoneyStorageAmount $amount
     * @param MoneyStorageEnventMemo $memo
     */
    public function __construct(?int $eventId, MoneyStorageId $storageId, MoneyStorageEventType $type, MoneyStorageAmount $amount, MoneyStorageEnventMemo $memo)
    {
        $this->eventId = $eventId;
        $this->storageId = $storageId;
        $this->type = $type;
        $this->amount = $amount;
        $this->memo = $memo;
    }


}
