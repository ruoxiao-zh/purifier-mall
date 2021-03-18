<?php

namespace App\Http\Controllers\Api;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Resources\CompanyResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyController extends Controller
{
    public function show(Request $request, Company $company): JsonResource
    {
        return new CompanyResource($company);
    }
}
