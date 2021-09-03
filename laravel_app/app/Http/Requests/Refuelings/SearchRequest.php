<?php


namespace App\Http\Requests\Refuelings;


use App\Application\query\Refuelings\FuelEconomyQueryConditions;
use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
{

    const SORT_KEY_DATE = 1;
    const SORT_KEY_DISTANCE = 2;
    const SORT_KEY_AMOUNT = 3;
    const SORT_KEY_FUELECONOMY = 4;
    const SORT_KEY_GASSTATION = 5;
    const SORT_KEY_MEMO = 6;

    const SORT_DESC = 1;
    const SORT_ASC = 2;

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
            'sort_key' => ['numeric'],
            'sort_order' => ['numeric'],
        ];
    }

    public function searchCommand(SearchRequest $request):FuelEconomyQueryConditions
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
            $this->limit,
            $this->sort_key,
            $this->sort_order
        );
    }
}
