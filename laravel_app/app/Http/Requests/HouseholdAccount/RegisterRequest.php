<?php

namespace App\Http\Requests\HouseholdAccount;

use App\Domain\HouseholdAccount\Model\Transaction\RegisterCommand;
use Illuminate\Foundation\Http\FormRequest;


/**
 * @property int amount
 * @property int transactionTypeValue
 * @property int reduceAccountId
 * @property int increaseAccountId
 * @property string contents
 */
class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
//        return false;
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
            "amount" =>  ['required','numeric','min:1'],
            "transactionTypeValue" =>  ['required','numeric','min:1'],
            "reduceAccountId" =>  ['nullable','numeric','min:1'],
            "increaseAccountId" =>  ['nullable','numeric','min:1'],
            "contents" => ['required','string'],
        ];
    }

    /**
     * リクエストからコマンドオブジェクトを生成
     * @return RegisterCommand
     * @throws \Exception
     */
    public function transferCommand(): RegisterCommand
    {
        return new RegisterCommand(
            $this->amount,
            $this->transactionTypeValue,
            $this->reduceAccountId,
            $this->increaseAccountId,
            $this->contents
        );
    }
}
