<?php

namespace App\Http\Controllers;

use App\Models\CompanyLaw;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyLawController extends Controller
{
    public function __construct()
    {
        //$this->middleware('role:admin');
    }

    public function index()
    {
        $companyLaw = CompanyLaw::firstOrCreate();

        return view('company-laws.index', compact('companyLaw'));
    }

    public function update(Request $request, CompanyLaw $companyLaw)
    {
        $validated = $request->validate([
            'max_daily_hours' => 'required|numeric|min:0',
            'max_daily_break_hours' => 'required|numeric|min:0',
            'min_weekly_hours' => 'required|numeric|min:0',
            'max_weekly_hours' => 'required|numeric|min:0',
            'min_monthly_hours' => 'required|numeric|min:0',
            'max_monthly_hours' => 'required|numeric|min:0',
        ]);

        $companyLaw->update($validated);

        return redirect()->route('company-laws.index')
            ->with('success', 'Company law settings updated successfully.');
    }

    public function edit(CompanyLaw $companyLaw)
    {
        return view('company-laws.edit', compact('companyLaw'));
    }
} 