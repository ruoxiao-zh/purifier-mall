<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\FormRequest;

class MessageCodeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'phone' => [
                'required',
                'phone:CN,mobile',
                'unique:users',
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'phone' => '手机号',
        ];
    }
}
