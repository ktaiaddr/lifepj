<?php


namespace App\Domain\Object\Deposit;

/**
 * 出納データ
 * Class Treasurer
 * @package App\Domain\Object\Deposit
 */
class Treasurer
{
    /** @var int 金額 */
    private int $amount;
    /** @var int タイプ 1:入金 2:出金 */
    private int $type;

    private string $memo;
}
