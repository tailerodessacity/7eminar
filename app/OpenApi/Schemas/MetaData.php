<?php

namespace App\OpenApi\Schemas;

/**
 * @OA\OpenApi(
 *     @OA\Info(
 *         title="My API",
 *         version="1.0.0",
 *         description="API documentation for my Laravel application",
 *         @OA\Contact(
 *             email="support@example.com"
 *         ),
 *         @OA\License(name="MIT")
 *     ),
 *     @OA\Server(
 *         url="http://localhost",
 *         description="Local dev server"
 *     ),
 *     @OA\Server(
 *         url="https://api.example.com",
 *         description="Production server"
 *     )
 * )
 */
class MetaData {}
