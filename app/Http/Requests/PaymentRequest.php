<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PaymentRequest extends FormRequest
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
            'name'     => 'required|string',
            'start_dt' => 'nullable|date_format:Y-m-d H:i:s',
            'end_dt'   => 'nullable|date_format:Y-m-d H:i:s',
            'format'   => [Rule::in(['html', 'csv', 'xml']), 'nullable'],
        ];
    }
}
