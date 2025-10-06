<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageUploadController extends Controller
{
  /**
   * Handle image upload for CKEditor
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function upload(Request $request): JsonResponse
  {
    try {
      // Validate the request
      $request->validate([
        'upload' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // 2MB max
      ]);

      // Get the uploaded file
      $file = $request->file('upload');

      // Generate unique filename
      $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

      // Store the file in public/uploads directory
      $path = $file->storeAs('uploads/posts', $filename, 'public');

      // Get the public URL
      $url = asset('storage/' . $path);

      // Return success response in CKEditor format
      return response()->json([
        'url' => $url,
        'uploaded' => true,
        'fileName' => $filename,
        'uploadedAt' => now()->toISOString(),
      ], 200);
    } catch (\Exception $e) {
      // Return error response in CKEditor format
      return response()->json([
        'error' => [
          'message' => $e->getMessage(),
        ],
        'uploaded' => false,
      ], 422);
    }
  }

  /**
   * Delete uploaded image
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function delete(Request $request): JsonResponse
  {
    try {
      $request->validate([
        'url' => 'required|string',
      ]);

      $url = $request->input('url');

      // Extract the path from the URL
      $path = str_replace(asset('storage/'), '', $url);

      // Delete the file
      if (Storage::disk('public')->exists($path)) {
        Storage::disk('public')->delete($path);

        return response()->json([
          'success' => true,
          'message' => 'Image deleted successfully',
        ]);
      }

      return response()->json([
        'success' => false,
        'message' => 'Image not found',
      ], 404);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => $e->getMessage(),
      ], 500);
    }
  }
}
