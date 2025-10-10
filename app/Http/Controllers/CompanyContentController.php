<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;

class CompanyContentController extends Controller
{
    public function store(Request $request, Company $company)
    {
        $validated = $request->validate([
            'content_ids' => 'required|array',
            'content_ids.*' => 'integer|exists:contents,id',
        ]);

        $company->contents()->sync($validated['content_ids']);

        return redirect()->back()->with('success', 'Conteúdo atualizado com sucesso.');
    }
}
