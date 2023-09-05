@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Examination Timetables</h1>
        
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3">
            @foreach($examination_timetables as $timetable)
                    <div class="col mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                            <h5 class="card-title"><strong>{{ str_replace(',', ', ', $timetable->course_code) }}</strong></h5>
                            <h6 class="card-subtitle mb-2 text-muted">{{ str_replace(',', ', ', $timetable->course_title) }}</h6>
                                <p class="card-text">Exam Date: {{ $timetable->exam_date }}</p>
                                <p class="card-text">Exam Time: {{ $timetable->exam_start_time }} - {{ $timetable->exam_end_time }}</p>
                                <p class="card-text">Examination Hall: {{ $timetable->examinationHall->name }}</p>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('examination_timetable.show', $timetable->id) }}" class="btn btn-info">
                                        <i class="fas fa-eye"></i> View Timetable
                                    </a>
                                    <a href="{{ route('examination_timetable.seating_arrangement', $timetable->id) }}" class="btn btn-primary">
                                        <i class="fas fa-chairs"></i> View Seating Arrangement
                                    </a>
                                    <a href="{{ route('examination_timetable.seating-arrangement-pdf', $timetable->id) }}" class="btn btn-danger">
                                        <i class="fas fa-file-pdf"></i> Download Seating Arrangement
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
            @endforeach
        </div>
    </div>
@endsection