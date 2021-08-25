<?php


namespace App\Domain\Model\FuelEconomy;

//通知用データ
interface IRefuelingNotification
{
    function refuelingId       (int $refuelingId)         :void;
    function userId            (int $userId)              :void;
    function date              (\DateTime $date)          :void;
    function refuelingAmount   (float $refuelingAmount)   :void;
    function refuelingDistance (float $refuelingDistance) :void;
    function gasStation        (?string $gasStation)       :void;
    function memo              (?string $memo)             :void;
    function delFlg            (?int $delFlg)             :void;
}
