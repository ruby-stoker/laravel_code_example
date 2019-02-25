<?php

namespace App\Services;

use App\Events\UserRegistered;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRegistrationService
{
    public function createUser(array $data): User
    {
        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        event(new UserRegistered($user));

        return $user;
    }
}