<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;
use App\Models\Company;
use App\Enums\TaxRegime;
use Illuminate\Http\Request;

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

    public function create(): Response
    {
        return Inertia::render('Companies/Create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'fantasy_name' => 'required|string|max:255',
            'cnpj' => 'required|string|max:255',
            'tax_regime' => 'required|in:' . TaxRegime::values(),
            'phone' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'street' => 'required|string|max:255',
            'number' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip_code' => 'required|string|max:255',
            'is_active' => 'required|boolean',
            'creator_id' => 'required|exists:users,id',
            'accountant_id' => 'required|exists:users,id',
        ]);

        $company = Company::create([
            'name' => $request->name,
            'fantasy_name' => $request->fantasy_name,
            'cnpj' => $request->cnpj,
            'tax_regime' => $request->tax_regime,
            'phone' => $request->phone,
            'email' => $request->email,
            'street' => $request->street,
            'number' => $request->number,
            'city' => $request->city,
            'state' => $request->state,
            'zip_code' => $request->zip_code,
            'is_active' => $request->is_active,
            'creator_id' => $request->creator_id,
            'accountant_id' => $request->accountant_id,
        ]);

        return redirect()->route('companies.index')->with('success', 'Empresa criada com sucesso.');
    }
}
