@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Examination Hall</h1>
        <form action="{{ route('examination_halls.update', $examinationHall->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ $examinationHall->name }}" required>
            </div>
            <div class="form-group">
                <label for="capacity">Capacity:</label>
                <input type="number" name="seating_capacity" id="seating_capacity" class="form-control" value="{{ $examinationHall->seating_capacity }}" required>
            </div>
            <div class="form-group">
                <label for="rows">Rows:</label>
                <input type="number" name="rows" id="rows" class="form-control" value="{{ $examinationHall->rows }}" required>
            </div>
            <div class="form-group">
                <label for="columns">Columns:</label>
                <input type="number" name="columns" id="columns" class="form-control" value="{{ $examinationHall->columns }}" required>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
@endsection
