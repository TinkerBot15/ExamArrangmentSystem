@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Add Course</h1>
        <form method="POST" action="{{ route('courses.store') }}">
            @csrf
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="code">Code:</label>
                <input type="text" name="code" id="code" class="form-control" required>
            </div>
            <div class="form-group">
            <label for="name">Department:</label>
                <select name="department" id="department" class="form-control">
                    <option value="">Select Department</option>
                    @foreach($courses as $department)
                        <option value="{{ $department }}">{{ $department }}</option>
                    @endforeach
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary">Save Course</button>
        </form>
    </div>
@endsection
