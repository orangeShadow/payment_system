<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddCurrencyRateRequest extends FormRequest
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
            'currency' => 'required|exists:currencies,code',
            'rate'     => 'required|numeric|min:0',
            'date'     => 'required|date_format:Y-m-d'
        ];
    }
}
