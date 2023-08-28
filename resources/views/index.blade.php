<!-- resources/views/show.blade.php -->

@extends('layouts.app')

@section('title', 'The list of tasks')

@section('content')
    @forelse ($tasks as $task)
        <div>
            <!-- Display task information here -->
            <h2>{{ $task->title }}</h2>
            <p>{{ $task->description }}</p>
        </div>
    @empty
        <div>There are no tasks!</div>
    @endforelse
@endsection
