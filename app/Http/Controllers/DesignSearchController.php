<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DesignApiService;
use App\Models\Design;
use App\Models\UserFavorite;
use App\Models\CartDesign;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DesignSearchController extends Controller
{
    protected $designApiService;

    public function __construct(DesignApiService $designApiService)
    {
        $this->designApiService = $designApiService;
    }

    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $category = $request->get('category', '');
        $designs = [];
        
        if ($search || $category) {
            try {
                if ($search) {
                    $result = $this->designApiService->searchExternalDesigns($search);
                } else {
                    $result = $this->designApiService->fetchExternalDesigns(['limit' => 20]);
                }
                
                if ($result && isset($result['data'])) {
                    $designs = $result['data'];
                }
            } catch (\Exception $e) {
                Log::error('Design search failed', ['error' => $e->getMessage()]);
                session()->flash('error', 'Failed to load designs');
            }
        }

        return view('designs.search', compact('designs', 'search', 'category'));
    }

    public function saveToCart(Request $request)
    {
        $request->validate([
            'design_id' => 'required',
            'title' => 'required|string|max:255',
            'image_url' => 'required|url'
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
                'design_data' => ['original_design_id' => $request->design_id],
                'image_url' => $request->image_url,
                'thumbnail_url' => $request->image_url,
                'is_active' => true
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم حفظ التصميم في السلة بنجاح!'
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to save design to cart', ['error' => $e->getMessage()]);
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
            'design_id' => 'required',
            'title' => 'required|string|max:255',
            'image_url' => 'required|url'
        ]);

        try {
            UserFavorite::firstOrCreate([
                'user_id' => Auth::id(),
                'favoritable_type' => 'design',
                'favoritable_id' => $request->design_id,
            ], [
                'data' => [
                    'title' => $request->title,
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
