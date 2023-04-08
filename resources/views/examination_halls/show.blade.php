@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ $hall->name }}</div>

                    <div class="card-body">
                        <p><strong>Capacity:</strong> {{ $hall->seating_capacity }}</p>
                        <p><strong>Rows:</strong> {{ $hall->rows }}</p>
                        <p><strong>Columns:</strong> {{ $hall->columns }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
