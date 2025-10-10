<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Content;

class ContentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'file' => 'required|file|max:512000'
        ]);
        $file = $validated['file'];

        $path = $file->store('contents', 's3');

        $content = Content::create([
            'title' => $file->getClientOriginalName(),
            'type' => $this->determineTypeFromMime($file->getClientMimeType()),
            'path_or_content' => $path,
            'office_id' => auth()->user()->office_id,
            'uploader_id' => auth()->id(),
            'size_bytes' => $file->getSize(),
        ]);

        return redirect()->back()->with('newContent', $content);
    }

    public function destroy(Content $content)
    {
        if ($content->office_id !== auth()->user()->office_id) {
            abort(403, 'Você não tem permissão para excluir este conteúdo.');
        }

        Storage::disk('s3')->delete($content->path_or_content);
        $content->delete();

        return redirect()->back()->with('contentDeleted', true);
    }

    private function determineTypeFromMime($mimeType)
    {
        if (str_starts_with($mimeType, 'image/')) return 'imagem';
        if (str_starts_with($mimeType, 'video/')) return 'video';
        if (in_array($mimeType, ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])) return 'documento';
        if (in_array($mimeType, ['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])) return 'planilha';
        return 'outro';
    }
}
