<?php


namespace App\Http\Requests;


use App\Application\query\FuelEconomy\FuelEconomyQueryConditions;
use Illuminate\Foundation\Http\FormRequest;

class RefuelingsSearchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'date_start' => ['date_format:Y-m-d'],
            'date_end' => ['date_format:Y-m-d'],
            'amount_low' => ['numeric'],
            'amount_high' => ['numeric'],
            'distance_low' => ['numeric'],
            'distance_high' => ['numeric'],
            'gas_station' => ['string'],
            'memo' => ['string'],
            'page' => ['numeric'],
            'limit' => ['numeric'],
        ];
    }

    public function searchCommand(RefuelingsSearchRequest $request):FuelEconomyQueryConditions
    {
        return new FuelEconomyQueryConditions(
            $this->date_start ? new \DateTime($this->date_start) : null ,
            $this->date_end? new \DateTime($this->date_end) : null,
            $this->amount_low,
            $this->amount_high,
            $this->distance_low,
            $this->distance_high,
            $this->gas_station,
            $this->memo,
            $this->page,
            $this->limit
        );
    }
}
