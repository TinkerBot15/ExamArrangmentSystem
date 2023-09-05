<!-- resources/views/examination_timetable/show.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Examination Timetable Details</h1>

        <a href="{{ route('examination_timetable.seating_arrangement', $examination_timetable->id) }}" class="btn btn-primary">View Seating Arrangement</a>
    
        <div>
            <strong>Course Code:</strong> {{ $examination_timetable->course_code }}
        </div>
        <div>
            <strong>Course Title:</strong> {{ $examination_timetable->course_title }}
        </div>
        <div>
            <strong>Exam Date:</strong> {{ $examination_timetable->exam_date }}
        </div>
        <div>
            <strong>Exam Start Time:</strong> {{ $examination_timetable->exam_start_time }}
        </div>
        <div>
            <strong>Exam End Time:</strong> {{ $examination_timetable->exam_end_time }}
        </div>
        <div>
            <strong>Examination Hall:</strong> {{ $examination_timetable->examinationHall->name }}
        </div>

            @if(Gate::allows('isAdmin'))
        <div class="mt-4">
            <a href="{{ route('admin.examination_timetable.index') }}" class="btn btn-primary">Back</a>
        </div>
        @endif

        @if (Gate::allows('isInvigilator'))
            <a href="{{ route('invigilator.examination_timetable.index') }}" class="btn btn-primary">Back</a>
        @endif
    </div>
@endsection
