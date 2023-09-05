@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Create Examination Hall</h1>
        <form action="{{ route('examination_halls.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="seating_capacity">Capacity:</label>
                <input type="number" name="seating_capacity" id="seating_capacity" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="rows">Rows:</label>
                <input type="number" name="rows" id="rows" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="Columns">Columns:</label>
                <input type="number" name="columns" id="columns" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success">Create</button>
        </form>
    </div>
@endsection
