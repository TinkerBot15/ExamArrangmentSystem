@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Examination Halls</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Capacity</th>
                    <th>Rows</th>
                    <th>Columns</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($examinationHalls as $examinationHall)
                    <tr>
                        <td>{{ $examinationHall->name }}</td>
                        <td>{{ $examinationHall->seating_capacity }}</td>
                        <td>{{ $examinationHall->rows }}</td>
                        <td>{{ $examinationHall->columns }}</td>
                        <td>
                            <a href="{{ route('examination_halls.show', $examinationHall->id) }}" class="btn btn-primary btn-sm">View</a>
                            <a href="{{ route('examination_halls.edit', $examinationHall->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('examination_halls.destroy', $examinationHall->id) }}" method="POST" style="display: inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <a href="{{ route('examination_halls.create') }}" class="btn btn-success">Add New Hall</a>
    </div>
@endsection
