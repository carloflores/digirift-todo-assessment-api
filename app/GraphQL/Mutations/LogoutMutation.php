<?php

namespace App\GraphQL\Mutations;

use Illuminate\Support\Facades\Auth;

class LogoutMutation
{
    public function __invoke()
    {
        Auth::user()->currentAccessToken()->delete();
        return true;
    }
}