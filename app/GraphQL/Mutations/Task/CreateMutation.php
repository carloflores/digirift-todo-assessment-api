<?php

namespace App\GraphQL\Mutations\Task;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\Task;

class CreateMutation
{
    public function __invoke($root, array $args)
    {
        $task = new Task();
        $task->title = $args['title'];
        $task->user_id = Auth::id();
        $task->status = false;
        $task->save();

        return $task;
    }
}