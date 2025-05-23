<?php

namespace App\Features\Comments\Controllers;

use App\Features\Comments\Actions\EditCommentAction;
use App\Features\Comments\DTOs\EditCommentDto;
use App\Features\Comments\Events\CommentCreated;
use App\Features\Comments\Models\Comment;
use App\Features\Comments\Requests\EditCommentsRequest;
use App\Features\Comments\Resources\CommentResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

class EditCommentController extends Controller
{
    /**
     * @OA\Put(
     *     path="/api/comments/{comment}",
     *     tags={"Comments"},
     *     summary="Update a comment",
     *     description="Updates the text of a specific comment.",
     *     operationId="updateComment",
     *     security={{"bearerAuth": {}}},
     *
     *     @OA\Parameter(
     *         name="comment",
     *         in="path",
     *         required=true,
     *         description="ID of the comment to update",
     *         @OA\Schema(type="integer", example=123)
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="Updated comment content",
     *         @OA\JsonContent(
     *             required={"text"},
     *             @OA\Property(property="text", type="string", example="Updated comment content")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Comment updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=123),
     *                 @OA\Property(property="text", type="string", example="Updated comment content"),
     *                 @OA\Property(property="post_id", type="integer", example=55),
     *                 @OA\Property(property="user", type="object",
     *                     @OA\Property(property="id", type="integer", example=7),
     *                     @OA\Property(property="name", type="string", example="Jane Doe")
     *                 ),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-22T15:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-22T15:05:00Z")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="This action is unauthorized.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Comment not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Comment not found.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="text", type="array",
     *                     @OA\Items(type="string", example="The text field is required.")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function __invoke(EditCommentsRequest $request, Comment $comment, EditCommentAction $action)
    {
        $dto = EditCommentDto::fromRequest($request->validated());
        $comment = $action->execute($dto, $comment);

        event(new CommentCreated($comment));

        return (new CommentResource($comment))
            ->response()
            ->setStatusCode(JsonResponse::HTTP_CREATED);
    }
}
