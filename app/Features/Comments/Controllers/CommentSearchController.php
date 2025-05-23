<?php

namespace App\Features\Comments\Controllers;

use App\Features\Comments\Requests\CommentSearchRequest;
use App\Features\Comments\Resources\CommentResource;
use App\Features\Comments\Services\CommentSearchService;
use Illuminate\Routing\Controller;
use OpenApi\Annotations as OA;

class CommentSearchController extends Controller
{
    public function __construct(
        protected CommentSearchService $commentSearchService
    ) {}

    /**
     * Search comments with filtering and pagination
     *
     * @OA\Get(
     *     path="/api/comments/search",
     *     operationId="searchComments",
     *     tags={"Comments"},
     *     summary="Search comments with filters",
     *     description="Searches comments using various filters with cursor-based pagination. Requires authentication.",
     *
     *     @OA\Parameter(
     *         name="text",
     *         in="query",
     *         required=false,
     *         description="Full-text search in comment content",
     *         @OA\Schema(type="string", minLength=2, example="interesting")
     *     ),
     *     @OA\Parameter(
     *         name="author",
     *         in="query",
     *         required=false,
     *         description="Partial match for author's name",
     *         @OA\Schema(type="string", minLength=2, example="Alex")
     *     ),
     *     @OA\Parameter(
     *         name="post_id",
     *         in="query",
     *         required=false,
     *         description="Filter by specific post ID",
     *         @OA\Schema(type="integer", format="int64", minimum=1, example=123)
     *     ),
     *     @OA\Parameter(
     *         name="cursor",
     *         in="query",
     *         required=false,
     *         description="Cursor token for pagination (base64 encoded)",
     *         @OA\Schema(type="string", example="eyJpZCI6MTIzLCJjcmVhdGVkX2F0IjoiMjAyMy0wMS0wMSJ9")
     *     ),
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         required=false,
     *         description="Number of items per page (default: 15, max: 50)",
     *         @OA\Schema(type="integer", minimum=1, maximum=50, default=15)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="text", type="string", example="This is a great post!"),
     *                     @OA\Property(property="post_id", type="integer", example=42),
     *                     @OA\Property(
     *                         property="user",
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=5),
     *                         @OA\Property(property="name", type="string", example="John Doe")
     *                     ),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-22T15:00:00Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-22T15:00:00Z")
     *                 )
     *             ),
     *             @OA\Property(
     *                 property="links",
     *                 type="object",
     *                 @OA\Property(property="first", type="string", nullable=true, example="http://localhost/api/comments/search?limit=15"),
     *                 @OA\Property(property="last", type="string", nullable=true, example="http://localhost/api/comments/search?cursor=eyJpZCI6MjAxfQ&limit=15"),
     *                 @OA\Property(property="prev", type="string", nullable=true),
     *                 @OA\Property(property="next", type="string", nullable=true, example="http://localhost/api/comments/search?cursor=eyJpZCI6MjAxfQ&limit=15")
     *             ),
     *             @OA\Property(
     *                 property="meta",
     *                 type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="from", type="integer", example=1),
     *                 @OA\Property(property="last_page", type="integer", example=3),
     *                 @OA\Property(property="path", type="string", example="http://localhost/api/comments/search"),
     *                 @OA\Property(property="per_page", type="integer", example=15),
     *                 @OA\Property(property="to", type="integer", example=15),
     *                 @OA\Property(property="total", type="integer", example=42)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid cursor parameter",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Invalid cursor parameter"),
     *             @OA\Property(property="code", type="string", example="invalid_cursor")
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
     *         description="Validation errors",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="post_id",
     *                     type="array",
     *                     @OA\Items(type="string", example="The selected post id is invalid.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Server Error"),
     *             @OA\Property(property="code", type="string", example="server_error")
     *         )
     *     )
     * )
     */
    public function __invoke(CommentSearchRequest $request)
    {
        $comments = $this->commentSearchService->search($request->validated());

        return CommentResource::collection($comments);
    }
}
