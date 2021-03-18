<?php

namespace App\Http\Controllers\Api;

use App\Models\Slideshow;
use Illuminate\Http\Request;
use App\Http\Resources\SlideshowResource;
use Illuminate\Http\Resources\Json\JsonResource;

class SlideshowController extends Controller
{
    public function index(Request $request): JsonResource
    {
        return SlideshowResource::collection(Slideshow::query()->sorted()->recent()->paginate());
    }
}
