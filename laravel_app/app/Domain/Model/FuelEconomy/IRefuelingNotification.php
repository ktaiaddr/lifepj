<?php


namespace App\Domain\Model\FuelEconomy;


interface IRefuelingNotification
{
    function refuelingId(int $refuelingId):void;
    function userId(int $userId):void;
    function date(\DateTime $date): void;
    function fuelEconomy(FuelEconomy $fuelEconomy):void;
    function gasStation(string $gasStation):void;
    function memo(string $memo):void;
}
