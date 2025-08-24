<?php

namespace App\Http\Controllers\SystemUser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;


class DocumentController extends Controller
{
    /**
     * List all documents
     */
    public function index()
    {
        $documents = Document::latest()->get();

        return response()->json([
            'success' => true,
            'data'    => $documents,
        ]);
    }

    /**
     * Store a new document (upload to S3)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'file'      => 'required|file|max:5120', // 5MB limit (adjust if needed)
            'file_type' => 'required|string|in:' . implode(',', Document::FILE_TYPES),
            'useage'    => 'nullable|string|in:' . implode(',', Document::USAGES),
            'published' => 'boolean',
        ]);

        // Upload file to S3
        $path = $request->file('file')->store('documents', 's3');

        // Make file publicly accessible (optional)
        $url = Storage::disk('s3')->url($path);

        $document = Document::create([
            'file_type' => $validated['file_type'],
            'useage'    => $validated['useage'] ?? null,
            'published' => $validated['published'] ?? true,
            'user_id'   => auth()->id(),
            'url'       => $url,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Document uploaded successfully',
            'data'    => $document,
        ], 201);
    }

    /**
     * Update an existing document
     */
    public function update(Request $request, Document $document)
    {
        $validated = $request->validate([
            'file'      => 'sometimes|file|max:5120',
            'file_type' => 'sometimes|string|in:' . implode(',', Document::FILE_TYPES),
            'useage'    => 'sometimes|string|in:' . implode(',', Document::USAGES),
            'published' => 'boolean',
        ]);

        // If updating file → delete old one from S3
        if ($request->hasFile('file')) {
            if ($document->url) {
                // Extract S3 key from URL
                $oldPath = parse_url($document->url, PHP_URL_PATH);
                $oldPath = ltrim($oldPath, '/');
                Storage::disk('s3')->delete($oldPath);
            }

            $path = $request->file('file')->store('documents', 's3');
            $document->url = Storage::disk('s3')->url($path);
        }

        $document->update(array_filter([
            'file_type' => $validated['file_type'] ?? $document->file_type,
            'useage'    => $validated['useage'] ?? $document->useage,
            'published' => $validated['published'] ?? $document->published,
            'url'       => $document->url,
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Document updated successfully',
            'data'    => $document,
        ]);
    }

    /**
     * Delete a document (remove from S3 + soft delete record)
     */
    public function destroy(Document $document)
    {
        if ($document->url) {
            $oldPath = parse_url($document->url, PHP_URL_PATH);
            $oldPath = ltrim($oldPath, '/');
            Storage::disk('s3')->delete($oldPath);
        }

        $document->delete();

        return response()->json([
            'success' => true,
            'message' => 'Document deleted successfully',
        ]);
    }
}
