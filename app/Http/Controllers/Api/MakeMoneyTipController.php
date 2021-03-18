<?php

namespace App\Http\Controllers\Api;

use App\Models\MakeMoneyTip;
use Illuminate\Http\Request;
use App\Http\Resources\MakeMoneyTipResource;
use Illuminate\Http\Resources\Json\JsonResource;

class MakeMoneyTipController extends Controller
{
    public function index(Request $request): JsonResource
    {
        return MakeMoneyTipResource::collection(MakeMoneyTip::query()->sorted()->recent()->paginate());
    }

    public function show(Request $request, MakeMoneyTip $makeMoneyTip): JsonResource
    {
        return new MakeMoneyTipResource($makeMoneyTip);
    }
}
