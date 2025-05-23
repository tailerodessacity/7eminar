<?php

namespace App\Features\Auth\Controllers;

use App\Features\Auth\Actions\UserLoginAction;
use App\Features\Auth\DTOs\UserLoginDto;
use App\Features\Auth\Requests\UserLoginRequest;
use App\Features\Auth\Resources\AuthUserResource;
use App\Http\Controllers\Controller;
use OpenApi\Annotations as OA;

class LoginController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/sign-in",
     *     tags={"Auth"},
     *     summary="Login user and get token",
     *     operationId="loginUser",
     *     @OA\RequestBody(
     *         required=true,
     *         description="User login credentials",
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="secret123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User logged in successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", example="john@example.com"),
     *                 @OA\Property(property="token", type="string", example="1|abc.def.ghi")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Invalid credentials",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid.")
     *         )
     *     )
     * )
     */
    public function __invoke(UserLoginRequest $request, UserLoginAction $action)
    {
        $dto = UserLoginDto::fromRequest($request->validated());
        $data = $action->execute($dto);

        return (new AuthUserResource($data['user']))
            ->additional(['token' => $data['token']]);
    }
}
