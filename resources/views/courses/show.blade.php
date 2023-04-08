@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ $course->name }}</h1>
        <p><strong>Code:</strong> {{ $course->code }}</p>
        <p><strong>Title:</strong> {{ $course->title }}</p>
    </div>
@endsection
