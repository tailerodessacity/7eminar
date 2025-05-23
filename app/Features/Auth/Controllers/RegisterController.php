<?php

namespace App\Features\Auth\Controllers;

use App\Features\Auth\Actions\UserRegisterAction;
use App\Features\Auth\DTOs\UserRegisterDto;
use App\Features\Auth\Requests\UserRegisterRequest;
use App\Features\Auth\Resources\UserRegisterResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

class RegisterController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/register",
     *     tags={"Auth"},
     *     summary="Register a new user",
     *     operationId="registerUser",
     *     @OA\RequestBody(
     *         required=true,
     *         description="User registration data",
     *         @OA\JsonContent(
     *             required={"name", "email", "password", "password_confirmation"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="secret123"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="secret123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User registered successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", example="john@example.com")
     *             ),
     *             @OA\Property(property="token", type="string", example="1|abc.def.ghi")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="email", type="array", @OA\Items(type="string", example="The email has already been taken."))
     *             )
     *         )
     *     )
     * )
     */
    public function __invoke(UserRegisterRequest $request, UserRegisterAction $action)
    {
        $dto = UserRegisterDto::fromRequest($request->validated());
        $user = $action->execute($dto);

        $token = $user->createToken('access-token',
            ['comments:read', 'comments:create', 'comments:update']
        )->plainTextToken;

        return (new UserRegisterResource($user))
            ->additional(['token' => $token])
            ->response()
            ->setStatusCode(JsonResponse::HTTP_CREATED);
    }
}
