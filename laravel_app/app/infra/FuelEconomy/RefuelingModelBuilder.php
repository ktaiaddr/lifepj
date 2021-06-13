<?php


namespace App\infra\FuelEconomy;


use App\Domain\Object\FuelEconomy\FuelEconomy;
use App\Models\Refueling;
use Illuminate\Database\Eloquent\Model;

class RefuelingModelBuilder implements \App\Domain\Object\FuelEconomy\IRefuelingNotification
{
    private ?int $refuelingId;
    private FuelEconomy $fuelEconomy;
    private string $gasStation;
    private string $memo;

    function refuelingId(?int $refuelingId): void
    {
        $this->refuelingId = $refuelingId;
    }

    function fuelEconomy(FuelEconomy $fuelEconomy): void
    {
        $this->fuelEconomy = $fuelEconomy;
    }

    function gasStation(string $gasStation): void
    {
        $this->gasStation = $gasStation;
    }

    function memo(string $memo): void
    {
        $this->memo = $memo;
    }

    public function build(): Refueling
    {

        if($this->refuelingId === null){
            $refueling = new Refueling();
            $refueling->refueling_id = $this->refuelingId;
            $refueling->refueling_amount = $this->fuelEconomy->getRefuelingAmount();
            $refueling->refueling_distance = $this->fuelEconomy->getRefuelingDistance();
            $refueling->gas_station = $this->gasStation;
            $refueling->memo = $this->memo;
            return $refueling;
        }
        $refueling = Refueling::where('refueling_id',$this->refuelingId)->get()->first();
        $refueling->refueling_amount = $this->fuelEconomy->getRefuelingAmount();
        $refueling->refueling_distance = $this->fuelEconomy->getRefuelingDistance();
        $refueling->gas_station = $this->gasStation;
        $refueling->memo = $this->memo;
        return $refueling;
    }
}
