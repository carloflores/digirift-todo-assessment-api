<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TestModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_be_created(): void
    {
        $user = User::factory()->create();

        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }
    public function test_task_can_be_created(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);

        $this->assertDatabaseHas('tasks', ['id' => $task->id]);
    }

    public function test_task_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);

        $this->assertEquals($user->id, $task->user_id);

    }

    public function test_user_has_many_tasks(): void
    {
        $user = User::factory()->create();
        Task::factory(3)->create(['user_id' => $user->id]);

        $this->assertEquals(3, $user->tasks->count());
    }   

    public function test_task_can_be_updated(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);

        $task->update(['title' => 'New Task Name']);
        $task->save();

        $this->assertDatabaseHas('tasks', ['title' => 'New Task Name']);
    }

    public function test_task_can_be_deleted(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);

        $task->delete();

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }
}