<?php

namespace App\Features\Auth\Actions;

use App\Features\Auth\DTOs\UserRegisterDto;
use App\Features\User\Models\User;

class UserRegisterAction
{
    public function execute(UserRegisterDto $dto): User
    {
        return User::create([
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => bcrypt($dto->password),
        ]);
    }
}
