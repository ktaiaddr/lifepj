<?php


namespace App\Domain\Object\FuelEconomy;


interface IRefuelingNotification
{
    function refuelingId(int $refuelingId):void;
    function fuelEconomy(FuelEconomy $fuelEconomy):void;
    function gasStation(string $gasStation):void;
    function memo(string $memo):void;
}
