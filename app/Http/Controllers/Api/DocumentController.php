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
     * Upload document for authenticated user
     * 
     * @OA\Post(
     *     path="/api/documents/upload",
     *     operationId="uploadDocument",
     *     tags={"Documents"},
     *     summary="Upload document",
     *     description="Upload a document (CR document or VAT document) for the authenticated user. Supported file types: PDF, JPG, PNG, DOC, DOCX. Maximum file size: 10MB.",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"type","document"},
     *                 @OA\Property(
     *                     property="type",
     *                     type="string",
     *                     enum={"cr_document","vat_document"},
     *                     description="Type of document being uploaded. 'cr_document' for Commercial Registration documents, 'vat_document' for VAT registration documents.",
     *                     example="cr_document"
     *                 ),
     *                 @OA\Property(
     *                     property="document",
     *                     type="string",
     *                     format="binary",
     *                     description="Document file to upload. Supported formats: PDF, JPG, PNG, DOC, DOCX. Maximum file size: 10MB. File should be clear and readable."
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Document uploaded successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Document uploaded successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="filename", type="string", example="1703123456_1_cr_document.pdf"),
     *                 @OA\Property(property="path", type="string", example="documents/1703123456_1_cr_document.pdf"),
     *                 @OA\Property(property="url", type="string", example="http://localhost:8000/storage/documents/1703123456_1_cr_document.pdf"),
     *                 @OA\Property(property="size", type="integer", example=1024000),
     *                 @OA\Property(property="mime_type", type="string", example="application/pdf"),
     *                 @OA\Property(property="type", type="string", example="cr_document")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation failed",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation failed"),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(property="document", type="array", @OA\Items(type="string", example="The document field is required."))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Failed to upload document"),
     *             @OA\Property(property="error", type="string", example="Internal server error")
     *         )
     *     )
     * )
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
     * Delete document for authenticated user
     * 
     * @OA\Delete(
     *     path="/api/documents/delete",
     *     operationId="deleteDocument",
     *     tags={"Documents"},
     *     summary="Delete document",
     *     description="Delete a document (CR document or VAT document) for the authenticated user. This will remove the file from storage and clear the reference from the user's profile.",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"type"},
     *             @OA\Property(
     *                 property="type",
     *                 type="string",
     *                 enum={"cr_document","vat_document"},
     *                 description="Type of document to delete. 'cr_document' for Commercial Registration documents, 'vat_document' for VAT registration documents. This will permanently remove the document from storage.",
     *                 example="cr_document"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Document deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Document deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Document not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Document not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation failed",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation failed"),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(property="type", type="array", @OA\Items(type="string", example="The type field is required."))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Failed to delete document"),
     *             @OA\Property(property="error", type="string", example="Internal server error")
     *         )
     *     )
     * )
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
     * Get document information for authenticated user
     * 
     * @OA\Get(
     *     path="/api/documents/info",
     *     operationId="getDocumentInfo",
     *     tags={"Documents"},
     *     summary="Get document information",
     *     description="Get information about a specific document (CR document or VAT document) for the authenticated user, including file details and download URL.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="type",
     *         in="query",
     *         description="Type of document to get info for",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             enum={"cr_document","vat_document"},
     *             example="cr_document"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Document information retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="type", 
     *                     type="string", 
     *                     description="Type of document (cr_document or vat_document)",
     *                     example="cr_document"
     *                 ),
     *                 @OA\Property(
     *                     property="filename", 
     *                     type="string", 
     *                     description="Original filename of the uploaded document",
     *                     example="1703123456_1_cr_document.pdf"
     *                 ),
     *                 @OA\Property(
     *                     property="url", 
     *                     type="string", 
     *                     description="Public URL to access/download the document",
     *                     example="http://localhost:8000/storage/documents/1703123456_1_cr_document.pdf"
     *                 ),
     *                 @OA\Property(
     *                     property="size", 
     *                     type="integer", 
     *                     description="File size in bytes",
     *                     example=1024000
     *                 ),
     *                 @OA\Property(
     *                     property="exists", 
     *                     type="boolean", 
     *                     description="Whether the file exists in storage",
     *                     example=true
     *                 ),
     *                 @OA\Property(
     *                     property="uploaded_at", 
     *                     type="string", 
     *                     format="date-time", 
     *                     description="Timestamp when the document was uploaded",
     *                     example="2024-01-01T00:00:00.000000Z"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Document not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Document not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation failed",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation failed"),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(property="type", type="array", @OA\Items(type="string", example="The type field is required."))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Failed to get document info"),
     *             @OA\Property(property="error", type="string", example="Internal server error")
     *         )
     *     )
     * )
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
