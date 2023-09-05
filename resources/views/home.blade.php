@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <p>{{ __('You are logged in!') }}</p>
                    <hr>

                    <div class="dashboard-links">
                        @if (Auth::user()->role === 'user')
                            <a href="{{ route('students.index') }}" class="btn btn-primary">{{ __('Students') }}</a>
                            <a href="{{ route('courses.index') }}" class="btn btn-success">{{ __('Courses') }}</a>
                            <a href="{{ route('examination_halls.index') }}" class="btn btn-info">{{ __('Examination Halls') }}</a>
                            <a href="{{ route('examination_timetable.index') }}" class="btn btn-warning">{{ __('Examination Timetable') }}</a>
                        @endif

                        @if (Auth::user()->role === 'invigilator')
                            <a href="{{ route('examination_timetable.invigilator.index') }}" class="btn btn-info">{{ __('Invigilator Timetable') }}</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
