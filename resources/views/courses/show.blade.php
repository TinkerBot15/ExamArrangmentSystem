@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ $course->name }}</h1>
        <p><strong>Title:</strong> {{ $course->name }}</p>
        <p><strong>Code:</strong> {{ $course->code }}</p>
        <p><strong>Title:</strong> {{ $course->department }}</p>
    </div>
@endsection
