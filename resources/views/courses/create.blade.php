@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Add Course</h1>
        <form method="POST" action="{{ route('courses.store') }}">
            @csrf
            <div class="form-group">
                <label for="code">Code:</label>
                <input type="text" name="code" id="code" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" name="title" id="title" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Save Course</button>
        </form>
    </div>
@endsection
