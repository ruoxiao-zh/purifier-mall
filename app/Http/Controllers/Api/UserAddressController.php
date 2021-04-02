<?php

namespace App\Http\Controllers\Api;

use App\Enums\HttpCodeEnum;
use App\Models\UserAddress;
use App\Services\UserAddress\UserAddressService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use App\Http\Resources\UserAddressResource;
use App\Http\Requests\Api\UserAddressRequest;
use Illuminate\Http\Resources\Json\JsonResource;

class UserAddressController extends Controller
{
    protected $userAddressService;

    public function __construct()
    {
        $this->userAddressService = new UserAddressService();
    }

    public function index(Request $request): JsonResource
    {
        return UserAddressResource::collection($request->user()->addresses);
    }

    public function store(UserAddressRequest $request): object
    {
        $userAddress = $request->user()->addresses()->create($request->only([
            'province',
            'city',
            'district',
            'address',
            'zip',
            'contact_name',
            'contact_phone',
        ]));

        return (new UserAddressResource($userAddress))->response()->setStatusCode(HttpCodeEnum::HTTP_CODE_201);
    }

    public function show(UserAddress $userAddress): JsonResource
    {
        return new UserAddressResource($userAddress);
    }

    public function update(UserAddressRequest $request, UserAddress $userAddress): JsonResource
    {
        $this->userAddressService->checkAuthorize($userAddress);

        $userAddress->update($request->only([
            'province',
            'city',
            'district',
            'address',
            'zip',
            'contact_name',
            'contact_phone',
        ]));

        return new UserAddressResource($userAddress);
    }

    public function destroy(UserAddress $userAddress)
    {
        $this->userAddressService->checkAuthorize($userAddress);

        $userAddress->delete();

        return response(null, HttpCodeEnum::HTTP_CODE_204);
    }
}
