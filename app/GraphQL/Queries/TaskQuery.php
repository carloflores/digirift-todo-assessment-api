<?php 

namespace App\GraphQL\Queries;

use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TaskQuery
{
    public function __invoke($root, array $args)
    {
        return Task::where('user_id', Auth::id())->get();
    }
} 