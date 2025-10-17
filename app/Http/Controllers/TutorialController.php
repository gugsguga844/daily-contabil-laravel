<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Content;
use App\Models\Tutorial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class TutorialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Map categories to { value, label } for FormSelect
        $categories = Category::where('office_id', auth()->user()->office_id)
            ->orderBy('name')
            ->get(['id', 'name'])
            ->map(fn ($c) => [
                'value' => $c->id,
                'label' => $c->name,
            ]);

        $libraryContents = Content::where('office_id', auth()->user()->office_id)
            ->with('uploader')
            ->latest()
            ->get(['id', 'title', 'type', 'path_or_content', 'office_id', 'uploader_id', 'size_bytes', 'created_at']);

        return Inertia::render('Tutorials/Create', [
            'categories' => $categories,
            'libraryContents' => $libraryContents,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            // DB columns are NOT nullable, enforce required here
            'description' => 'required|string|max:255',
            'long_description' => 'required|string',
            'level' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'status' => 'required|string',

            'steps' => 'present|array',
            'supporting_material_ids' => 'present|array',

            'steps.*.title' => 'required|string|max:255',
            'steps.*.description' => 'nullable|string|max:255',
            'steps.*.content_id' => 'nullable|integer|exists:contents,id',
            'supporting_material_ids.*' => 'integer|exists:contents,id',
        ]);

        $tutorial = DB::transaction(function () use ($validated) {
            $tutorial = Tutorial::create([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'long_description' => $validated['long_description'],
                'category_id' => $validated['category_id'],
                'level' => $validated['level'],
                'status' => $validated['status'],
                'office_id' => auth()->user()->office_id,
            ]);

            foreach ($validated['steps'] as $index => $stepData) {
                $tutorial->steps()->create([
                    'title' => $stepData['title'],
                    'description' => $stepData['description'],
                    'content_id' => $stepData['content_id'],
                    'order' => $index + 1,
                    'office_id' => auth()->user()->office_id,
                ]);
            }

            if (! empty($validated['supporting_material_ids'])) {
                $tutorial->supportingMaterials()->attach($validated['supporting_material_ids']);
            }

            return $tutorial;
        });

        return redirect()->route('tutorials.show', $tutorial)->with('success', 'Tutorial criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $tutorial = Tutorial::findOrFail($id);

        return Inertia::render('Tutorials/Show', [
            'tutorial' => $tutorial,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
