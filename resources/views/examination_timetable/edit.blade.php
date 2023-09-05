<!-- resources/views/examination_timetable/edit.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Examination Timetable</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('examination_timetable.update', $examination_timetable->id) }}">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="course_code">Course Code</label>
                <input type="text" name="course_code" id="course_code" class="form-control" value="{{ old('course_code', $examination_timetable->course_code) }}">
            </div>

            <div class="form-group">
                <label for="course_title">Course Title</label>
                <input type="text" name="course_title" id="course_title" class="form-control" value="{{ old('course_title', $examination_timetable->course_title) }}">
            </div>

            <div class="form-group">
                <label for="exam_date">Exam Date</label>
                <input type="date" name="exam_date" id="exam_date" class="form-control" value="{{ old('exam_date', $examination_timetable->exam_date) }}">
            </div>

            <div class="form-group">
                <label for="exam_start_time">Exam Start Time</label>
                <input type="time" name="exam_start_time" id="exam_start_time" class="form-control" value="{{ old('exam_start_time', $examination_timetable->exam_start_time) }}">
            </div>

            <div class="form-group">
                <label for="exam_end_time">Exam End Time</label>
                <input type="time" name="exam_end_time" id="exam_end_time" class="form-control" value="{{ old('exam_end_time', $examination_timetable->exam_end_time) }}">
            </div>

            <div class="form-group">
                <label for="examination_hall_id">Examination Hall</label>
                <select name="examination_hall_id" id="examination_hall_id" class="form-control">
                    @foreach($halls as $hall)
                        <option value="{{ $hall->id }}" {{ $hall->id == $examination_timetable->examination_hall_id ? 'selected' : '' }}>
                            {{ $hall->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
@endsection
