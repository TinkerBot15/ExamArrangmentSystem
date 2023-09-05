@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Examination Timetables</h1>
        <a href="{{ route('examination_timetable.create') }}" class="btn btn-primary mb-3">Create Timetable</a>

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
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <a href="{{ route('examination_timetable.edit', $timetable->id) }}" class="btn btn-primary">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('examination_timetable.destroy', $timetable->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this timetable?')">
                                        <i class="fas fa-trash-alt"></i> Delete
                                    </button>
                                </form>
                            </div>
                            <div class="mt-3">
                                @if ($timetable->seatingArrangement)
                                    <button class="btn btn-success" disabled>
                                        <i class="fas fa-cogs"></i> Seating Arrangement Generated
                                    </button>
                                @else
                                    <a href="{{ route('examination_timetable.generateSeatingArrangement', $timetable->id) }}" class="btn btn-success">
                                        <i class="fas fa-cogs"></i> Generate Seating Arrangement
                                    </a>
                                @endif
                                <a href="{{ route('examination_timetable.seating_arrangement', $timetable->id) }}" class="btn btn-info">
                                    <i class="fas fa-eye"></i> View Seating Arrangement
                                </a>
                                <a href="{{ route('examination_timetable.seating-arrangement-pdf', $timetable->id) }}" class="btn btn-danger">
                                    <i class="fas fa-file-pdf"></i> Download Seating Arrangement
                                </a>
                                <a href="{{ route('examination_timetable.sendSMSNotifications', ['timetableId' => $timetable->id])}}" class="btn btn-dark">
                                    <i class="fas fa-envelope"></i> Send SMS Notification
                                </a>
                                <a href="{{ route('examination_timetable.student_list', ['timetableId' => $timetable->id])}}" class="btn btn-primary">
                                    <i class="fas fa-envelope"></i> Fetch SMS Notification Status
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
