<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Design;
use App\Models\UserFavorite;
use App\Models\CartDesign;
use App\Services\DesignApiService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DesignController extends Controller
{
    protected $designApiService;

    public function __construct(DesignApiService $designApiService)
    {
        $this->designApiService = $designApiService;
    }

    /**
     * Search external designs from Freepik API (like web design.search)
     */
    public function freepikSearch(Request $request): JsonResponse
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
                    
                    // Check cart status for each design
                    $designs = $this->addCartStatusToDesigns($designs);
                }
            } catch (\Exception $e) {
                Log::error('Design search failed', ['error' => $e->getMessage()]);
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to load designs: ' . $e->getMessage()
                ], 500);
            }
        }

        return response()->json([
            'success' => true,
            'data' => $designs,
            'search' => $search,
            'category' => $category
        ]);
    }

    /**
     * Get all designs in cart
     */
    public function getCartDesigns(Request $request): JsonResponse
    {
        try {
            $userId = Auth::check() ? Auth::id() : null;
            $sessionId = Auth::check() ? null : session()->getId();

            $query = CartDesign::active();

            if ($userId) {
                $query->where('user_id', $userId);
            } else {
                $query->where('session_id', $sessionId);
            }

            $designs = $query->orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'data' => $designs,
                'count' => $designs->count(),
                'user_type' => $userId ? 'authenticated' : 'guest'
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to get cart designs', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'session_id' => session()->getId()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'فشل في جلب التصاميم من السلة: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Save design to cart (like web design.save-to-cart)
     */
    public function saveToCart(Request $request): JsonResponse
    {
        Log::info('saveToCart called', [
            'request_data' => $request->all(),
            'user_id' => Auth::id(),
            'session_id' => session()->getId()
        ]);

        $request->validate([
            'design_id' => 'required',
            'title' => 'required|string|max:255',
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
            Log::error('Failed to save design to cart', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'فشل في حفظ التصميم: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete design from cart (like web design.delete-from-cart)
     */
    public function deleteFromCart(Request $request): JsonResponse
    {
        Log::info('deleteFromCart called', [
            'request_data' => $request->all(),
            'user_id' => Auth::id(),
            'session_id' => session()->getId()
        ]);

        $request->validate([
            'design_id' => 'required',
            'title' => 'required|string|max:255',
            'image_url' => 'required|string'
        ]);

        try {
            $userId = Auth::check() ? Auth::id() : null;
            $sessionId = Auth::check() ? null : session()->getId();

            // Find the cart design to delete
            $cartDesign = CartDesign::where('title', $request->title)
                ->where('image_url', $request->image_url)
                ->where('is_active', true)
                ->where(function($query) use ($userId, $sessionId) {
                    if ($userId) {
                        $query->where('user_id', $userId);
                    } else {
                        $query->where('session_id', $sessionId);
                    }
                })
                ->first();

            if (!$cartDesign) {
                return response()->json([
                    'success' => false,
                    'message' => 'التصميم غير موجود في السلة!'
                ]);
            }

            $cartDesign->delete();

            return response()->json([
                'success' => true,
                'message' => 'تم حذف التصميم من السلة بنجاح!'
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to delete design from cart', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'فشل في حذف التصميم من السلة: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add design to favorites (like web design.add-to-favorites)
     */
    public function addToFavorites(Request $request): JsonResponse
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

    /**
     * Add cart status to designs array
     */
    private function addCartStatusToDesigns($designs)
    {
        $userId = Auth::check() ? Auth::id() : null;
        $sessionId = Auth::check() ? null : session()->getId();
        
        foreach ($designs as &$design) {
            $design['in_cart'] = $this->isDesignInCart($design['id'], $design['image_url'], $userId, $sessionId);
        }
        
        return $designs;
    }

    /**
     * Check if design is already in cart
     */
    private function isDesignInCart($designId, $imageUrl, $userId, $sessionId)
    {
        $query = CartDesign::where('is_active', true)
            ->where('image_url', $imageUrl);
            
        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $sessionId);
        }
        
        return $query->exists();
    }

    /**
     * Upload multiple ready design files (images)
     */
    public function uploadReadyDesign(Request $request): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'يجب تسجيل الدخول أولاً'
            ], 401);
        }

        $request->validate([
            'design_files' => 'required|array|min:1|max:10', // Allow 1-10 files
            'design_files.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:10240', // 10MB max per file
        ]);

        try {
            $uploadedDesigns = [];
            $errors = [];

            foreach ($request->file('design_files') as $index => $file) {
                try {
                    // Generate unique filename
                    $filename = 'uploaded_designs/' . Str::uuid() . '.' . $file->getClientOriginalExtension();
                    
                    // Store the file
                    $path = Storage::disk('public')->putFileAs('', $file, $filename);
                    $imageUrl = Storage::url($path);

                    // Generate auto title from filename
                    $originalName = $file->getClientOriginalName();
                    $title = pathinfo($originalName, PATHINFO_FILENAME);
                    $title = $title ?: 'تصميم مرفوع - ' . ($index + 1);

                    // Create cart design entry
                    $cartDesign = CartDesign::create([
                        'user_id' => Auth::id(),
                        'title' => $title,
                        'design_data' => [
                            'original_design_id' => 'uploaded_' . Str::uuid(),
                            'source' => 'upload',
                            'original_filename' => $originalName
                        ],
                        'image_url' => $imageUrl,
                        'thumbnail_url' => $imageUrl,
                        'is_active' => true
                    ]);

                    $uploadedDesigns[] = [
                        'id' => $cartDesign->id,
                        'title' => $cartDesign->title,
                        'image_url' => $cartDesign->image_url,
                        'created_at' => $cartDesign->created_at
                    ];

                    Log::info('Design uploaded successfully', [
                        'user_id' => Auth::id(),
                        'design_id' => $cartDesign->id,
                        'filename' => $filename,
                        'image_url' => $imageUrl
                    ]);

                } catch (\Exception $fileError) {
                    $errors[] = "فشل في رفع الملف " . ($index + 1) . ": " . $fileError->getMessage();
                    Log::error('Failed to upload individual design', [
                        'error' => $fileError->getMessage(),
                        'file_index' => $index,
                        'user_id' => Auth::id()
                    ]);
                }
            }

            if (empty($uploadedDesigns)) {
                return response()->json([
                    'success' => false,
                    'message' => 'فشل في رفع جميع الملفات',
                    'errors' => $errors
                ], 500);
            }

            $message = count($uploadedDesigns) > 1 
                ? 'تم رفع ' . count($uploadedDesigns) . ' تصميمات بنجاح!'
                : 'تم رفع التصميم بنجاح!';

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $uploadedDesigns,
                'uploaded_count' => count($uploadedDesigns),
                'errors' => $errors // Include any partial failures
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to upload designs', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'فشل في رفع التصاميم: ' . $e->getMessage()
            ], 500);
        }
    }
}
