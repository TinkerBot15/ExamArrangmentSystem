@extends('layouts.app')
@section('content')
<table class="table table-striped">
    <thead>
        <tr>
            <th>Course Code</th>
            <th>Course Title</th>
            <th>Exam Date</th>
            <th>Exam Start Time</th>
            <th>Exam End Time</th>
            <th>Examination Hall</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($examination_timetables as $examination_timetable)
        <tr>
            <td>{{ $examination_timetable->course_code }}</td>
            <td>{{ $examination_timetable->course_title }}</td>
            <td>{{ $examination_timetable->exam_date }}</td>
            <td>{{ $examination_timetable->exam_start_time }}</td>
            <td>{{ $examination_timetable->exam_end_time }}</td>
            <td>{{ $examination_timetable->examinationHall->name }}</td>
            <td>
                <a href="{{ route('examination_timetable.show', $examination_timetable->id) }}" class="btn btn-primary btn-sm">View</a>
                <a href="{{ route('examination_timetable.edit', $examination_timetable->id) }}" class="btn btn-warning btn-sm">Edit</a>
                <form action="{{ route('examination_timetable.destroy', $examination_timetable->id) }}" method="POST" style="display: inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection