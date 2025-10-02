<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class CompanyController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $companies = $user->office
            ->companies()
            ->with('accountant')
            ->latest()
            ->paginate(10)
            ->through(function ($company) {
                return [
                    'id' => $company->id,
                    'name' => $company->name,
                    'cnpj' => $company->cnpj,
                    'tax_regime' => $company->tax_regime?->value,
                    'tax_regime_label' => $company->tax_regime?->label(),
                    'is_active' => $company->is_active,
                    'accountant_name' => optional($company->accountant)->name,
                ];
            });

        return Inertia::render('Companies/Index', [
            'companies' => $companies,
        ]);
    }

    public function show($company_id)
    {
        $user = auth()->user();

        $company = $user->office
            ->companies()
            ->with('accountant')
            ->findOrFail($company_id);

        return Inertia::render('Companies/Show', [
            'company' => $company,
        ]);
    }
}
