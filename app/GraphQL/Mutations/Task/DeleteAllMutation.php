<?php

namespace App\GraphQL\Mutations\Task;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\Task;

class DeleteAllMutation
{
    public function __invoke($root, array $args)
    {
        try {
            $task = Task::where('user_id', Auth::id())->delete();

            return [
                'msg' => 'Task deleted successfully.'
            ];
        } catch (\Exception $e) {
            throw ValidationException::withMessages([
                'id' => $e->getMessage()
            ]);
        }
    }
}