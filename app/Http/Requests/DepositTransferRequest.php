<?php

namespace App\Http\Requests;

use App\Rules\ExistsInCurrency;
use Illuminate\Foundation\Http\FormRequest;

class DepositTransferRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
        return in_array($this->method(), $this->allowedMethods());
    }


    public function allowedMethods(): array
    {
        return ['POST'];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'amount' => ['required', 'numeric', 'min:0', 'not_in:0'],
            'from_user' => ['required', 'numeric', 'exists:users,id'],
            'to_user' => ['required', 'numeric', 'exists:users,id'],
            'currency_key' => [
                'required',
                'string',
                new ExistsInCurrency()
            ],
        ];
    }
}
