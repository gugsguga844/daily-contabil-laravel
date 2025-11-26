<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Tutorial;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TutorialManageController extends Controller
{
    public function index(Request $request)
    {
        $officeId = $request->user()?->office_id;

        $query = Tutorial::query()
            ->with(['category:id,name'])
            ->when($officeId, fn ($q) => $q->where('office_id', $officeId))
            ->latest();

        $tutorials = $query->paginate(12)->through(function (Tutorial $t) {
            return [
                'id' => $t->id,
                'title' => $t->title,
                'description' => $t->description,
                'status' => $t->status,
                'level' => $t->level,
                'category' => $t->category?->only(['id', 'name']),
                'created_at' => $t->created_at,
            ];
        });

        $categories = Category::query()
            ->when($officeId, fn ($q) => $q->where('office_id', $officeId))
            ->orderBy('name')
            ->get(['id', 'name'])
            ->map(fn ($c) => ['value' => $c->id, 'label' => $c->name]);

        $metrics = [
            'total' => (int) Tutorial::where('office_id', $officeId)->count(),
            'published' => (int) Tutorial::where('office_id', $officeId)->where('status', 'published')->count(),
            'drafts' => (int) Tutorial::where('office_id', $officeId)->where('status', 'draft')->count(),
        ];

        return Inertia::render('Admin/Tutorials/Index', [
            'tutorials' => $tutorials,
            'categories' => $categories,
            'metrics' => $metrics,
        ]);
    }
}
