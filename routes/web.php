<?php

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

class Task
{
    public int $id;
    public string $title;
    public string $description;
    public ?string $long_description;
    public bool $completed;
    public string $created_at;
    public string $updated_at;

    public function __construct(
        int $id,
        string $title,
        string $description,
        ?string $long_description,
        bool $completed,
        string $created_at,
        string $updated_at
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->long_description = $long_description;
        $this->completed = $completed;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }
}

$tasks = [
    new Task(
        1,
        'Buy groceries',
        'Task 1 description',
        'Task 1 long description',
        false,
        '2023-03-01 12:00:00',
        '2023-03-01 12:00:00'
    ),
    new Task(
        2,
        'Sell old stuff',
        'Task 2 description',
        null,
        false,
        '2023-03-02 12:00:00',
        '2023-03-02 12:00:00'
    ),
    new Task(
        3,
        'Learn programming',
        'Task 3 description',
        'Task 3 long description',
        true,
        '2023-03-03 12:00:00',
        '2023-03-03 12:00:00'
    ),
    new Task(
        4,
        'Take dogs for a walk',
        'Task 4 description',
        null,
        false,
        '2023-03-04 12:00:00',
        '2023-03-04 12:00:00'
    ),
];

Route::get('/', function () {
    return redirect()->route('tasks.index');
});

Route::get('/tasks', function () use ($tasks) {
    return view('index', [
        'tasks' => $tasks
    ]);
})->name('tasks.index');

// Route::get('/tasks/{id}', function ($id) use ($tasks) {
//     $task = collect($tasks)->firstWhere('id', $id);

//     if (!$task) {
//         abort(Response::HTTP_NOT_FOUND);
//     }

//     return view('show', ['task' => $task]);
// })->name('tasks.show');

Route::get('/tasks/{id}', function ($id){
    return view('show', ['task' =>\App\Models\Task::find($id)]);
})->name('tasks.show');


Route::fallback(function () {
    return 'Still got somewhere!';
});