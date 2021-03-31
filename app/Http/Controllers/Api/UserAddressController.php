<?php

namespace App\Http\Controllers\Api;

use App\Models\UserAddress;
use Illuminate\Http\Request;
use App\Http\Resources\UserAddressResource;
use App\Http\Requests\Api\UserAddressRequest;
use Illuminate\Http\Resources\Json\JsonResource;

class UserAddressController extends Controller
{
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

        return (new UserAddressResource($userAddress))->response()->setStatusCode(201);
    }

    public function show(UserAddress $userAddress): JsonResource
    {
        return new UserAddressResource($userAddress);
    }

    public function update(UserAddressRequest $request, UserAddress $userAddress): JsonResource
    {
        $this->authorize('own', $userAddress);

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
        $this->authorize('own', $userAddress);

        $userAddress->delete();

        return response(null, 204);
    }
}
