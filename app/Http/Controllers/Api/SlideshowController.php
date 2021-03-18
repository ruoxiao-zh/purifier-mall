<?php

namespace App\Http\Controllers\Api;

use App\Models\Slideshow;
use Illuminate\Http\Request;
use App\Http\Resources\SlideshowResource;

class SlideshowController extends Controller
{
    public function index(Request $request)
    {
        return SlideshowResource::collection(Slideshow::query()->sorted()->recent()->paginate());
    }
}
