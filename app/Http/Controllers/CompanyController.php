<?php

namespace App\Http\Controllers;

use App\Enums\TaxRegime;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

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
            'company' => [
                'id' => $company->id,
                'name' => $company->name,
                'fantasy_name' => $company->fantasy_name,
                'cnpj' => $company->cnpj,
                'tax_regime' => $company->tax_regime?->value,
                'tax_regime_label' => $company->tax_regime?->label(),
                'is_active' => $company->is_active,
                'accountant_name' => optional($company->accountant)->name,
                'phone' => $company->phone,
                'email' => $company->email,
                'street' => $company->street,
                'number' => $company->number,
                'city' => $company->city,
                'state' => $company->state,
                'zip_code' => $company->zip_code,
            ],
        ]);
    }

    public function create(): Response
    {
        $office = auth()->user()->office;

        $accountants = $office->users()->get()->map(function (User $user) {
            return [
                'value' => $user->id,
                'label' => $user->name,
            ];
        });

        return Inertia::render('Companies/Create', [
            'accountants' => $accountants,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'fantasy_name' => 'required|string|max:255',
            'cnpj' => 'required|string|max:255',
            'tax_regime' => 'required|in:'.TaxRegime::values(),
            'phone' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'street' => 'required|string|max:255',
            'number' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip_code' => 'required|string|max:255',
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
            'is_active' => true,
            'creator_id' => auth()->id(),
            'office_id' => optional(auth()->user())->office_id,
            'accountant_id' => $request->accountant_id,
        ]);

        return redirect()->route('companies.index')->with('success', 'Empresa criada com sucesso.');
    }
}
