<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $other_user;
    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->other_user = User::factory()->create();

        $this->user->access_token = $this->user->createToken('auth')->plainTextToken;

    }


    public function test_task_created(): void
    {

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->user->access_token
        ])->postJson('/graphql', [
            'query' => '
                mutation createTask($title: String!) {
                    createTask(title: $title) {
                        id
                        title
                        status
                        user_id
                    }
                }
            ',

            'variables' => [
                'title' => 'Test Title',
            ],
        ]);

        $data = json_decode($response->getContent(), true);

        $response->assertJson([
            'data' => [
                'createTask' => [
                    'id' => $data['data']['createTask']['id'],
                    'title' => 'Test Title',
                    'status' => false,
                    'user_id' => $this->user->id
                ]
            ]
        ]);
    }
    public function test_task_updated(): void
    {

        $task = Task::factory()->create(['user_id' => $this->user->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->user->access_token
        ])->postJson('/graphql', [
            'query' => '
                mutation updateTask($title: String!, $id: ID!, $status: Boolean!) {
                    updateTask(title: $title, id: $id, status: $status) {
                        id
                        title
                        status
                        user_id
                    }
                }
            ',

            'variables' => [
                'id' => $task->id,
                'title' => 'Test Title Updated',
                'status' => true,
            ],
        ]);

        $data = json_decode($response->getContent(), true);

        $response->assertJson([
            'data' => [
                'updateTask' => [
                    'id' => $data['data']['updateTask']['id'],
                    'title' => 'Test Title Updated',
                    'status' => true,
                    'user_id' => $this->user->id
                ]
            ]
        ]);
    }
    public function test_task_deleted(): void
    {

        $task = Task::factory()->create(['user_id' => $this->user->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->user->access_token
        ])->postJson('/graphql', [
            'query' => '
                mutation deleteTask($id: ID!) {
                    deleteTask(id: $id) {
                        msg
                    }
                }
            ',

            'variables' => [
                'id' => $task->id
            ],
        ]);

        $data = json_decode($response->getContent(), true);

        $response->assertJson([
            'data' => [
                'deleteTask'=> [
                    'msg' => 'Task deleted successfully.'
                ]
            ]
        ]);
    }
    public function test_task_delete_by_status(): void
    {

        $task = Task::factory()->create(['user_id' => $this->user->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->user->access_token
        ])->postJson('/graphql', [
            'query' => '
                mutation deleteTaskByStatus($status: Boolean!) {
                    deleteTaskByStatus(status: $status) {
                        msg
                    }
                }
            ',

            'variables' => [
                'status' => false
            ],
        ]);

        $data = json_decode($response->getContent(), true);

        $response->assertJson([
            'data' => [
                'deleteTaskByStatus'=> [
                    'msg' => 'Task deleted successfully.'
                ]
            ]
        ]);
    }
    public function test_delete_all_task_by_current_user(): void
    {

        $task = Task::factory()->create(['user_id' => $this->user->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->user->access_token
        ])->postJson('/graphql', [
            'query' => '
                mutation {
                    deleteAllTask {
                        msg
                    }
                }
            '
        ]);

        $data = json_decode($response->getContent(), true);

        $response->assertJson([
            'data' => [
                'deleteAllTask'=> [
                    'msg' => 'Task deleted successfully.'
                ]
            ]
        ]);
    }

    public function test_update_constraints(): void
    {

        $task = Task::factory()->create(['user_id' => $this->other_user->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->user->access_token
        ])->postJson('/graphql', [
            'query' => '
                mutation updateTask($title: String!, $id: ID!, $status: Boolean!) {
                    updateTask(title: $title, id: $id, status: $status) {
                        id
                        title
                        status
                        user_id
                    }
                }
            ',

            'variables' => [
                'id' => $task->id,
                'title' => 'Test Title Updated',
                'status' => true,
            ],
        ]);

        $response->assertJsonFragment([
               "message" => "You are not authorized to delete this task."
        ]);
    }
    public function test_delete_constraints(): void
    {

        $task = Task::factory()->create(['user_id' => $this->other_user->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->user->access_token
        ])->postJson('/graphql', [
            'query' => '
                mutation deleteTask($id: ID!) {
                    deleteTask(id: $id) {
                        msg
                    }
                }
            ',

            'variables' => [
                'id' => $task->id
            ],
        ]);

        $response->assertJsonFragment([
            "message" => "You are not authorized to delete this task."
        ]);
    }
}
