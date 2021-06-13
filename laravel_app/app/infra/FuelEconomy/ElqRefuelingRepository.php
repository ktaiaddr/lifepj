<?php


namespace App\infra\FuelEconomy;


use App\Domain\Object\FuelEconomy\FuelEconomy;
use App\Domain\Object\FuelEconomy\Refueling;

class ElqRefuelingRepository implements \App\Domain\Object\FuelEconomy\IRefuelingRepository
{

    function save(Refueling $refueling): int
    {
        $refuelingModelBuilder = new RefuelingModelBuilder();
        $refueling->notify($refuelingModelBuilder);

        $elqRefueling = $refuelingModelBuilder->build();
//var_dump($elqRefueling);
        $elqRefueling->save();
//var_dump($elqRefueling->refueling_id);
        return $elqRefueling->refueling_id;
    }

    function find(int $refuelingId): Refueling
    {
        $elqReueling = \App\Models\Refueling::where('refueling_id',$refuelingId)->get()->first();

        return new Refueling($elqReueling->refueling_id,
            new FuelEconomy($elqReueling->refueling_amount,
                $elqReueling->refueling_distance),
            $elqReueling->gas_station,
            $elqReueling->memo
        );
    }
}
