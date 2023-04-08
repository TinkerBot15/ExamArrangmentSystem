@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Create Examination Timetable') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('examination_timetable.store') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="course_code" class="col-md-4 col-form-label text-md-right">{{ __('Course Code') }}</label>

                            <div class="col-md-6">
                                <input id="course_code" type="text" class="form-control @error('course_code') is-invalid @enderror" name="course_code" value="{{ old('course_code') }}" required autocomplete="course_code" autofocus>

                                @error('course_code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="course_title" class="col-md-4 col-form-label text-md-right">{{ __('Course Title') }}</label>

                            <div class="col-md-6">
                                <input id="course_title" type="text" class="form-control @error('course_title') is-invalid @enderror" name="course_title" value="{{ old('course_title') }}" required autocomplete="course_title">

                                @error('course_title')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="exam_date" class="col-md-4 col-form-label text-md-right">{{ __('Exam Date') }}</label>

                            <div class="col-md-6">
                                <input id="exam_date" type="date" class="form-control @error('exam_date') is-invalid @enderror" name="exam_date" value="{{ old('exam_date') }}" required autocomplete="exam_date">

                                @error('exam_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="exam_start_time" class="col-md-4 col-form-label text-md-right">{{ __('Exam Start Time') }}</label>

                            <div class="col-md-6">
                                <input id="exam_start_time" type="time" class="form-control @error('exam_start_time') is-invalid @enderror" name="exam_start_time" value="{{ old('exam_start_time') }}" required autocomplete="exam_start_time">

                                @error('exam_start_time')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="exam_end_time" class="col-md-4 col-form-label text-md-right">{{ __('Exam End Time') }}</label>

                            <div class="col-md-6">
                                <input id="exam_end_time" type="time" class="form-control @error('exam_end_time') is-invalid @enderror" name="exam_end_time" value="{{ old('exam_end_time') }}" required autocomplete="exam_end_time">

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
                                    {{ __('Create') }}
                                </button>
                            </div>
                        </div>
@endsection