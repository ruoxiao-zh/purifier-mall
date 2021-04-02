<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\FormRequest;

class HandleRefundRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'agree'  => ['required', 'boolean'],
            'reason' => ['required_if:agree,false'], // 拒绝退款时需要输入拒绝理由
        ];
    }
}
