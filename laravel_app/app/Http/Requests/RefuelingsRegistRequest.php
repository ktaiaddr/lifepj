<?php


namespace App\Http\Requests;


use App\Domain\Model\FuelEconomy\UpdateRefuelingCommand;
use Illuminate\Foundation\Http\FormRequest;

class RefuelingsRegistRequest extends FormRequest
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
            'refuleing_id' => ['numeric','min:1'],
            'date' => ['date_format:Y-m-d'],
            'refueling_amount' => ['required','numeric','min:1'],
            'refueling_distance' => ['required','numeric','min:1'],
            'gas_station' => ['string'],
            'memo' => ['string'],
        ];
    }

    public function transferCommand(): UpdateRefuelingCommand
    {
        return new UpdateRefuelingCommand(
            $this->refuleing_id ? $this->refuleing_id : null,
            $this->date ? new \DateTime($this->date):null,
            $this->refueling_amount,
            $this->refueling_distance,
            $this->gas_station ? $this->gas_station : '',
            $this->memo?$this->memo : ''
        );
    }
}
