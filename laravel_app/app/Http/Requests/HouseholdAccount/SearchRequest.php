<?php

namespace App\Http\Requests\HouseholdAccount;

use App\Domain\HouseholdAccount\Model\Transaction\SearchCommand;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property int $transactionTypeVal
 * @property int $accountId
 * @property string $viewMonth
 */
class SearchRequest extends FormRequest
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
            "transactionTypeValue" =>  ['nullable','numeric'],
            "accountId" =>  ['nullable','numeric'],
            "viewMonth" =>  ['nullable','string'],
        ];
    }

    public function transferCommand(): SearchCommand
    {
        return new SearchCommand(
            $this->transactionTypeVal,
            $this->accountId,
            $this->viewMonth
        );
    }
}
