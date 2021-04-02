<?php

namespace App\Http\Requests\Api;

class ApplyRefundRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'reason' => 'required',
        ];
    }

    public function attributes(): array
    {
        return [
            'reason' => '退货原因',
        ];
    }
}
