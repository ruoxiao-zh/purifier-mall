<?php

namespace App\Http\Requests\Api;

class MessageCodeRequest extends FormRequest
{
    public function rules()
    {
        return [
            'phone' => [
                'required',
                'phone:CN,mobile',
                'unique:users',
            ],
        ];
    }

    public function attributes()
    {
        return [
            'phone' => '手机号',
        ];
    }
}
