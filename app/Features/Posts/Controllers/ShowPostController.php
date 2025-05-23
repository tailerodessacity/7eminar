<?php

namespace App\Features\Posts\Controllers;

use App\Features\Posts\Models\Post;
use App\Features\Posts\Resources\PostResource;
use App\Http\Controllers\Controller;
use OpenApi\Annotations as OA;

class ShowPostController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/posts/{post}",
     *     operationId="getPostWithComments",
     *     tags={"Posts"},
     *     summary="Get post with its comments",
     *     description="Returns a post and paginated list of its comments.",
     *
     *     @OA\Parameter(
     *         name="post",
     *         in="path",
     *         required=true,
     *         description="ID of the post to fetch",
     *         @OA\Schema(type="integer", example=42)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Post with comments",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=42),
     *             @OA\Property(property="title", type="string", example="This is a post title"),
     *             @OA\Property(property="comments", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="text", type="string", example="This is a comment"),
     *                 @OA\Property(property="post_id", type="integer", example=42),
     *                 @OA\Property(property="user", type="object",
     *                     @OA\Property(property="id", type="integer", example=5),
     *                     @OA\Property(property="name", type="string", example="Jane Doe")
     *                 ),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-22T15:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-22T15:01:00Z")
     *             )),
     *             @OA\Property(property="pagination", type="object",
     *                 @OA\Property(property="next_cursor", type="string", nullable=true, example="eyJpZCI6NDUxfQ=="),
     *                 @OA\Property(property="prev_cursor", type="string", nullable=true, example="eyJpZCI6NDQyfQ==")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Post not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="No query results for model [Post] 9999.")
     *         )
     *     )
     * )
     */
    public function __invoke(Post $post)
    {
        return new PostResource($post, $post->getPostComments());
    }
}
