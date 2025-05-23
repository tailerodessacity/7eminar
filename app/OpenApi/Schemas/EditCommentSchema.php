<?php

namespace App\OpenApi\Schemas;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="EditCommentRequest",
 *     type="object",
 *     required={"text"},
 *     @OA\Property(
 *         property="text",
 *         type="string",
 *         minLength=3,
 *         maxLength=10000,
 *         example="This is a updated post!"
 *     )
 * )
 */
class EditCommentSchema {}
