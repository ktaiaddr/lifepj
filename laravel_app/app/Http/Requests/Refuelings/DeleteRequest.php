<?php


namespace App\Http\Requests\Refuelings;


use App\Domain\Model\Refuelings\UpdateRefuelingCommand;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class RefuelingsRegistRequest
 * @property int $refuleing_id
 * @property int $delete
 * @package App\Http\Requests
 */
class DeleteRequest extends FormRequest
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
            'delete' => ['numeric','min:1'],
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
            $this->refueling_id,
            null,
            null,
            null,
            '',
             '',
            1
        );
    }
}
