<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\FormRequest;

class AuthorizationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'username' => 'required|string',
            'password' => 'required|alpha_dash|min:6',
        ];
    }

    public function attributes(): array
    {
        return [
            'username' => '用户名',
            'password' => '密码',
        ];
    }
}
