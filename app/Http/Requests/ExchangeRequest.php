<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExchangeRequest extends FormRequest
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
            'user_from' => ['required','exists:users,id'],
            'user_to'   => 'required|exists:users,id',
            'currency'  => 'required|exists:currencies,code',
            'amount'    => 'required|numeric|min:0'
        ];
    }
}
