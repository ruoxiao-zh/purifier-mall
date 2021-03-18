<?php

namespace App\Http\Controllers\Api;

use App\Models\MakeMoneyTip;
use Illuminate\Http\Request;
use App\Http\Resources\MakeMoneyTipResource;

class MakeMoneyTipController extends Controller
{
    public function index(Request $request)
    {
        return MakeMoneyTipResource::collection(MakeMoneyTip::query()->sorted()->recent()->paginate());
    }

    public function show(Request $request, MakeMoneyTip $makeMoneyTip)
    {
        return new MakeMoneyTipResource($makeMoneyTip);
    }
}
