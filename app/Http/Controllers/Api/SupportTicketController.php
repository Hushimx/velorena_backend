<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\SupportTicketReply;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(
 *     name="Support Tickets",
 *     description="API endpoints for managing support tickets"
 * )
 */
class SupportTicketController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/support-tickets",
     *     summary="Get user's support tickets",
     *     tags={"Support Tickets"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filter by status",
     *         @OA\Schema(type="string", enum={"open", "in_progress", "pending", "resolved", "closed"})
     *     ),
     *     @OA\Parameter(
     *         name="priority",
     *         in="query",
     *         description="Filter by priority",
     *         @OA\Schema(type="string", enum={"low", "medium", "high", "urgent"})
     *     ),
     *     @OA\Parameter(
     *         name="category",
     *         in="query",
     *         description="Filter by category",
     *         @OA\Schema(type="string", enum={"technical", "billing", "general", "feature_request", "bug_report"})
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number",
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="tickets", type="array", @OA\Items(ref="#/components/schemas/SupportTicket")),
     *                 @OA\Property(property="pagination", ref="#/components/schemas/Pagination")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $user = Auth::user();
        $query = $user->supportTickets()->with(['replies' => function ($q) {
            $q->public()->latest()->limit(1); // Get latest public reply
        }]);

        // Apply filters
        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        if ($request->filled('priority')) {
            $query->byPriority($request->priority);
        }

        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        $tickets = $query->latest()->paginate(15);

        return response()->json([
            'success' => true,
            'data' => [
                'tickets' => $tickets->items(),
                'pagination' => [
                    'current_page' => $tickets->currentPage(),
                    'last_page' => $tickets->lastPage(),
                    'per_page' => $tickets->perPage(),
                    'total' => $tickets->total(),
                    'has_more_pages' => $tickets->hasMorePages(),
                ]
            ]
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/support-tickets",
     *     summary="Create a new support ticket",
     *     tags={"Support Tickets"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"subject", "description", "priority", "category"},
     *             @OA\Property(property="subject", type="string", example="Login issue"),
     *             @OA\Property(property="description", type="string", example="I cannot login to my account"),
     *             @OA\Property(property="priority", type="string", enum={"low", "medium", "high", "urgent"}, example="medium"),
     *             @OA\Property(property="category", type="string", enum={"technical", "billing", "general", "feature_request", "bug_report"}, example="technical")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Support ticket created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Support ticket created successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/SupportTicket")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function store(Request $request): JsonResponse
    {
        // Validation rules for API (no user_id required as we use Auth::user())
        $rules = [
            'subject' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
            'priority' => 'nullable|in:low,medium,high,urgent', // Made optional with default
            'category' => 'required|in:technical,billing,general,feature_request,bug_report',
            'attachments' => 'nullable|array',
            'attachments.*' => 'nullable|string',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $ticket = Auth::user()->supportTickets()->create([
            'subject' => $request->subject,
            'description' => $request->description,
            'priority' => $request->priority ?? 'medium', // Default to medium if not provided
            'category' => $request->category,
            'status' => 'open', // Set default status
            'attachments' => $request->attachments ?? [],
        ]);

        $ticket->load(['user']);

        return response()->json([
            'success' => true,
            'message' => 'Support ticket created successfully',
            'data' => $ticket
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/support-tickets/{id}",
     *     summary="Get a specific support ticket",
     *     tags={"Support Tickets"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Support ticket ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/SupportTicketWithReplies")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Support ticket not found"),
     *     @OA\Response(response=403, description="Forbidden - not your ticket"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function show(SupportTicket $supportTicket): JsonResponse
    {
        // Check if the ticket belongs to the authenticated user
        if ($supportTicket->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied'
            ], 403);
        }

        $supportTicket->load(['user', 'assignedAdmin', 'replies' => function ($q) {
            $q->public()->with(['user', 'admin'])->orderBy('created_at');
        }]);

        return response()->json([
            'success' => true,
            'data' => $supportTicket
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/support-tickets/{id}/replies",
     *     summary="Add a reply to a support ticket",
     *     tags={"Support Tickets"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Support ticket ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"message"},
     *             @OA\Property(property="message", type="string", example="Thank you for the update"),
     *             @OA\Property(property="attachments", type="array", @OA\Items(type="string"), description="File paths")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Reply added successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Reply added successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/SupportTicketReply")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=403, description="Forbidden - not your ticket"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function addReply(Request $request, SupportTicket $supportTicket): JsonResponse
    {
        // Check if the ticket belongs to the authenticated user
        if ($supportTicket->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied'
            ], 403);
        }

        // Check if ticket is open
        if (!$supportTicket->isOpen()) {
            return response()->json([
                'success' => false,
                'message' => 'This ticket is closed and cannot accept new replies'
            ], 422);
        }

        $validator = Validator::make($request->all(), SupportTicketReply::getValidationRules());

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $reply = $supportTicket->replies()->create([
            'user_id' => Auth::id(),
            'message' => $request->message,
            'attachments' => $request->attachments ?? [],
        ]);

        $reply->load(['user']);

        return response()->json([
            'success' => true,
            'message' => 'Reply added successfully',
            'data' => $reply
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/support-tickets/{id}/replies",
     *     summary="Get replies for a support ticket",
     *     tags={"Support Tickets"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Support ticket ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/SupportTicketReply"))
     *         )
     *     ),
     *     @OA\Response(response=403, description="Forbidden - not your ticket"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function getReplies(SupportTicket $supportTicket): JsonResponse
    {
        // Check if the ticket belongs to the authenticated user
        if ($supportTicket->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied'
            ], 403);
        }

        $replies = $supportTicket->replies()
            ->public()
            ->with(['user', 'admin'])
            ->orderBy('created_at')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $replies
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/support-tickets/statistics",
     *     summary="Get support ticket statistics for user",
     *     tags={"Support Tickets"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="total", type="integer", example=10),
     *                 @OA\Property(property="open", type="integer", example=3),
     *                 @OA\Property(property="closed", type="integer", example=7),
     *                 @OA\Property(property="by_priority", type="object", example={"high": 2, "medium": 5, "low": 3}),
     *                 @OA\Property(property="by_category", type="object", example={"technical": 4, "general": 3, "billing": 3})
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function statistics(): JsonResponse
    {
        $user = Auth::user();

        $stats = [
            'total' => $user->supportTickets()->count(),
            'open' => $user->supportTickets()->open()->count(),
            'closed' => $user->supportTickets()->closed()->count(),
            'by_priority' => $user->supportTickets()
                ->selectRaw('priority, count(*) as count')
                ->groupBy('priority')
                ->pluck('count', 'priority'),
            'by_category' => $user->supportTickets()
                ->selectRaw('category, count(*) as count')
                ->groupBy('category')
                ->pluck('count', 'category'),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}
