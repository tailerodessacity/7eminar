<?php

namespace App\Features\Auth\Actions;

use App\Features\Auth\DTOs\UserLoginDto;
use App\Features\User\Models\User;
use Illuminate\Support\Facades\Hash;

class UserLoginAction
{
    public function execute(UserLoginDto $dto): array
    {
        $user = User::where('email', $dto->email)->first();

        if (!$user || !Hash::check($dto->password, $user->password)) {
            return [];
        }

        return [
            'user' => $user,
            'token' => $user->createToken(
                'access-token', ['comments:read', 'comments:create', 'comments:update'])
                ->plainTextToken,
        ];
    }
}
