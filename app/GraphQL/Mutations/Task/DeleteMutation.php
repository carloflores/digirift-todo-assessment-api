<?php

namespace App\GraphQL\Mutations\Task;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\Task;

class DeleteMutation
{
    public function __invoke($root, array $args)
    {
        try {
            $task = Task::findOrFail($args['id']);

            if ($task->user_id !== Auth::id()) {
                throw ValidationException::withMessages([
                    'id' => 'You are not authorized to delete this task.'
                ]);
            }

            $task->delete();

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