@extends('layouts.app')

@section('content')
  <h1>Add New Student</h1>

  <form action="{{ route('students.store') }}" method="POST">
    @csrf

    <div class="form-group">
      <label for="name">Name:</label>
      <input type="text" name="name" id="name" class="form-control" required>
    </div>

    <div class="form-group">
      <label for="department">Department:</label>
      <input type="text" name="department" id="department" class="form-control" required>
    </div>

    <div class="form-group">
      <label for="level">Level:</label>
      <select name="level" id="level" class="form-control" required>
        <option value="100">100</option>
        <option value="200">200</option>
        <option value="300">300</option>
        <option value="400">400</option>
        <option value="500">500</option>
      </select>
    </div>

    <button type="submit" class="btn btn-primary">Add Student</button>
  </form>
@endsection
