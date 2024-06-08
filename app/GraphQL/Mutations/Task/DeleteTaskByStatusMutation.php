<?php

namespace App\GraphQL\Mutations\Task;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\Task;

class DeleteTaskByStatusMutation
{
    public function __invoke($root, array $args)
    {
        try {
            $status = $args['status'] ? 1 : 0;
            Task::where('status', $status)->where('user_id', Auth::id())->delete();

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