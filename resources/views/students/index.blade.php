@extends('layouts.app')

@section('content')


<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Students') }}</div>
                
                <div class="card-body">
                <a href="{{ route('students.create') }}" class="btn btn-success">Add Student</a>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Name</th>
                                <th scope="col">Matric Number</th>
                                <th scope="col">Email</th>
                                <th scope="col">Phone Number</th>
                                <th scope="col">Department</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $student)
                            <tr>
                                <th scope="row">{{ $student->id }}</th>
                                <td>{{ $student->name }}</td>
                                <td>{{ $student->matric_number }}</td>
                                <td>{{ $student->email }}</td>
                                <td>{{ $student->phone_number }}</td>
                                <td>{{ $student->department }}</td>
                                <td>
                                    <a href="{{ route('students.edit', $student->id) }}" class="btn btn-primary">Edit</a>
                                    <form action="{{ route('students.destroy', $student->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
</div>
        </div>
    </div>
</div>
@endsection
