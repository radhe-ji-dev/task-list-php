<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Task;
use Illuminate\Http\Response;

class TaskTest extends TestCase
{

    public function test_EditView()
    {
        // Arrange
        $task = Task::factory()->create();

        // Act
        $response = $this->get(route('tasks.edit', ['task' => $task]));

        // Assert
        $response->assertStatus(200)
            ->assertViewIs('edit')
            ->assertViewHas('task', $task);
    }

    public function test_create_a_task()
    {
        $taskData = [
            'title' => 'New Task',
            'description' => 'This is a new task description.',
            'long_description' => 'This is a new task long description.'
        ];

        $response = $this->withHeaders([
            'X-CSRF-TOKEN' => csrf_token(),
        ])->post(route('tasks.store'), $taskData);


        $response->assertStatus(Response::HTTP_FOUND); // 302 Found
        unset($taskData['id']);

        //  $this->assertDatabaseHas('tasks', $taskData);


        $this->withoutMiddleware();
        $this->post(route('tasks.store'), $taskData);
    }

    public function test_returns_404_notask()
    {
        // Act: Make a GET request to the tasks show route with a nonexistent task ID
        $response = $this->get(route('tasks.show', ['task' => 12345]));

        // Assert
        $response->assertStatus(404); // Assert resource not found
    }


    public function test_title_and_description_invalid()
    {
        $invalidTaskData = [
            'title' => '', // Empty title
            'description' => '',
            'long_description' => ''
        ];

        $response = $this->post(route('tasks.store'), $invalidTaskData);

        // $response->assertStatus(Response::HTTP_NOT_FOUND); 

        $response->assertSessionHasErrors(['title', 'description']);
    }

    public function test_passes_validation_with_valid_data()
    {
        $validTaskData = [
            'title' => 'Valid Title',
            'description' => 'Valid Description',
            'long_description' => 'Valid Long Description'
        ];

        $response = $this->post(route('tasks.store'), $validTaskData);

        // Assert
        $response->assertStatus(Response::HTTP_FOUND);
    }

    public function test_toggle_task_()
    {
        // Create a task
        $task = Task::factory()->create();

        // Ensure the task is not completed
        //$this->assertFalse($task->is_completed);

        // Make a PUT request to the toggle-complete route
        $response = $this->put(route('tasks.toggle-complete', $task));

        // Assert that the task's completion status has been toggled
        $task->refresh();
        //$this->assertTrue($task->is_completed);

        // Assert a successful redirect
        $response->assertRedirect();
        $response->assertSessionHas('success', 'Task updated successfully!');
    }

    public function test_delete_a_task()
    {
        // Create a task
        $task = Task::factory()->create();

        // Make a DELETE request to the tasks.destroy route
        $response = $this->delete(route('tasks.destroy', $task));

        // Assert that the task has been deleted from the database
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);

        // Assert a successful redirect
        $response->assertRedirect(route('tasks.index'));
        $response->assertSessionHas('success', 'Task deleted successfully!');
    }
}
