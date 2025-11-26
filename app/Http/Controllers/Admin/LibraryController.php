<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Content;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LibraryController extends Controller
{
    public function index(Request $request)
    {
        $officeId = $request->user()?->office_id;

        $query = Content::query()
            ->with('uploader')
            ->when($officeId, fn ($q) => $q->where('office_id', $officeId))
            ->latest();

        $contents = $query->paginate(12)->through(function (Content $c) {
            return [
                'id' => $c->id,
                'title' => $c->title,
                'type' => $c->type,
                'size_bytes' => $c->size_bytes,
                'full_url' => $c->full_url,
                'created_at' => $c->created_at,
                'uploader' => $c->uploader ? [
                    'id' => $c->uploader->id,
                    'name' => $c->uploader->name,
                ] : null,
            ];
        });

        $allForMetrics = Content::query()
            ->when($officeId, fn ($q) => $q->where('office_id', $officeId))
            ->select(['id', 'type', 'size_bytes'])
            ->get();

        $metrics = [
            'total' => $allForMetrics->count(),
            'used_bytes' => (int) $allForMetrics->sum('size_bytes'),
            'videos' => $allForMetrics->where('type', 'videos')->count(),
            'by_type' => $allForMetrics->groupBy('type')->map->count(),
        ];

        return Inertia::render('Admin/Library/Index', [
            'contents' => $contents,
            'metrics' => $metrics,
        ]);
    }
}
