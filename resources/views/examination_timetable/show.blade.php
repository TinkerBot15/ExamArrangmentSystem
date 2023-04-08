@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Examination Timetable Details</h1>
        <table class="table">
            <tbody>
                <tr>
                    <td>Course Code:</td>
                    <td>{{ $examination_timetable->course_code }}</td>
                </tr>
                <tr>
                    <td>Course Title:</td>
                    <td>{{ $examination_timetable->course_title }}</td>
                </tr>
                <tr>
                    <td>Exam Date:</td>
                    <td>{{ $examination_timetable->exam_date }}</td>
                </tr>
                <tr>
                    <td>Start Time:</td>
                    <td>{{ $examination_timetable->exam_start_time }}</td>
                </tr>
                <tr>
                    <td>End Time:</td>
                    <td>{{ $examination_timetable->exam_end_time }}</td>
                </tr>
                <tr>
                    <td>Examination Hall:</td>
                    <td>{{ $examination_timetable->examinationHall->name }}</td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection
