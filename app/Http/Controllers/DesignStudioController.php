<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartDesign;
use App\Models\UserFavorite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DesignStudioController extends Controller
{
    public function index(Request $request)
    {
        $designId = $request->get('design_id');
        $imageUrl = $request->get('image_url');
        
        return view('designs.studio', compact('designId', 'imageUrl'));
    }

    public function saveDesign(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'design_data' => 'required|array',
            'image_url' => 'required|string'
        ]);

        try {
            // Check for duplicate designs (same title and image URL within last 5 minutes)
            $userId = Auth::check() ? Auth::id() : null;
            $sessionId = Auth::check() ? null : session()->getId();
            
            $recentDuplicate = CartDesign::where('title', $request->title)
                ->where('image_url', $request->image_url)
                ->where('is_active', true)
                ->where(function($query) use ($userId, $sessionId) {
                    if ($userId) {
                        $query->where('user_id', $userId);
                    } else {
                        $query->where('session_id', $sessionId);
                    }
                })
                ->where('created_at', '>=', now()->subMinutes(5))
                ->first();
            
            if ($recentDuplicate) {
                return response()->json([
                    'success' => false,
                    'message' => 'تم حفظ التصميم مسبقاً!'
                ]);
            }
            
            CartDesign::create([
                'user_id' => $userId,
                'session_id' => $sessionId,
                'title' => $request->title,
                'design_data' => $request->design_data,
                'image_url' => $request->image_url,
                'thumbnail_url' => $request->image_url,
                'is_active' => true
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم حفظ التصميم في السلة بنجاح!'
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to save design', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'فشل في حفظ التصميم'
            ], 500);
        }
    }

    public function addToFavorites(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'يجب تسجيل الدخول أولاً'
            ], 401);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'design_data' => 'required|array',
            'image_url' => 'required|string'
        ]);

        try {
            UserFavorite::create([
                'user_id' => Auth::id(),
                'favoritable_type' => 'custom_design',
                'favoritable_id' => uniqid(),
                'data' => [
                    'title' => $request->title,
                    'design_data' => $request->design_data,
                    'image_url' => $request->image_url
                ]
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم إضافة التصميم للمفضلة!'
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to add to favorites', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'فشل في إضافة التصميم للمفضلة'
            ], 500);
        }
    }
}
