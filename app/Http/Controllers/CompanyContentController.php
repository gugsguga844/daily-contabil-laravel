<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyContentController extends Controller
{
    public function store(Request $request, Company $company)
    {
        $validated = $request->validate([
            'content_ids' => 'present|array',
            'content_ids.*' => 'integer|exists:contents,id',
        ]);

        $company->contents()->sync($validated['content_ids']);

        return redirect()->back()->with('success', 'Conteúdo atualizado com sucesso.');
    }
}
