<?php

namespace App\Http\Controllers\Api;

use App\Company;
use Illuminate\Http\Request;
use App\Http\Resources\CompanyResource;

class CompanyController extends Controller
{
    public function show(Request $request, Company $company)
    {
        return new CompanyResource($company);
    }
}
