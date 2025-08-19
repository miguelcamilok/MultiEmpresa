<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SuperAdminController extends Controller
{
    public function approveCompany($id): JsonResponse
    {
        $company = Company::findOrFail($id);
        $company->status = 'approved';
        $company->save();

        return response()->json([
            'message' => 'Company approved successfully',
            'company' => $company
        ]);
    }
}
