<?php


namespace App\Http\Requests\Refuelings;


use App\Domain\Model\FuelEconomy\UpdateRefuelingCommand;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class RefuelingsRegistRequest
 * @property int $refuleing_id
 * @property string $date
 * @property float $refueling_amount
 * @property float $refueling_distance
 * @property string $gas_station
 * @property string $memo
 * @package App\Http\Requests
 */
class RegistRequest extends FormRequest
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
            'refueling_id' => ['nullable','numeric','min:1'],
            'date' => ['date_format:Y-m-d'],
            'refueling_amount' => ['required','numeric','min:1'],
            'refueling_distance' => ['required','numeric','min:1'],
            'gas_station' => ['string'],
            'memo' => ['string'],
        ];
    }

    /**
     * リクエストからコマンドオブジェクトを生成
     * @return UpdateRefuelingCommand
     * @throws \Exception
     */
    public function transferCommand(): UpdateRefuelingCommand
    {
        return new UpdateRefuelingCommand(
            $this->refueling_id ?: null,
            $this->date ? new \DateTime($this->date):null,
            $this->refueling_amount,
            $this->refueling_distance,
            $this->gas_station ?: '',
            $this->memo?: '',
            0
        );
    }
}
