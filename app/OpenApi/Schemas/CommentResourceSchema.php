<?php

namespace App\OpenApi\Schemas;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="CommentResource",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="post_id", type="integer", example=55),
 *         @OA\Property(
 *             property="user",
 *             type="object",
 *             @OA\Property(property="id", type="integer", example=4),
 *             @OA\Property(property="name", type="string", example="John Doe")
 *         ),
 *         @OA\Property(property="text", type="string", example="This is a great post!"),
 *         @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-22T12:34:56Z"),
 *         @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-22T12:34:56Z")
 *     )
 * )
 */
class CommentResourceSchema {}
