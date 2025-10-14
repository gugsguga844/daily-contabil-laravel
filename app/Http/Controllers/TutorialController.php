<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Content;
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
        $categories = Category::where('office_id', auth()->user()->office_id)
            ->orderBy('name')    
            ->get(['id', 'name']);

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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
