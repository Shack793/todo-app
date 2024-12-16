<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Todo;

class TodoApiTest extends TestCase
{
    use RefreshDatabase;

    // Test listing todos
    public function test_can_list_todos()
    {
        Todo::factory()->count(3)->create();

        $response = $this->getJson('/api/todos');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    // Test creating a new todo
    public function test_can_create_todo()
    {
        $payload = [
            'title' => 'Test Todo',
            'details' => 'This is a test',
            'status' => 'not_started',
        ];

        $response = $this->postJson('/api/todos', $payload);

        $response->assertStatus(201)
                 ->assertJsonFragment(['title' => 'Test Todo']);
    }

    // Test updating a todo
    public function test_can_update_todo()
    {
        $todo = Todo::factory()->create();

        $payload = ['status' => 'completed'];

        $response = $this->putJson("/api/todos/{$todo->id}", $payload);

        $response->assertStatus(200)
                 ->assertJsonFragment(['status' => 'completed']);
    }

    // Test deleting a todo
    public function test_can_delete_todo()
    {
        $todo = Todo::factory()->create();

        $response = $this->deleteJson("/api/todos/{$todo->id}");

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Todo deleted successfully']);
    }
}
