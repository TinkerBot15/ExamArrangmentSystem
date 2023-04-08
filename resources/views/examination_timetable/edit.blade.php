@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Edit Examination Timetable</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('examination_timetable.update', $examination_timetable->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="form-group row">
                                <label for="course_code" class="col-md-4 col-form-label text-md-right">Course Code</label>

                                <div class="col-md-6">
                                    <input id="course_code" type="text" class="form-control @error('course_code') is-invalid @enderror" name="course_code" value="{{ $examination_timetable->course_code }}" required autocomplete="course_code" autofocus>

                                    @error('course_code')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="course_title" class="col-md-4 col-form-label text-md-right">Course Title</label>

                                <div class="col-md-6">
                                    <input id="course_title" type="text" class="form-control @error('course_title') is-invalid @enderror" name="course_title" value="{{ $examination_timetable->course_title }}" required autocomplete="course_title" autofocus>

                                    @error('course_title')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="exam_date" class="col-md-4 col-form-label text-md-right">Exam Date</label>

                                <div class="col-md-6">
                                    <input id="exam_date" type="date" class="form-control @error('exam_date') is-invalid @enderror" name="exam_date" value="{{ $examination_timetable->exam_date }}" required autocomplete="exam_date" autofocus>

                                    @error('exam_date')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="exam_start_time" class="col-md-4 col-form-label text-md-right">Exam Start Time</label>

                                <div class="col-md-6">
                                    <input id="exam_start_time" type="time" class="form-control @error('exam_start_time') is-invalid @enderror" name="exam_start_time" value="{{ $examination_timetable->exam_start_time }}" required autocomplete="exam_start_time" autofocus>

                                    @error('exam_start_time')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="exam_end_time" class="col-md-4 col-form-label text-md-right">Exam End Time</label>

                                <div class="col-md-6">
                                    <input id="exam_end_time" type="time" class="form-control @error('exam_end_time') is-invalid @enderror" name="exam_end_time" value="{{ $examination_timetable->exam_end_time }}" required autocomplete="exam_end_time" autofocus>

                                    @error('exam_end_time')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                            <label for="examination_hall_id" class="col-md-4 col-form-label text-md-right">{{ __('Examination Hall') }}</label>

                            <div class="col-md-6">
                                <select id="examination_hall_id" class="form-control @error('examination_hall_id') is-invalid @enderror" name="examination_hall_id" required>
                                    <option value="">-- Select Examination Hall --</option>
                                    @foreach($halls as $hall)
                                        <option value="{{ $hall->id }}" {{ old('examination_hall_id') == $hall->id ? 'selected' : '' }}>{{ $hall->name }}</option>
                                    @endforeach
                                </select>

                                @error('examination_hall_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Update') }}
                                </button>
                            </div>
                        </div>
@endsection
