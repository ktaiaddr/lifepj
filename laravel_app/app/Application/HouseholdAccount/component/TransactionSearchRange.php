<?php

namespace App\Application\HouseholdAccount\component;

/**
 * 家計簿ページの月選択の範囲
 */
class TransactionSearchRange
{
    /**
     * 選択可能開始月
     * @var string|null
     */
    public ?string $minMonth;
    /**
     * 選択可能終了月
     * @var string|null
     */
    public ?string $maxMonth;
}
