<?php

namespace App\Features\Comments\Controllers;

use App\Features\Comments\Actions\CreateCommentAction;
use App\Features\Comments\DTOs\CreateCommentDto;
use App\Features\Comments\Events\CommentCreated;
use App\Features\Comments\Requests\CreateCommentsRequest;
use App\Features\Comments\Resources\CommentResource;
use App\Features\Posts\Models\Post;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

class CreateCommentController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/posts/{post}/comment",
     *     operationId="createComment",
     *     tags={"Comments"},
     *     summary="Create a new comment",
     *     description="Creates a new comment on the specified post.",
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="post",
     *         in="path",
     *         required=true,
     *         description="ID of the post to comment on",
     *         @OA\Schema(type="integer", example=123)
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="Comment payload",
     *         @OA\JsonContent(
     *             required={"text"},
     *             @OA\Property(
     *                 property="text",
     *                 type="string",
     *                 minLength=3,
     *                 maxLength=10000,
     *                 example="This is a great post!"
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Comment created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="text", type="string", example="This is a great post!"),
     *                 @OA\Property(property="post_id", type="integer", example=123),
     *                 @OA\Property(property="user", type="object",
     *                     @OA\Property(property="id", type="integer", example=5),
     *                     @OA\Property(property="name", type="string", example="John Doe")
     *                 ),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-22T15:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-22T15:00:00Z")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Post not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Post not found.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="text",
     *                     type="array",
     *                     @OA\Items(type="string", example="The text field is required.")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function __invoke(CreateCommentsRequest $request, Post $post, CreateCommentAction $action)
    {
        $dto = CreateCommentDto::fromRequest($request->validated(), $post->id, auth()->id());
        $comment = $action->execute($dto);

        event(new CommentCreated($comment));

        return (new CommentResource($comment))
            ->response()
            ->setStatusCode(JsonResponse::HTTP_CREATED);
    }
}
