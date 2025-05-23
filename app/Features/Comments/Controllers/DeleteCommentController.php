<?php

namespace App\Features\Comments\Controllers;

use App\Features\Comments\Actions\DeleteCommentAction;
use App\Features\Comments\Models\Comment;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

class DeleteCommentController extends Controller
{
    /**
     * @OA\Delete(
     *     path="/api/comments/{comment}",
     *     tags={"Comments"},
     *     summary="Delete a comment",
     *     description="Deletes the specified comment.",
     *     operationId="deleteComment",
     *     security={{"bearerAuth": {}}},
     *
     *     @OA\Parameter(
     *         name="comment",
     *         in="path",
     *         required=true,
     *         description="ID of the comment to delete",
     *         @OA\Schema(type="integer", example=123)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Comment deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Deleted post successfully")
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
     *     )
     * )
     */
    public function __invoke(Comment $comment, DeleteCommentAction $action)
    {
        $action->execute($comment);
        return new JsonResponse(['message' => 'Deleted post successfully']);
    }
}
