@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ $student->name }}</h1>
        <p><strong>Matric Number:</strong> {{ $student->matric_number }}</p>
        <p><strong>Email:</strong> {{ $student->email }}</p>
        <p><strong>Phone Number:</strong> {{ $student->phone_number }}</p>
        <p><strong>Department:</strong> {{ $student->department }}</p>
        <p><strong>Created At:</strong> {{ $student->created_at }}</p>
        <p><strong>Updated At:</strong> {{ $student->updated_at }}</p>
        <a href="{{ route('students.index') }}" class="btn btn-primary">Back</a>
    </div>
@endsection
