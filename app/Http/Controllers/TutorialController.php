<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Content;
use App\Models\Tutorial;
use Illuminate\Http\RedirectResponse;
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
    public function show(Tutorial $tutorial)
    {
        if ($tutorial->office_id !== auth()->user()->office_id) {
            abort(403);
        }

        $tutorial->load([
            'category:id,name',
            'steps' => function ($query) {
                $query->orderBy('order');
            },
            'steps.content.uploader:id,name',
            'supportingMaterials.uploader:id,name',
        ]);

        $completedStepsIds = auth()->user()
            ->completedSteps()
            ->whereIn('step_id', $tutorial->steps->pluck('id'))
            ->pluck('step_id')
            ->flip();

        $tutorial->steps->each(function ($step) use ($completedStepsIds) {
            $step->is_completed = isset($completedStepsIds[$step->id]);
        });

        return Inertia::render('Tutorials/Show', [
            'tutorial' => $tutorial,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $tutorial = Tutorial::with([
            'steps' => function ($q) { $q->orderBy('order'); },
            'supportingMaterials',
        ])->findOrFail($id);

        if ($tutorial->office_id !== auth()->user()->office_id) {
            abort(403);
        }

        $categories = Category::where('office_id', auth()->user()->office_id)
            ->orderBy('name')
            ->get(['id', 'name'])
            ->map(fn ($c) => [ 'value' => $c->id, 'label' => $c->name ]);

        $libraryContents = Content::where('office_id', auth()->user()->office_id)
            ->with('uploader')
            ->latest()
            ->get(['id', 'title', 'type', 'path_or_content', 'office_id', 'uploader_id', 'size_bytes', 'created_at']);

        // Normalize payload for front-end
        $payload = [
            'id' => $tutorial->id,
            'title' => $tutorial->title,
            'description' => $tutorial->description,
            'long_description' => $tutorial->long_description,
            'level' => $tutorial->level,
            'category_id' => $tutorial->category_id,
            'status' => $tutorial->status,
            'steps' => $tutorial->steps->map(fn ($s) => [
                'temp_id' => $s->id, // temp for client-side editing
                'title' => $s->title,
                'description' => $s->description,
                'content_id' => $s->content_id,
                'content' => $s->content?->only(['id','title','type']),
            ])->values(),
            'supporting_material_ids' => $tutorial->supportingMaterials->pluck('id'),
        ];

        return Inertia::render('Tutorials/Edit', [
            'tutorial' => $payload,
            'categories' => $categories,
            'libraryContents' => $libraryContents,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $tutorial = Tutorial::with(['steps', 'supportingMaterials'])->findOrFail($id);
        if ($tutorial->office_id !== auth()->user()->office_id) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
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

        \DB::transaction(function () use ($tutorial, $validated) {
            $tutorial->update([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'long_description' => $validated['long_description'],
                'category_id' => $validated['category_id'],
                'level' => $validated['level'],
                'status' => $validated['status'],
            ]);

            // Recreate steps in the new order
            $tutorial->steps()->delete();
            foreach ($validated['steps'] as $index => $stepData) {
                $tutorial->steps()->create([
                    'title' => $stepData['title'],
                    'description' => $stepData['description'] ?? null,
                    'content_id' => $stepData['content_id'] ?? null,
                    'order' => $index + 1,
                    'office_id' => auth()->user()->office_id,
                ]);
            }

            $tutorial->supportingMaterials()->sync($validated['supporting_material_ids'] ?? []);
        });

        return redirect()->route('tutorials.show', $tutorial)->with('success', 'Tutorial atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tutorial $tutorial): RedirectResponse
    {
        if ($tutorial->office_id !== auth()->user()->office_id) {
            abort(403);
        }

        $tutorial->delete();

        return back()->with('success', 'Tutorial excluído com sucesso!');
    }
}
