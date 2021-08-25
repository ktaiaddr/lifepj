<?php


namespace App\infra\EloquentRepository;


use App\Domain\Model\FuelEconomy\FuelEconomy;
use App\Models\Refueling;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RefuelingModelBuilder implements \App\Domain\Model\FuelEconomy\IRefuelingNotification
{
    private ?int $refuelingId;
    private int $userId;
    private string $date;
    private float $refuelingAmount;
    private float $refuelingDistance;
    private ?string $gasStation;
    private ?string $memo;
    private ?int $delFlg;

    function refuelingId(?int $refuelingId): void
    {
        $this->refuelingId = $refuelingId;
    }

    function userId(int $userId): void
    {
        $this->userId = $userId;
    }

    function date(\DateTime $date): void
    {
        $this->date = $date->format( 'Y-m-d' );
    }

    function refuelingAmount(float $refuelingAmount): void
    {
        $this->refuelingAmount = $refuelingAmount;
    }

    function refuelingDistance(float $refuelingDistance): void
    {
        $this->refuelingDistance = $refuelingDistance;
    }

    function gasStation(?string $gasStation): void
    {
        $this->gasStation = $gasStation;
    }

    function memo(?string $memo): void
    {
        $this->memo = $memo;
    }

    function delFlg(?int $delFlg): void
    {
        $this->delFlg = $delFlg;
    }

    /**
     * @return Refueling
     */
    public function build(): Refueling
    {
        $refueling_id = $this->refuelingId;
        if(!$refueling_id)
            $refueling_id = Refueling::max('refueling_id')+1;



        $refueling = Refueling::create([
            'refueling_id' => $refueling_id,
            'user_id' => $this->userId,
            'date' => $this->date,
            'refueling_amount' =>$this->refuelingAmount,
            'refueling_distance' =>$this->refuelingDistance,
            'gas_station' =>$this->gasStation,
            'memo' =>$this->memo,
            'del_flg' =>$this->delFlg
        ]);

        Refueling::where('refueling_id',$refueling_id)
            ->where('id', '<>',$refueling->id)
            ->where('del_flg',0)
            ->update(['del_flg'=>1]);

        return $refueling;

        //新規EloqModel
        if(!$this->refuelingId){

//            $subquery = DB::table( (new Refueling)->getTable() )
//                ->where('user_id', $this->userId )
//                ->selectRaw(DB::raw('MAX(refueling_id)+1 as hoge'))
//                ->orderBy('refueling_id', 'desc')
//                ->take(1)->toSql();

            $refueling = Refueling::create([
                'refueling_id' => (Refueling::max('refueling_id')+1),
                'user_id' => $this->userId,
                'date' => $this->date,
                'refueling_amount' =>$this->refuelingAmount,
                'refueling_distance' =>$this->refuelingDistance,
                'gas_station' =>$this->gasStation,
                'memo' =>$this->memo
            ]);
//            $refueling = new Refueling();
//            $refueling->refueling_id = $subquery->hoge;
//            $refueling->user_id = $this->userId;
//            $refueling->date = $this->date;
//            $refueling->refueling_amount = $this->refuelingAmount;
//            $refueling->refueling_distance = $this->refuelingDistance;
//            $refueling->gas_station = $this->gasStation;
//            $refueling->memo = $this->memo;
            return $refueling;
        }

        //既存EloqModel
        $refueling = Refueling::where('refueling_id',$this->refuelingId)->where('user_id',$this->userId)->get()->first();
        $refueling->date = $this->date;
        $refueling->refueling_amount = $this->refuelingAmount;
        $refueling->refueling_distance = $this->refuelingDistance;
        $refueling->gas_station = $this->gasStation;
        $refueling->memo = $this->memo;
        $refueling->save();
        return $refueling;
    }
}
