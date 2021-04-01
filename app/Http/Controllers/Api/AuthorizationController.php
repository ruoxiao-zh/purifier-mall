<?php

namespace App\Http\Controllers\Api;

use App\Enums\HttpCodeEnum;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;
use App\Http\Requests\Api\AuthorizationRequest;

class AuthorizationController extends Controller
{
    public function store(AuthorizationRequest $request): object
    {
        $username = $request->username;

        filter_var($username, FILTER_VALIDATE_EMAIL) ?
            $credentials['email'] = $username : $credentials['phone'] = $username;

        $credentials['password'] = $request->password;

        if ( !$token = \Auth::guard('api')->attempt($credentials)) {
            throw new AuthenticationException(trans('auth.failed'));
        }

        return $this->respondWithToken($token)->setStatusCode(HttpCodeEnum::HTTP_CODE_201);
    }

    public function update(): object
    {
        $token = auth('api')->refresh();

        return $this->respondWithToken($token);
    }

    public function destroy()
    {
        auth('api')->logout();

        return response(null, HttpCodeEnum::HTTP_CODE_204);
    }

    protected function respondWithToken(string $token): object
    {
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'expires_in'   => auth('api')->factory()->getTTL() * 60,
        ]);
    }
}
