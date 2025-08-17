<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DocumentController extends Controller
{
    /**
     * Upload document
     */
    public function uploadDocument(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), User::getFileUploadRules());

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $user = $request->user();
            $documentType = $request->input('type');
            $file = $request->file('document');

            // Generate unique filename
            $filename = time() . '_' . $user->id . '_' . $documentType . '.' . $file->getClientOriginalExtension();
            
            // Store file in documents directory
            $path = $file->storeAs('documents', $filename, 'public');

            // Update user's document path
            $fieldName = $documentType . '_path';
            $user->update([$fieldName => $path]);

            // Get the public URL
            $url = Storage::url($path);

            return response()->json([
                'success' => true,
                'message' => 'Document uploaded successfully',
                'data' => [
                    'filename' => $filename,
                    'path' => $path,
                    'url' => $url,
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'type' => $documentType
                ],
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload document',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete document
     */
    public function deleteDocument(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'type' => 'required|in:cr_document,vat_document'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $user = $request->user();
            $documentType = $request->input('type');
            $fieldName = $documentType . '_path';

            // Check if document exists
            if (!$user->$fieldName) {
                return response()->json([
                    'success' => false,
                    'message' => 'Document not found',
                ], 404);
            }

            // Delete file from storage
            if (Storage::disk('public')->exists($user->$fieldName)) {
                Storage::disk('public')->delete($user->$fieldName);
            }

            // Update user record
            $user->update([$fieldName => null]);

            return response()->json([
                'success' => true,
                'message' => 'Document deleted successfully',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete document',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get document info
     */
    public function getDocumentInfo(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'type' => 'required|in:cr_document,vat_document'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $user = $request->user();
            $documentType = $request->input('type');
            $fieldName = $documentType . '_path';

            if (!$user->$fieldName) {
                return response()->json([
                    'success' => false,
                    'message' => 'Document not found',
                ], 404);
            }

            $url = Storage::url($user->$fieldName);
            $exists = Storage::disk('public')->exists($user->$fieldName);
            $size = $exists ? Storage::disk('public')->size($user->$fieldName) : 0;

            return response()->json([
                'success' => true,
                'data' => [
                    'type' => $documentType,
                    'filename' => basename($user->$fieldName),
                    'url' => $url,
                    'size' => $size,
                    'exists' => $exists,
                    'uploaded_at' => $user->updated_at
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get document info',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
