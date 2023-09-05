@extends('layouts.app')

@section('content')
<div class="container">
      <div class="row justify-content-center">
            <div class="col-md-8">
                  <div class="card">
                        <div class="card-header">{{ __('Edit Student Details') }}</div>

                        <div class="card-body">
                              <form method="POST" action="{{ route('students.update', $student->id) }}">
                                    @csrf
                                    @method('PUT')

                                    <div class="form-group row">
                                          <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                                          <div class="col-md-6">
                                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $student->name }}" required autocomplete="name" autofocus>

                                                @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                      <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                          </div>
                                    </div>

                                    <div class="form-group row">
                                          <label for="matric_number" class="col-md-4 col-form-label text-md-right">{{ __('Matric Number') }}</label>

                                          <div class="col-md-6">
                                                <input id="matric_number" type="text" class="form-control @error('matric_number') is-invalid @enderror" name="matric_number" value="{{ $student->matric_number }}" required autocomplete="matric_number">

                                                @error('matric_number')
                                                <span class="invalid-feedback" role="alert">
                                                      <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                          </div>
                                    </div>

                                    <div class="form-group row">
                                          <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                                          <div class="col-md-6">
                                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $student->email }}" required autocomplete="email">

                                                @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                      <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                          </div>
                                    </div>

                                    <div class="form-group row">
                                          <label for="phone_number" class="col-md-4 col-form-label text-md-right">{{ __('Phone Number') }}</label>

                                          <div class="col-md-6">
                                                <input id="phone_number" type="text" class="form-control @error('phone_number') is-invalid @enderror" name="phone_number" value="{{ $student->phone_number }}" required autocomplete="phone_number">

                                                @error('phone_number')
                                                <span class="invalid-feedback" role="alert">
                                                      <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                          </div>
                                    </div>

                                    <div class="form-group row">
                                          <label for="department" class="col-md-4 col-form-label text-md-right">{{ __('Department') }}</label>

                                          <div class="col-md-6">
                                                <input id="department" type="text" class="form-control @error('department') is-invalid @enderror" name="department" value="{{ $student->department }}" required autocomplete="department">

                                                @error('department')
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