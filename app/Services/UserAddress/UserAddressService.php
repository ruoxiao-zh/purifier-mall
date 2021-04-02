<?php

namespace App\Services\UserAddress;

use App\Models\UserAddress;
use Illuminate\Auth\Access\Gate;
use Illuminate\Auth\Access\AuthorizationException;

class UserAddressService
{
    public function checkAuthorize(UserAddress $userAddress): void
    {
        try {
            Gate::authorize('own', $userAddress);
        } catch (AuthorizationException $e) {
            abort($e->getCode(), '权限不足');
        }
    }
}
