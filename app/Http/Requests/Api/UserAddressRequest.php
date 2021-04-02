<?php

namespace App\Http\Requests\Api;

class UserAddressRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'province'      => 'required',
            'city'          => 'required',
            'district'      => 'required',
            'address'       => 'required',
            'zip'           => 'required',
            'contact_name'  => 'required',
            'contact_phone' => 'required',
        ];
    }

    public function attributes(): array
    {
        return [
            'province'      => '省',
            'city'          => '城市',
            'district'      => '县/区',
            'address'       => '详细地址',
            'zip'           => '邮编',
            'contact_name'  => '姓名',
            'contact_phone' => '电话',
        ];
    }
}
