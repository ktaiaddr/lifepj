<?php

namespace App\Http\Requests\HouseholdAccount;

use Illuminate\Foundation\Http\FormRequest;

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
            "contents" => ['required','string','min:1'],
        ];
    }
}
