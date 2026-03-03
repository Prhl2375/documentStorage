<?php

namespace App\Http\Controllers;

use App\Jobs\DocumentDeleted;
use App\Models\Document;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DocumentsController extends Controller
{
    public function store(Request $request):JsonResponse
    {
        $validated = $request->validate([
            'file' => [
                'required',
                'file',
                'mimes:pdf,docx',
                'max:10240',
            ],
        ]);

        $file      = $validated['file'];
        $storedName = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path      = Storage::disk('local')->putFileAs('documents', $file, $storedName);

        Document::create([
            'original_name' => $file->getClientOriginalName(),
            'stored_name'   => $storedName,
            'path'          => $path,
            'disk'          => 'local',
            'mime_type'     => $file->getMimeType(),
            'size'          => $file->getSize(),
            'expires_at'    => now()->addHours(24),
        ]);

        return response()->json([
            'message' => 'File uploaded successfully',
        ], 201);
    }

    public function list():View
    {
        $documents = Document::all();
        return view("documents", ["documents" => $documents]);
    }

    public function destroy(Document $document):JsonResponse
    {
        DocumentDeleted::dispatch(
            $document->original_name,
            $document->mime_type,
            $document->size,
            'manual',
            now()->toISOString(),
        );

        $document->deleteFile();
        $document->delete();

        return response()->json(['message' => 'File deleted successfully']);
    }
}
