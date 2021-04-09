<?php

namespace App\Services\UserAddress;

use App\Enums\HttpCodeEnum;
use App\Models\UserAddress;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UserAddressService
{
    use AuthorizesRequests;

    public function checkAuthorize(UserAddress $userAddress): void
    {
        try {
            $this->authorize('own', $userAddress);
        } catch (AuthorizationException $e) {
            abort(HttpCodeEnum::HTTP_CODE_403, '权限不足');
        }
    }
}
