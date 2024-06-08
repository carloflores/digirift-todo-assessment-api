<?php 

namespace App\GraphQL\Queries;

use App\Models\User;

class UserQuery
{
    public function __invoke($root, array $args)
    {
        return User::all();
    }
} 